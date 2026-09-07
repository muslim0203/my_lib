# marketplace - infratuzilma: tarmoq, volume va ROLLBACK

Bu fayl S1 (Platform & CI) ishi doirasida yaratildi. `.gitlab-ci.yml`,
`docker-compose.yml`, `docker-compose.prod.yml` shu hujjatga ishora qiladi.

> Bu hujjatdagi buyruqlar **bu mashinada bajarilmadi: Docker mavjud emas**.
> Ular ko'rib chiqish (review) va operator uchun yozilgan.

---

## 1. Tarmoq va volume'lar

| Nomi          | Turi    | Nima uchun                                                |
|---------------|---------|-----------------------------------------------------------|
| `marketplace` | network | Barcha konteynerlar shu bridge tarmog'ida gaplashadi. `docker-compose*.yml` da `external: true` — ya'ni compose uni **yaratmaydi**, oldindan mavjud bo'lishi kerak. |
| `marketplace` | volume  | PostgreSQL ma'lumotlari (`/var/lib/postgresql/data`).      |
| `rabbitmq`    | volume  | RabbitMQ holati.                                           |

Birinchi ishga tushirish (idempotent, mavjud ma'lumotga tegmaydi):

```
make init
```

`make init` oxirida tarmoq subnet'ini chop etadi. Uni
`docker/database/postgresql/pg_hba.conf` dagi keng RFC1918 diapazonlar o'rniga
yozib qo'yish tavsiya etiladi.

### Nega `external: true` saqlandi

Uni compose boshqaruviga o'tkazish tarmoq nomini `<project>_marketplace` ga
o'zgartiradi va ishlab turgan stenddagi konteynerlarni bir-biridan uzib qo'yadi.
Shuning uchun nom o'zgarmadi, faqat yaratish `make init` ga ko'chirildi.

---

## 2. Portlar

| Servis    | Konteyner porti | Hostga chiqarilganmi                      |
|-----------|-----------------|-------------------------------------------|
| nginx     | 80              | dev: `127.0.0.1:9090`, prod: `${APP_HTTP_BIND:-127.0.0.1:9090}` |
| php-fpm   | 9000            | **YO'Q** (faqat docker tarmog'i ichida)   |
| postgres  | 5432            | **YO'Q**                                  |
| rabbitmq  | 5672 / 15672    | dev: faqat `127.0.0.1`; prod: **YO'Q**    |

---

## 3. Deploy oqimi (prod)

1. `deploy-to-prod` — rsync `/var/www/marketplace/backend` ga (manual).
2. `composer-install-prod` — `composer install --no-dev --optimize-autoloader` (manual).
3. `run-migration-prod` — `php artisan migrate --force` (manual).
4. `app-optimize-prod` — `optimize:clear` + `config:cache` + `route:cache` +
   `view:cache` + `storage:link --force` + `queue:restart` +
   `kill -USR2 1` (FPM graceful reload, opcache yangilanadi).

`run-seeder-prod` **o'chirilgan** — sababi `.gitlab-ci.yml` ichida yozilgan.

---

## 4. ROLLBACK

### 4.0. Har qanday rollbackdan OLDIN — baza zaxirasi

```
docker compose -f docker-compose.prod.yml exec -T database \
    pg_dump -U "$DB_USERNAME" -Fc "$DB_DATABASE" > /backup/marketplace-$(date +%F-%H%M).dump
```

Tashqi (managed) DB bo'lsa, provayderning snapshot mexanizmidan foydalaning.
**Volume hech qachon o'chirilmaydi** (`docker volume rm marketplace` = ma'lumot yo'qolishi).

### 4.1. Faqat KOD ni orqaga qaytarish (migratsiyasiz deploy)

Eng tez va eng xavfsiz yo'l — oldingi commit'dan qayta deploy:

1. GitLab'da `main` ni oldingi ishlaydigan commit'ga qaytaring
   (`git revert <commit>` — `--force push` EMAS).
2. `deploy-to-prod` → `composer-install-prod` → `app-optimize-prod` ni qo'lda ishga tushiring.
3. Tekshirish:
   ```
   docker compose -f docker-compose.prod.yml ps          # hammasi healthy
   docker compose -f docker-compose.prod.yml logs --tail=100 php
   curl -sS -o /dev/null -w '%{http_code}\n' http://127.0.0.1:9090/
   ```

> `app-optimize-prod` ni **o'tkazib yubormang**: `opcache.validate_timestamps=0`
> bo'lgani uchun FPM eski kodni keshda ushlab turadi va rollback "ishlamagandek"
> ko'rinadi.

### 4.2. MIGRATSIYA ni orqaga qaytarish

```
docker exec marketplace_php_cli php artisan migrate:rollback --step=1 --force
```

`migrate:rollback` faqat migratsiyada `down()` to'g'ri yozilgan bo'lsa ishlaydi.
Ustun/jadval o'chirilgan bo'lsa **ma'lumot qaytmaydi** — 4.0 dagi dump'dan tiklang:

```
docker compose -f docker-compose.prod.yml exec -T database \
    pg_restore -U "$DB_USERNAME" -d "$DB_DATABASE" --clean --if-exists < /backup/<fayl>.dump
```

### 4.3. Konteyner darajasidagi rollback

```
docker compose -f docker-compose.prod.yml up -d --no-deps --force-recreate php
docker compose -f docker-compose.prod.yml up -d --no-deps --force-recreate nginx
```

Yangi image yiqilsa, oldingi image tag'iga qayting va shu buyruqni takrorlang.

### 4.4. Navbat ishchisi

Deploydan keyin `queue:restart` chaqirilmasa, ishchilar **eski kod** bilan
job bajarishda davom etadi:

```
docker compose -f docker-compose.prod.yml exec queue php artisan queue:restart
docker compose -f docker-compose.prod.yml restart queue
```

Yiqilgan job'lar: `php artisan queue:failed`, qayta urinish: `queue:retry all`.

---

## 5. Konfiguratsiya o'zgarganda operator bajarishi SHART bo'lgan qadamlar

1. **`.env` da `DB_PASSWORD` majburiy.** `docker-compose*.yml` da default parol
   olib tashlandi (`${DB_PASSWORD:?...}`), u yo'q bo'lsa compose ishga tushmaydi.
2. **`POSTGRES_HOST_AUTH_METHOD: trust` olib tashlandi.** Bu **mavjud** volume'dagi
   parolni o'zgartirmaydi — `POSTGRES_PASSWORD` faqat birinchi `initdb` da ishlaydi.
   Mavjud baza uchun:
   ```
   docker compose -f docker-compose.prod.yml exec database psql -U postgres
   SET password_encryption = 'scram-sha-256';
   ALTER USER postgres WITH PASSWORD '<yangi parol>';
   \q
   ```
   So'ng `.env` dagi `DB_PASSWORD` ni yangilang va `php` / `queue` / `scheduler`
   konteynerlarini qayta ishga tushiring.
3. **md5 → scram-sha-256.** `pg_hba.conf` endi `scram-sha-256` talab qiladi.
   md5 xeshli foydalanuvchilar **ulanolmaydi**, to'g'ri parol kiritsa ham.
   Har biri uchun 2-banddagi `ALTER USER ... PASSWORD` ni bajaring.
   Tekshirish:
   ```
   SELECT rolname, substring(rolpassword for 12) FROM pg_authid;
   -- "SCRAM-SHA-2" bilan boshlansa - to'g'ri.
   ```
4. **Prod'da DB/broker tashqi.** `.env.example` da hali `DB_HOST=database`,
   `RABBITMQ_HOST=rabbitmq` turibdi. Serverdagi `.env` da haqiqiy host nomlarini
   qo'ying, yoki `--profile selfhosted` bilan ishga tushiring.
5. **Scheduler faqat BITTA nusxada.** Host'dagi eski cron yozuvini o'chiring:
   ```
   crontab -l ; ls -la /etc/cron.d/
   ```
6. **RAM byudjetini tasdiqlang.** `docker/php/pool.d/www.conf-local` dagi
   `pm.max_children = 12` **2 GB faraziga** asoslangan, o'lchangan emas.
   `docker stats` va `ps -o rss -C php-fpm` bilan tekshiring.

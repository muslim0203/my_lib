# Marketplace

Laravel 10 (PHP 8.2+) marketplace: JWT API va Blade admin paneli.
Ma'lumotlar bazasi **PostgreSQL**, navbat **RabbitMQ**.

> Diqqat: ilova PostgreSQL uchun yozilgan. Migratsiyalar va bir qator
> so'rovlar SQLite'da **ishlamaydi** (quyida "Ma'lum cheklovlar" ga qarang).

---

## 1. Birinchi o'rnatish

```bash
cp .env.example .env
make init            # docker tarmog'i va volume'larini yaratadi (idempotent)
make build
make up-dev          # dev profil: PostgreSQL va RabbitMQ ni ham ko'taradi
```

`.env` ni to'ldiring. Majburiy va ixtiyoriy kalitlar `.env.example` da
guruhlab izohlangan. Sirlar hech qachon repozitoriyga yozilmaydi.

Kalitlarni **faqat bir marta**, birinchi o'rnatishda yarating:

```bash
docker compose exec php-cli php artisan key:generate
docker compose exec php-cli php artisan jwt:secret
```

> Bu ikki qiymatni keyingi deploylarda **qayta yaratmang**. `APP_KEY` ni
> almashtirish shifrlangan qiymatlarni va sessiyalarni buzadi;
> `JWT_SECRET` ni almashtirish barcha amaldagi tokenlarni bekor qiladi.

Migratsiyalar va boshlang'ich ma'lumotlar:

```bash
make db-migrate
docker compose exec php-cli php artisan db:seed
```

### Birinchi admin hisobi

Ochiq matnli standart parol **olib tashlandi**. Admin faqat
konfiguratsiya orqali yaratiladi:

```
ADMIN_INITIAL_USERNAME=admin
ADMIN_INITIAL_EMAIL=<pochta>
ADMIN_INITIAL_PASSWORD=<kamida 12 belgi>
```

Bu qiymatlar bo'sh bo'lsa `AdminUserSeeder` **to'xtaydi** va hech qanday
admin yaratmaydi. Mavjud admin hech qachon qayta yozilmaydi — takroriy
seed uning parolini ham, huquqlarini ham tiklamaydi. Serverda ishlatib
bo'lgach, bu qiymatlarni muhitdan olib tashlash tavsiya etiladi.

---

## 2. Kundalik buyruqlar

```bash
make up / make down          # lokal stack
make up-dev / make down-dev  # + PostgreSQL va RabbitMQ
make login-php-cli           # konteyner ichiga kirish
make db-migrate              # migratsiyalar
make test                    # phpunit (CI bilan bir xil muhitda)
make phpstan                 # statik tahlil
make help                    # barcha targetlar
```

---

## 3. Production

Production alohida compose faylida: `docker-compose.prod.yml`.
Unda restart siyosati, healthcheck, queue worker va scheduler bor va
production `php.ini` ishlatiladi.

Ma'lumotlar bazasi va broker **standart holatda tashqi (managed)
xizmatlar** deb qabul qilingan. Ular ham shu hostda kerak bo'lsa:

```bash
docker compose -f docker-compose.prod.yml --profile selfhosted up -d
```

Deploy tartibi:

```bash
make prod-build
make prod-up
docker compose -f docker-compose.prod.yml exec queue php artisan migrate --force
make prod-optimize     # kesh qurish + storage:link + queue restart + FPM reload
```

> `opcache.validate_timestamps=0` bo'lgani uchun yangi kod **faqat**
> konteyner qayta yaratilganda yoki FPM graceful reload qilinganda
> ko'rinadi. `make prod-optimize` buni o'zi bajaradi.

Tarmoq, volume va ortga qaytish tartibi: `docker/ROLLBACK.md`.

---

## 4. Fayllar va saqlash

Yuklangan fayllar web root'dan **tashqarida**, `storage/app/private` da
saqlanadi va faqat avtorizatsiya qiladigan `GET /api/file-view/{filename}`
marshruti orqali beriladi.

- Bepul mahsulot muqovasi va oldindan ko'rish fayllari ochiq.
- Pullik mahsulotning manba fayli va audiosi uchun **tasdiqlangan to'lov**
  talab qilinadi. Order qatorining shunchaki mavjudligi yetarli emas.
- Shaxsiy hujjatlar (diplom, litsenziya, sertifikat, patent) faqat
  moderator xodim uchun ochiq.

Mavjud o'rnatishda:

1. Eski fayl yozuvlari uchun migratsiya `files.path` ni himoyalangan
   marshrutga qaratadi. Fayllarning o'zi **ko'chirilmaydi va
   o'chirilmaydi**; o'qishda eski disklar ham tekshiriladi.
2. Fayllarni `storage/app/private` ga ko'chirish tavsiya etiladi.
3. `public/storage` symlinkini pullik fayllar ko'chirilgunicha
   **yaratmang** — aks holda ular yana ochiq bo'lib qoladi.

---

## 5. Testlar

```bash
vendor/bin/phpunit    # mahalliy PHP bilan
make test             # CI bilan bir xil konteyner muhitida
```

Testlar alohida, xotiradagi bazadan foydalanadi va ishlab chiqish
bazasiga tegmaydi.

---

## 6. Ma'lum cheklovlar

- **To'liq migratsiya to'plami SQLite'da ishlamaydi.** `php artisan migrate`
  `enum_notification_messages` jadvalidagi `drop column` da yiqiladi
  (indeksga bog'liqlik). Ilova PostgreSQL uchun yozilgan va shunday
  qoldirildi. Shu sababli to'liq HTTP darajasidagi integratsiya testlari
  faqat PostgreSQL muhitida bajarilishi mumkin; mavjud testlar kerakli
  jadvallarni o'zi tuzadi.
- **Throttle va scheduler umumiy kesh talab qiladi.** `CACHE_DRIVER=file`
  bilan OTP/API cheklovlari har bir worker uchun alohida hisoblanadi va
  `->onOneServer()` ishlamaydi. Bir nechta worker bo'lsa `redis` qo'ying.
- **Vite ishlatilmaydi.** Birorta Blade `@vite` chaqirmaydi, CI asset
  build qilmaydi; admin `public/assets/**` dagi tayyor bundle'lardan
  foydalanadi. `vite.config.js` va `package.json` hozircha o'chirilmadi.
- Bog'liqliklar auditi (`composer audit`, `npm audit`) bu muhitda
  bajarilmadi — tarmoq yo'q.

Bandlar bo'yicha to'liq holat: `docs/remediation-status.md`.

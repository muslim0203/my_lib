# Yakuniy hisobot

Manba: `claude-marketplace-orchestrator-prompts.md` (audit paketi).
Band-band batafsil holat: [`remediation-status.md`](remediation-status.md).

---

## Holat

**Deployga tayyor emas.**
Mahalliy tekshiruvlar o'tdi; production muhiti hali tekshirilmagan.

Sabab: barcha tasdiqlangan to'lov/autentifikatsiya/avtorizatsiya chetlab
o'tish yo'llari yopildi va mahalliy tekshiruvlar toza, lekin uchta
release darvozasi ochiq qolmoqda:

1. Infratuzilma (Docker, nginx→FPM, PostgreSQL) bu mashinada **umuman
   ishga tushirilmadi** — Docker ham, PostgreSQL ham mavjud emas.
2. Bog'liqliklar auditi bajarilmadi (tarmoq yo'q), `laravel/framework`
   esa qo'llab-quvvatlash oynasining oxirida.
3. Mavjud o'rnatishda eski pullik fayllarni maxfiy diskka ko'chirish —
   operator qadami, hali bajarilmagan.

---

## Tekshirilgan versiya

| | |
|---|---|
| Branch | `master` |
| Baseline | `c3c9958` (ish boshlanishidan oldin yaratilgan snapshot) |
| Yakuniy kod commiti | `1116fbd` (undan keyingi commitlar faqat hujjat) |
| Hajmi | `c3c9958..1116fbd`: 136 fayl, +9982 / −776 |

Repozitoriy ish boshida git ostida **emas** edi. Har bir o'zgarishni
ortga qaytarish mumkin bo'lishi uchun avval baseline commit yaratildi.

> Commit raqamlari bir marta o'zgargan: author pochtasi
> `muslim0203@gmail.com` ga to'g'rilanib, tarix qayta yozilgan.
> Fayl mazmuni o'zgarmagan (tree hash `3516a2db` - aynan o'sha).

Commitlar:

```
1116fbd test: cover report filter branch selection and SQL bindings
12ce434 chore: raise PHPStan to level 5 and fix the defects it surfaced
4c94ee2 fix(admin): RBAC, CSRF on destructive routes, locale allow-list,
        employee IDOR, duplicate route names
2719894 fix(pay): fail closed on unconfigured Click/Payme credentials,
        redact signatures
e18bce3 chore: infra hardening, docs, phpstan level 3
482da82 wip: security remediation checkpoint (entitlement, OTP, files,
        exceptions, CORS, SQL)
```

---

## Yopilgan bandlar

**Blokerlar:** B1, B2, B3, B4, B5, B6
**Kritik/yuqori:** S1, S2, S3, S4, S5, S6, S7, S8
**O'rta:** M1, M2, M3, M4, M5, M6, M7, M9, M10
**Past:** L1, L4, L5, L6, L7

> Muhim farq: bu bandlarning bir qismi **runtime'da tasdiqlangan**
> (B3, S1, S2, M1, M7, M9, M10, S7, S8, L6, L7 — buyruq chiqishi yoki
> o'tgan test bilan), qolganlari esa **faqat kod darajasida tuzatilgan**
> (B1, B2, B5, B6, M5, M6 — Docker yo'qligi sababli sinab ko'rilmadi).
> Har bir band bo'yicha aniq holat `remediation-status.md` da.

Eng muhim uchtasi:

- **S1 — bepul xarid zaifligi.** `GET product/buy/{id}` narx yoki turni
  umuman tekshirmasdan istalgan **pullik** mahsulotga `TYPE_FREE` order
  yaratardi; `ProductViewResource` esa order *mavjudligiga* qarab manba
  kitob va audioni ochardi. Endi marshrut `POST`, faqat haqiqatan bepul
  mahsulot uchun ishlaydi, kirish huquqi esa to'lov tizimi tasdiqlagan
  orderni talab qiladi. Yangi tekshiruv bazada **allaqachon mavjud**
  soxta `free` orderlarni ham bloklaydi.
- **B3 — deploy blokeri.** `php artisan route:list` yiqilardi
  (`Unresolvable dependency ... PaymeService`), demak `route:cache` ham
  ishlamasdi.
- **S2 — OTP.** 4 xonali `rand()`, ochiq matnda saqlash, `==` bilan
  solishtirish, urinish chegarasining yo'qligi va butun ilovada
  o'chirilgan throttle.

---

## Muhim o'zgarishlar

**Yangi fayllar**

| Fayl | Nima uchun |
|---|---|
| `app/Core/Services/FileManager/FileAccessService.php` | Fayllarga kirishning yagona chegarasi |
| `app/Http/Controllers/Api/FileManager/FileViewController.php` | `User-Agent` "avtorizatsiyasi" o'rniga huquq tekshiruvi |
| `database/migrations/..._repoint_file_paths_to_guarded_route.php` | Eski fayl havolalarini himoyalangan marshrutga qaratish |
| `database/migrations/..._harden_users_verify_mail_tokens.php` | OTP uchun `token_hash`, `attempts`, `used_at` |
| `database/seeders/RoleSeeder.php` | Lockout'ning oldini oluvchi bir martalik o'tish qadami |
| `docker-compose.prod.yml`, `docker/php/**/php.ini-production`, `.dockerignore`, `docker/ROLLBACK.md` | Production kompozitsiya, ini va rollback tartibi |
| 9 ta test fayli | Quyidagi "Tekshiruvlar" bo'limiga qarang |

**Xatti-harakati o'zgargan joylar (ataylab)**

- `product/buy/{id}`: `GET` → `POST`; pullik mahsulotga `403`.
- Fayl havolalari `/storage/...` o'rniga `/api/file-view/...`.
- `ValidationException`: `500` → **`422`**, `data.errors` bilan.
- `AuthenticationException` → `401`, `AuthorizationException` → `403`
  (ilgari ikkalasi ham `500`).
- `APP_DEBUG=false` da kutilmagan xatolar umumlashtiriladi (ilgari
  `APP_ENV != production` bo'lsa to'liq SQL matni mijozga ketardi).
- CORS: `CORS_ALLOWED_ORIGINS` dagi aniq ro'yxat (ilgari buzuq qiymat
  hech bir origin'ga mos kelmasdi); nginx CORS bloklari olib tashlandi.
- `api` guruhidan `StartSession` olib tashlandi, `throttle:api` yoqildi.
- 18 ta destruktiv `GET` marshruti `DELETE` ga o'tkazildi va CSRF tiklandi.
- `proverb/list` endi JWT talab qiladi (takrorlangan himoyasiz nusxa
  olib tashlandi) — **bu mijozga ta'sir qiladi va egasi tomonidan
  tasdiqlanishi kerak**.
- API tomonidagi 7 ta marshrut nomiga `api.` prefiksi qo'shildi.
- Hisobot turlari (`BOOKS_FREE`, `BOOKS_NOT_BOUGHT`) endi **haqiqatan
  ishlaydi** — ilgari solishtiruv doim `false` bo'lgani uchun hech qachon
  bajarilmagan, ya'ni bu endpointlar boshqa natija qaytaradi.

---

## Tekshiruvlar (haqiqiy natijalar)

| Buyruq | Natija |
|---|---|
| `vendor/bin/phpunit` | **OK (97 test, 340 assertion)**, exit 0 |
| `vendor/bin/phpstan analyse` (daraja 5) | **No errors**, exit 0 |
| `php artisan route:list` | exit 0, **256 marshrut** |
| `php artisan config:cache` | exit 0 |
| `php artisan route:cache` | exit 0 |
| `php artisan view:cache` | exit 0 |

Muhit: PHP 8.2.12 (XAMPP), SQLite (xotirada), Laravel 10.48.28.

**Boshlang'ich holat:** 2 ta test bor edi, 1 tasi yiqilardi; PHPStan
daraja 1; `route:list` va `route:cache` umuman ishlamasdi.

**Testlar tasodifan o'tmayotganining dalili.** Ikki joyda tuzatishdan
oldingi kod vaqtincha tiklanib, testlar qayta ishga tushirildi:

| Test to'plami | Baseline kod bilan | Tuzatilgan kod bilan |
|---|---|---|
| `PaymentSecurityTest` | 2 xato + 2 yiqilish | 9 test o'tadi |
| `ReportFilterTest` + `PaginationFilterTest` | **36 xato** | 37 test o'tadi |

Test fayllari: `EntitlementTest`, `OtpTest`, `PaymentSecurityTest`,
`ReportFilterTest`, `PaginationFilterTest`, `AdminRbacTest`,
`LocaleValidationTest`, `ExceptionHandlerTest`, `CorsTest`, `ExampleTest`.

---

## Bajarilmagan yoki yiqilgan tekshiruvlar

| Nima | Sabab |
|---|---|
| Konteynerlarni ko'tarish, nginx→FPM so'rovi, DB ulanishi, healthcheck, queue/scheduler | **Docker mavjud emas.** B1, B2, B5, B6, M5, M6 faqat konfiguratsiya darajasida tuzatilgan |
| Hisobot SQL'larini haqiqiy bajarish | **PostgreSQL mavjud emas.** So'rovlar PG'ga xos (`json_agg`, `filter (where ...)`); testlar `DB::select` ni ushlab qoladi, SQL bajarilmaydi |
| To'liq HTTP darajasidagi integratsiya testlari | Loyihaning migratsiya to'plami SQLite'da yiqiladi (M8, quyida) |
| `composer audit`, `npm audit`, `composer outdated` | **Tarmoq va composer yo'q.** Auditdagi "48/14/2 advisory" raqamlari **tasdiqlanmagan** va bu hisobotda tasdiqlangan sifatida keltirilmaydi |
| CSRF himoyasini test bilan isbotlash | Laravel PHPUnit ostida `VerifyCsrfToken` ni chetlab o'tadi (`VerifyCsrfToken.php:74`) — bunday test yolg'on ishonch bergan bo'lardi. Dalil statik: marshrutlar `DELETE`, `withoutMiddleware` chaqiruvlari olib tashlangan, middleware `web` guruhida |
| GitLab pipeline | Runner yo'q |

---

## Ochiq bandlar

| ID | Xavf | Keyingi qadam |
|---|---|---|
| **S9** | Yuqori | `laravel/framework v10.48.28` qo'llab-quvvatlash oynasining oxirida; `nunomaduro/larastan` lock faylida `abandoned`; Docker image'lari `php:8.3.0-*` (birinchi reliz, keyingi patchsiz). Tarmoq bor mashinada `composer audit --locked` va yangilanish rejasi kerak |
| **M8** | O'rta | Migratsiyalar SQLite'da yiqiladi (`enum_notification_messages` dagi indeksga bog'liq `drop column`). Ilova PostgreSQL uchun yozilgan va shunday qoldirildi — bu ongli qaror, lekin integratsiya testlari faqat PG muhitida mumkin |
| **L2** | Past | Vite zanjiri butunlay ishlatilmaydi (birorta Blade `@vite` chaqirmaydi, CI build qilmaydi). O'chirish tavsiya etiladi — mahsulot qarori |
| **L3** | Past | `auth:sanctum` mavjud bo'lmagan guardga murojaat qiladi; `SmsAuthService::login()` — `// TODO`. Iste'molchilari to'liq kuzatilmagani uchun o'chirilmadi |
| — | O'rta | `sendTokenToMail` noma'lum har qanday pochta uchun foydalanuvchi yaratadi. Throttle bilan yumshatildi, mahsulot oqimi o'zgartirilmadi |
| — | O'rta | `BOOKS_NOT_BOUGHT` hisoboti **birinchi marta haqiqatan bajariladi** — uning SQL'i amalda hech qachon ishlamagan, PG'da tekshirilishi shart |
| — | Past | `EmployeeController` guruhi ataylab faqat `auth` da qoldi (o'z profili); filter sahifalaridagi tugmalar `@can` bilan o'ralmagan (server 403 qaytaradi, lekin tugma ko'rinadi) |

---

## Ma'lumotlar va fayllarni o'tkazish

1. **Backup** — migratsiyalardan oldin baza va `storage/app` nusxasi.
2. **Migratsiyalar** (`--force`). Ikkalasi ham qo'shuvchi: ustun qo'shadi
   va `files.path` ni himoyalangan marshrutga qaratadi. **Hech qanday
   ma'lumot o'chirilmaydi.** `down()` metodlari qaytaruvchi.
3. **Eski OTP kodlari bekor qilinadi** — foydalanuvchilar yangi kod
   so'raydi. Bu kutilgan holat.
4. **Fayllar.** Yangi yuklamalar `storage/app/private` ga tushadi. Eski
   fayllar **ko'chirilmadi va o'chirilmadi**; o'qishda eski disklar ham
   tekshiriladi, shuning uchun tizim ishlashda davom etadi.
   Ko'chirish tavsiya etiladi, va **`public/storage` symlinki pullik
   fayllar ko'chirilgunicha yaratilmasligi kerak** — aks holda ular yana
   ochiq bo'lib qoladi.
5. **Soxta orderlar.** Bazadagi pullik mahsulotlarga tegishli
   `payment_type = 'free'` orderlarni ko'rib chiqish kerak: ular eski
   zaiflik orqali yaratilgan bo'lishi mumkin. Yangi kod ularni
   bloklaydi, lekin ular tekshiruvdan o'tishi lozim.
6. **Admin rollari — MAJBURIY, bir marta:**

   ```bash
   php artisan db:seed --class="Database\Seeders\RoleSeeder" --force
   ```

   Mavjud adminlarda rol yo'q; bu buyruqsiz ular paneldan chiqib qoladi.
   Roli borlar tegilmaydi, shuning uchun qayta ishga tushirish xavfsiz.
7. **PostgreSQL.** `trust` olib tashlangani mavjud volume'dagi parolni
   o'rnatmaydi (`POSTGRES_PASSWORD` faqat birinchi `initdb` da ishlaydi):
   `ALTER USER ... PASSWORD ...` qo'lda bajarilishi kerak. md5 → scram
   o'tishida ham parollar qayta o'rnatilishi shart.
8. **Kalitlar.** `APP_KEY` va `JWT_SECRET` faqat birinchi o'rnatishda
   yaratiladi va keyingi deploylarda **saqlanadi**.

---

## Deploy va ortga qaytish

Tartib `README.md` va `docker/ROLLBACK.md` da. Qisqacha:

```bash
make prod-build && make prod-up
docker compose -f docker-compose.prod.yml exec queue php artisan migrate --force
make prod-optimize    # kesh + storage:link + queue restart + FPM reload
```

`opcache.validate_timestamps=0` bo'lgani uchun yangi kod faqat konteyner
qayta yaratilganda yoki FPM graceful reload qilinganda ko'rinadi;
`make prod-optimize` buni bajaradi.

**Ortga qaytish cheklovlari.** Kod darajasida ortga qaytish oddiy
(`c3c9958` gacha har bir commit alohida). Ma'lumotlar darajasida:
migratsiyalarning `down()` metodlari qo'shilgan ustunlarni olib tashlaydi
va `files.path` ni tiklaydi, lekin **eski OTP kodlari qaytarilmaydi**
(ular ataylab bekor qilingan). Fayllar ko'chirilgan bo'lsa, ortga
qaytish ularni avtomatik qaytarmaydi — bu qadam qo'lda rejalashtirilishi
kerak.

---

## Sizdan kerak bo'lgan ma'lumot

Quyidagilar ishni to'sib turgan yoki qaror talab qiladigan savollar:

1. **Production frontend va admin domenlari** — `CORS_ALLOWED_ORIGINS`
   uchun (kamida ikkita origin yozilishi kerak).
2. **Server RAM/CPU** — FPM `pm.max_children = 12` hozircha **faraz**
   (2 GB byudjet, ~128 MB worker), o'lchov emas.
3. **To'lovlar yoqiladimi** — `PAYME_*` / `CLICK_*` bo'sh qolsa tizim
   fail-closed ishlaydi va hech qanday to'lovni qabul qilmaydi.
4. **Bu birinchi deploymi yoki mavjud baza va fayllar bormi** — yuqoridagi
   o'tkazish qadamlari shunga qarab o'zgaradi.
5. **`proverb/list` ochiq bo'lishi kerakmi?** Hozir JWT talab qiladi
   (dasturchining dastlabki niyati bo'yicha). Agar u ommaviy kontent
   bo'lsa, qaytarish kerak.
6. **Ishonchli proxy manzillari va HTTPS topologiyasi** — HSTS ataylab
   qo'shilmadi, chunki server hozir faqat `listen 80` da.

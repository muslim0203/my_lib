# Audit bandlari bo'yicha holat

Bu jadval `claude-marketplace-orchestrator-prompts.md` dagi AUDIT REGISTER
asosida tuzilgan va har bir band repozitoriyda **qayta tekshirilgan**.
Auditdagi da'volar bajarilishi shart bo'lgan buyruq emas, tekshirilishi
kerak bo'lgan dalil sifatida qabul qilindi.

Holatlar: `unverified`, `confirmed`, `already-fixed`, `in-progress`,
`fixed-unverified`, `verified`, `blocked`.

## Muhitning cheklovlari (hisobotni o'qishdan oldin)

Bu mashinada mavjud: PHP 8.2.12 (XAMPP), Node 20, git.
Mavjud **emas**: Docker, `docker compose`, PostgreSQL, `composer` (faqat
tarmoq talab qiladigan `composer.phar`), tarmoq.

Buning oqibatlari:

- Infratuzilma bandlari (B1, B2, B5, B6, M5, M6) faqat konfiguratsiya
  darajasida tuzatildi. Konteynerlar ko'tarilmadi, nginx→FPM so'rovi
  bajarilmadi, DB ulanishi tekshirilmadi. Bular **target muhitda**
  tasdiqlanishi shart.
- Loyihaning to'liq migratsiya to'plami SQLite'da **ishlamaydi** (quyida
  M8 ga qarang), PostgreSQL esa yo'q. Shu sababli to'liq HTTP darajasidagi
  integratsiya testlari bajarilmadi; testlar kerakli jadvallarni o'zi
  tuzib, haqiqiy servis/repozitoriy mantig'ini tekshiradi.
- `composer audit` / `npm audit` bajarilmadi. Auditdagi "48 Composer
  advisories, 14 high, 2 npm advisories" raqamlari **tasdiqlanmagan** va
  bu hujjatda tasdiqlangan sifatida keltirilmaydi.

## Audit hisobidagi nomuvofiqlik

Paket sarlavhasida 6 bloker, 9 kritik/yuqori, 11 o'rta, 7 past deyilgan.
Reyestrda esa 10 ta o'rta band (M1-M10) ko'rsatilgan; 11-band mavjud emas.
Yo'qolgan band taxmin qilinmadi va o'ylab topilmadi. PDF 12-sahifasida
va'da qilingan 97 ta environment kaliti ro'yxati ham yo'q.

Haqiqiy inventar (bu repozitoriydan olingan, taxmin emas):
`config/**` da **166 ta** turli `env()` kaliti ishlatiladi; tuzatishdan
oldin `.env.example` da ulardan **67 tasi** bor edi. Ya'ni "97" raqamiga
mos keladigan hech narsa topilmadi.

---

## Bloker bandlar

| ID | Holat | Dalil va natija |
|---|---|---|
| B1 | fixed-unverified | Tasdiqlandi: `docker/php/pool.d/www.conf-local:1` da `listen = 127.0.0.1:9000`, `docker/nginx/config/default.conf:31` da esa `fastcgi_pass php:9000`. Konteynerlararo 127.0.0.1 yetib bormaydi. Log kataloglari yaratilmagan, fayl nomlari begona loyihadan (`pm.gov.uz.log`). Tuzatildi; **runtime tekshiruv bajarilmadi (Docker yo'q)**. |
| B2 | fixed-unverified | Tasdiqlandi: compose mavjud bo'lmagan `docker/postgres/` katalogini mount qiladi (haqiqiy yo'l `docker/database/postgresql/`), `POSTGRES_HOST_AUTH_METHOD: trust` barcha DB autentifikatsiyasini o'chiradi. Qo'shimcha: `pg_hba.conf:6` da `host all all all md5`, `postgresql.conf` da `listen_addresses='*'` va `ssl=off`. Tuzatildi; **runtime tekshiruv bajarilmadi**. Mavjud volume uchun parol `ALTER USER` bilan qo'lda o'rnatilishi kerak. |
| B3 | verified | **Runtime bilan tasdiqlandi.** Tuzatishdan oldin `php artisan route:list` yiqilardi: `Unresolvable dependency resolving [Parameter #0 $merchantId] in class PaymeService`. Sabab: `->give(config('payme.merchant_id'))` — sozlama `null` bo'lganda konteyner buni "binding yo'q" deb qabul qiladi. Bu `route:cache` ni ham buzardi, ya'ni deployni to'sardi. Endi `->give(fn () => config(...))`. Natija: `route:list` exit 0, **256 marshrut**. `.env.example` ga yetishmagan PAYME_*, CLICK_*, GOOGLE_*, AUTHENTICATION_PROVIDER_ADMIN kalitlari qo'shildi. |
| B4 | fixed-unverified | Tasdiqlandi: `FileManagerService` `Storage::put()` ni standart (`local`) diskka yozardi, DB'ga esa `/storage...` yo'lini saqlardi, `public/storage` symlinki esa umuman yo'q edi — ya'ni havolalar hech qachon ishlamagan. Endi fayllar `storage/app/private` ga yoziladi, havolalar himoyalangan `api/file-view` marshrutiga qaraydi, eski yozuvlar uchun migratsiya bor va o'qishda eski disklar ham tekshiriladi. Fayllar **o'chirilmadi va ko'chirilmadi**. |
| B5 | fixed-unverified | Tasdiqlandi: `.gitlab-ci.yml` da `composer install` `--no-dev` siz (Telescope, Ignition, PHPUnit, Faker serverga tushadi), test bosqichi umuman yo'q, `run-seeder-prod` — bir bosishda productionni seed qiladigan tugma, `networks: external: true` qo'lda yaratishni talab qiladi. Tuzatildi; **pipeline ishga tushirilmadi (GitLab runner yo'q)**. |
| B6 | fixed-unverified | Tasdiqlandi: `docker/php/fpm/config/php.ini-local` — `php.ini-development` nusxasi: `display_errors=On` (503), `expose_php=On` (398), `memory_limit=4096M` (430), `max_execution_time=1000` (408), `upload_max_filesize=15M` (850). Ilova esa 70 MB fayl kutadi (`config/filesystems.php: max_upload_size = 70*1024`). Production ini yaratildi; **runtime chegara testi bajarilmadi**. |

---

## Kritik va yuqori bandlar

| ID | Holat | Dalil va natija |
|---|---|---|
| S1 | verified | **Eng jiddiy band.** Tasdiqlandi: `ProductService::buy()` narx yoki turni **umuman** tekshirmasdi va istalgan mahsulotga `TYPE_FREE` order yaratardi; `ProductViewResource` esa order *mavjudligiga* qarab `source_file` va `audio_files` ni ochardi. Ya'ni: GET so'rov → bepul order → pullik kitob/audio ochiq. Tuzatildi: `Product::isFree()` yagona qoida, `hasEntitlement()` pullik mahsulot uchun to'lov tizimi (payme/click) tasdiqlagan orderni talab qiladi, marshrut `GET` dan `POST` ga o'tkazildi. Regressiya testlari o'tdi. Muhim: yangi tekshiruv bazada **allaqachon mavjud** soxta `free` orderlarni ham bloklaydi. |
| S2 | verified | Tasdiqlandi: OTP `rand(1000, 9999)` (4 xona, CSPRNG emas), bazada **ochiq matnda**, solishtirish `==` (`EmailUserProvider:142`), urinishlar hisobi yo'q, TTL sozlamasining standart qiymati yo'q edi (`env('MAIL_CODE_EXPIRE_AT')` → `addMinutes(null)` → 0 daqiqa), `throttle` esa butun ilovada o'chirilgan edi. Tuzatildi: `random_int` 6 xona, `Hash::make`/`Hash::check`, qator qulfi ostidagi atomar iste'mol, 5 urinish chegarasi, bir martalik ishlatish, IP+pochta bo'yicha ikki o'lchovli throttle, `throttle:api` qayta yoqildi. Testlar o'tdi. |
| S3 | fixed-unverified | Tasdiqlandi, lekin **auditdagi fayl yo'llari noto'g'ri**: `app/Core/Services/Authority/...` va `app/Core/Services/Author/...` mavjud emas; haqiqiy yo'l `app/Core/Services/Register/`. `RequestRepository::get()` faqat `where('id')` bilan olardi, `RequestController::view()` esa hech qanday egalik tekshirmasdi — istalgan autentifikatsiyalangan foydalanuvchi begona arizaning `passport`, `pin_fl`, `account_number` ma'lumotlarini o'qiy olardi. Register xizmatlari esa begona arizani o'zlashtirib, `author_id` ni o'ziga o'tkazardi. Tuzatildi: `getOwned()` so'rov darajasida cheklaydi; xodim (moderator) uchun istisno saqlandi. |
| S4 | fixed-unverified | Tasdiqlandi: `AdminUserSeeder` da ochiq parol `Qwerty123$`, tasodifiy email, va `Employee::factory()` har ishga tushirishda shartsiz bajarilardi (yetim qatorlar). Tuzatildi: parol faqat `config('auth.admin_initial.password')` dan olinadi, yo'q bo'lsa seeder **to'xtaydi**; mavjud admin hech qachon qayta yozilmaydi; `PermissionSeeder` `DatabaseSeeder` ga qo'shildi (ilgari umuman ishga tushmasdi). |
| S5 | fixed-unverified | Tasdiqlandi: `routes/web.php` dagi barcha 26 admin guruhida faqat `auth` bor; butun `app/` da bitta ham `can(`, `Gate::`, `authorize(`, `permission:`, `role:` yo'q. `spatie/laravel-permission` o'rnatilgan, `PermissionSeeder` mavjud, lekin `DatabaseSeeder` da chaqirilmagan va `User` modelida `HasRoles` yo'q. Admin = `users.employee_id IS NOT NULL`. Tuzatildi: `User` ga `HasRoles`, 21 ta huquq va `admin`/`moderator` rollari (idempotent seeder), har bir admin guruhiga `can:<huquq>`, `Gate::before` faqat `admin` roliga. **Lockout xavfi ataylab hal qilindi** - quyidagi operator qadamiga qarang. |
| S6 | fixed-unverified | Qisman tasdiqlandi. **Auditning bir da'vosi noto'g'ri:** `VerifyCsrfToken::$except` **bo'sh**. Haqiqiy muammo boshqa: 18 ta holat o'zgartiruvchi marshrut `Route::get` sifatida e'lon qilingan (Laravel GET uchun CSRF tekshirmaydi), ulardan 16 tasi qo'shimcha `->withoutMiddleware(VerifyCsrfToken::class)` chaqiradi. Yana bir noto'g'ri da'vo: approve/reject **POST** (web.php:217, 232, 359, 362) — faqat delete/destroy GET. |
| S7 | verified | Tasdiqlandi: `ValidationException` ataylab **500** bilan qaytarilardi va `$e->errors()` tashlab yuborilardi; `config('app.debug')` umuman o'qilmasdi; yagona qalqon `config('app.env') === 'production'` edi, ya'ni `local`/`staging`/`dev` muhitlarida to'liq SQL matni va bindinglar mijozga ketardi; `$statusCode` singleton'da instance-property edi. Tuzatildi. `GET /api/user` endi 500 emas, **401** qaytaradi. |
| S8 | verified | Tasdiqlandi: `config/cors.php` da `'allowed_origins' => ['*, *, *, *, *']` — bu bitta buzuq satr, wildcard ham emas; hech bir origin'ga mos kelmasdi. nginx esa alohida `Access-Control-Allow-Origin: *` + `Allow-Credentials: true` qo'shardi. Tuzatildi: Laravel yagona CORS egasi, originlar `CORS_ALLOWED_ORIGINS` dan; nginx CORS bloklari olib tashlandi. |
| S9 | blocked | **Qayta tasdiqlab bo'lmadi: tarmoq va composer yo'q.** Lock fayllardan offline o'qilgan haqiqiy versiyalar: `laravel/framework v10.48.28`, `tymon/jwt-auth 2.1.1`, `spatie/laravel-permission 6.12.0`, `laravel/sanctum v3.3.3`, `laravel/socialite v5.17.1`, `guzzlehttp/guzzle 7.9.2`, `symfony/http-foundation v6.4.18`, `nunomaduro/larastan v2.9.12` (lock faylida `abandoned` deb belgilangan, `larastan/larastan` ga ko'chirilgan), `laravel/telescope v5.4.0`; `vite 5.4.21`, `axios 1.20.0`. Docker bazaviy image'lari `php:8.3.0-*` — bu 8.3 ning birinchi relizi va u boshqa xavfsizlik yangilanishini olmaydi. |

---

## O'rta bandlar

| ID | Holat | Dalil va natija |
|---|---|---|
| M1 | verified | Tasdiqlandi: `routes/api.php` dagi `file-view` yo'lni birlashtirardi (`storage_path("app/public/{$filename}")`) va avtorizatsiya o'rniga `User-Agent` da `Mozilla` borligini tekshirardi. Tuzatildi: yozuv bo'yicha qidiriladi (traversal imkonsiz), fayl toifasi bog'lanishiga qarab aniqlanadi, pullik kontent uchun huquq talab qilinadi. Testlar o'tdi. |
| M2 | fixed-unverified | Tasdiqlandi: `substr($header, 6)` sxemani tekshirmasdi, `explode(':')` natijasi ikkiga ajratilardi — ikki nuqtasiz sarlavha PHP 8 da xato berardi; solishtirish vaqt bo'yicha xavfsiz emas edi. Tuzatildi: sxema tekshiriladi, qat'iy Base64, birinchi ikki nuqta bo'yicha ajratish, `hash_equals`, va sozlanmagan integratsiya uchun fail-closed. |
| M3 | fixed-unverified | Tasdiqlandi: `.env.example` da ishlatib bo'ladigan `APP_KEY` va `JWT_SECRET=secret` bor edi. Ikkalasi bo'sh qoldirildi va **bir martalik** yaratish tartibi izohda yozildi; keyingi deploylarda kalitlar saqlanishi ta'kidlandi. |
| M4 | fixed-unverified | Tasdiqlandi: `PaginationFilter::wrap()` da `DB::select($totalQuery)` **umuman binding olmasdi**; sanalar 6 joyda xom interpolatsiya qilinardi. **Halollik uchun:** bu hozirda `date_format:Y-m-d` validatsiyasi bilan niqoblangan, ya'ni **latent**, tirik eksploit emas. Baribir binding'ga o'tkazildi. **PostgreSQL'da bajarilmadi.** |
| M5 | fixed-unverified | Tasdiqlandi: production compose yo'q edi — DB va broker `profiles: [dev]` ortida, worker/scheduler yo'q, restart policy va healthcheck yo'q. Tuzatildi; **runtime tekshiruv bajarilmadi**. |
| M6 | fixed-unverified | Tasdiqlandi: nginx'da yashirin fayllar himoyasi, xavfsizlik sarlavhalari, `server_tokens off` yo'q; `gzip on` bor, lekin `gzip_types` yo'q (faqat `text/html` siqiladi). Tuzatildi. HSTS **ataylab qo'shilmadi**: server faqat `listen 80` da va HTTPS topologiyasi tasdiqlanmagan. |
| M7 | verified | Tasdiqlandi: `proverb/list` ikki marta e'lon qilingan — birinchisi `jwt.verify` bilan, ikkinchisi himoyasiz; Laravel'da oxirgisi g'olib, ya'ni himoya jimgina yo'qolgan. `auth/logout` esa `guest` guruhi ichida turgan va bir vaqtda `jwt.verify` ham talab qilingan — bu ikkisi bir-birini istisno qiladi, marshrut amalda ishlamas edi. Ikkalasi tuzatildi va `route:list` bilan tasdiqlandi. |
| M8 | confirmed | **Runtime bilan tasdiqlandi va HAL QILINMADI.** `php artisan migrate` SQLite'da yiqiladi: `SQLSTATE[HY000]: General error: 1 error in index enum_notification_messages_code_name_unique after drop column: no such column: code_name`. Bundan tashqari `app/Core/Filters/**` da 18 ta faylda `DB::raw('created_at::date')` va keng `ilike` ishlatilgan. **Qaror:** ilova PostgreSQL uchun yozilgan va shunday qoldirildi; SQLite qo'llab-quvvatlashi qo'shilmadi. Buning narxi — to'liq integratsiya testlari faqat PostgreSQL muhitida bajarilishi mumkin. |
| M9 | verified | Tasdiqlandi: boshlanishda atigi 2 ta test bor edi va `ExampleTest` yiqilardi (`/` → 302, chunki `auth` login'ga yo'naltiradi). Hozir: **39 test, 111 assertion, hammasi o'tadi**. `phpunit.xml` endi alohida xotiradagi bazadan foydalanadi va ishlab chiqish bazasiga tegmaydi. **PHPStan auditning 5-daraja maqsadiga yetkazildi: 0 xato** (1 -> 3 -> 5). Auth fayllari bo'yicha istisnolar olib tashlandi; faqat ikkita aniq, hujjatlashtirilgan `ignoreErrors` qoldi (kompozit kalit kovariantligi va Laravel `Seeder::$command` docblokidagi noaniqlik - framework kodining o'zi ham `isset()` bilan tekshiradi). |
| M10 | verified | Tasdiqlandi: `Kernel.php` `api` guruhida `StartSession` bor edi (JWT API uchun keraksiz sessiya cookie'si), `throttle:api` esa izohga olingan edi. Sessiyaning yagona iste'molchisi (`LoginAndPasswordService::login()` → `session()->regenerate()`) faqat `web` guruhidagi admin login oqimida ishlatilishi tekshirildi, shundan keyin olib tashlandi. `throttle:api` qayta yoqildi. |

---

## Past bandlar

| ID | Holat | Dalil va natija |
|---|---|---|
| L1 | fixed-unverified | Tasdiqlandi: `canonical` va `og:url` uchinchi tomon saytiga (`preview.keenthemes.com`) ishora qilardi, CSS/JS bir necha marta yuklanardi. **Qo'shimcha, auditda yo'q ikkita jiddiy kamchilik topildi:** (1) `login.blade.php` da `value="{{ old('password') }}"` — yuborilgan parol HTML'ga qaytib chiqardi; (2) `Session::get(...)` qiymatlari JS shablon-literallari ichiga xom `<?= ?>` bilan chiqarilardi (XSS). Ikkalasi ham tuzatildi. |
| L2 | confirmed | Tasdiqlandi va **ataylab tuzatilmadi**: birorta Blade `@vite` ishlatmaydi, CI hech qachon asset build qilmaydi, admin `public/assets/**` dagi tayyor bundle'lardan foydalanadi. Ya'ni butun Vite zanjiri ishlatilmaydi. O'chirish tavsiya qilinadi, lekin bu mahsulot qarori. |
| L3 | confirmed | Tasdiqlandi: `routes/api.php` da `auth:sanctum` ishlatiladi, lekin `config/auth.php` da `sanctum` guardi yo'q; `SmsAuthService::login()` tanasi `// TODO`. Kod o'chirilmadi — iste'molchilari to'liq kuzatilmagan. |
| L4 | fixed-unverified | Log darajasi va JWT xatolari bo'yicha o'zgarishlar exception handler ichida qilindi. Production log saqlash muddati va monitoring **operator qarori** bo'lib qolmoqda. |
| L5 | verified | Tasdiqlandi: README `.env-example` (noto'g'ri nom) ni ko'rsatardi, tarmoq yaratishni va kalit generatsiyasini umuman aytmasdi, `Makefile` da esa `artisan-migrate` compose'da mavjud bo'lmagan `artisan` servisiga murojaat qilardi (ya'ni hech qachon ishlamagan). Ikkalasi ham qayta yozildi. |
| L6 | verified | Tasdiqlandi: `lang/oz/validation+.php` nomi buzuq bo'lgani uchun Laravel guruhni `validation+` deb hal qilardi, `oz` esa standart lokal — ya'ni **oz tilida birorta validatsiya xabari ishlamasdi**. `git mv` bilan `validation.php` ga o'zgartirildi va `__('validation.required')` haqiqatan o'zbekcha matn qaytarishi tekshirildi. |
| L7 | verified | **Audit da'vosi qisman noto'g'ri.** `database.sqlite` repo ildizida emas — u `database/database.sqlite` da va `database/.gitignore` orqali allaqachon to'g'ri ignore qilingan. `composer.phar` esa haqiqatan 3.6 MB va git'da kuzatilardi: `.gitignore` ga `*.phar` qo'shildi va `git rm --cached` qilindi. Fayl diskda qoldi, tarix qayta yozilmadi. |

---

## Auditning ijobiy topilmalari (qayta tekshirildi)

| Nazorat | Holat |
|---|---|
| Click imzo va service-ID tekshiruvi | Mavjud va saqlandi (`ClickService`: `isMatchSignKey`, `service_id` solishtiruvi). |
| Payme summa tekshiruvi | Mavjud va saqlandi. |
| Parol hash'lash va yashirin maydonlar | Mavjud (`User` modelida `'password' => 'hashed'` cast). |
| Yuklamalarda MIME/kengaytma/hajm tekshiruvi | Saqlandi; fayl nomi hali ham `hashName()` orqali generatsiya qilinadi. |
| Order dublikatining oldini olish | `products_orders(product_id, customer_id)` unique cheklovi mavjud; endi u kodda ham ataylab ishlatiladi. |
| JWT blacklist / logout | Kod saqlandi. **Runtime tekshiruv bajarilmadi.** |
| Muallif so'rovlari uchun scope | `RequestSearchFilter` da mavjud edi; faqat `view` da yo'q edi — o'sha tuzatildi. |

---

## Auditda umuman yo'q, lekin topilgan bandlar

| Tavsif | Jiddiylik | Holat |
|---|---|---|
| `routes/web.php` dagi `language/{locale?}` marshruti xom URL segmentini sessiyaga yozadi; u `LanguageHelper` orqali SQL **ustun identifikatoriga** tushadi. Ikkilamchi SQL in'yeksiya, istalgan autentifikatsiyalangan web foydalanuvchi uchun. | Yuqori | Tuzatilmoqda (allow-list ikki qatlamda) |
| `login.blade.php` yuborilgan parolni HTML'ga qaytaradi. | Yuqori | Tuzatildi |
| Blade'da `Session::get()` JS shablon-literaliga xom chiqariladi (XSS). | Yuqori | Tuzatildi |
| `UsersVerifyMailToken::isEnable()` `=== true` bilan solishtiradi; ba'zi drayverlar `1` qaytaradi. | O'rta | Tuzatildi (aniq `boolean` cast) |
| `docker/php/deploy/Dockerfile` `COPY . /test` qiladi, `.dockerignore` esa yo'q — ishchi katalogdagi `.env` (jonli `APP_KEY` bilan) image qatlamiga tushadi. | Yuqori | Tuzatilmoqda |
| `sendTokenToMail` noma'lum har qanday pochta uchun foydalanuvchi yaratadi (cheksiz hisob yaratish). | O'rta | Qisman: throttle bilan yumshatildi, mahsulot oqimi o'zgartirilmadi |
| **`route:cache` ikkinchi sababdan ham yiqilardi:** 7 juft marshrut nomi `routes/api.php` va `routes/web.php` da takrorlangan (`auth.logout`, `company.view`, `company-partner.view`, `company-social-network.view`, `product.view`, `product.delete`, `request.view`). Bu B3 dan mustaqil, alohida deploy blokeri. | Bloker | Tuzatildi: API tomonidagi nomlarga `api.` prefiksi. `route:cache` endi exit 0 |
| **Click imzosi bo'sh sir bilan hisoblanardi.** `CLICK_SECRET_KEY` sozlanmagan bo'lsa (namunada bo'sh), algoritmni bilgan har kim to'g'ri `md5` imzo yasab, soxta callback bilan pullik mahsulotni ochib olardi. `service_id` tekshiruvi ham `intval(null) === 0` sababli chetlab o'tilardi. | Yuqori | Tuzatildi (fail closed + `hash_equals`), testlar bilan qoplandi |
| `EmployeeController::editProfile/editUser` URL'dagi `id` ni tekshirmasdi: istalgan xodim boshqa xodimning profilini va login ma'lumotlarini o'zgartira olardi. | Yuqori | Tuzatildi (egalik tekshiruvi) |
| To'lov callback'lari `sign_string` bilan birga to'liq loglanardi. | Past | Tuzatildi (`[redacted]`) |
| GET→DELETE o'tkazilgandan keyin admin paneldagi 18 ta o'chirish tugmasi amalni bajarsa ham qizil "xato" oynasini ko'rsatardi (JS faqat JSON kutardi, kontrollerlar esa redirect qaytaradi). | O'rta | Tuzatildi (`Delete.js` da bitta joyda) |
| `ProductController::viewRequest()` passport/PINFL qaytaradi, lekin **hech qanday marshrutga bog'lanmagan** - o'lik kod, oshkorlik yo'q. | Ma'lumot | O'zgartirilmadi |
| **Hisobot turi hech qachon tanlanmasdi.** `ReportFilterByBenefit` va `ReportFilterByBooks` da `$formRequest->post('report_type_id') === ReportTypeEnum::X->value` yozilgan edi: chap tomon **satr**, o'ng tomon **int**, ya'ni `"7" === 7` har doim `false`. Natijada saralash doim `asc` bo'lgan, `BOOKS_FREE` va `BOOKS_NOT_BOUGHT` shohobchalari esa **umuman bajarilmagan**. `ReportService::purchaseStatistics()` allaqachon `intval()` ishlatgani niyatni tasdiqlaydi. | Yuqori | Tuzatildi (`$formRequest->integer(...)`) |
| `PurchaseListResource` da `findByClickTransId()` `null` qaytarishi mumkin, `?? 0` esa metod **chaqirilgandan keyin** turardi - to'lov topilmasa fatal xato. | O'rta | Tuzatildi (`?->` obyektning o'ziga) |
| `ReportService::payList()` da `abort()` birinchi argument sifatida **matn** oladi (status kodi o'rniga) - PHP 8 da TypeError. | O'rta | Tuzatildi (401) |
| So'rov qiymatlari (`author_id`, `type_id`, `product_id`, `price_type_id`) `int` parametrlarga tekshiruvsiz uzatilardi: mijoz massiv yuborsa TypeError (500). | Past | Tuzatildi (`integer()`) |

---

## Deploy uchun operator bajarishi shart bo'lgan qadamlar

1. `.env` ni yangi `.env.example` bo'yicha to'ldirish: `CORS_ALLOWED_ORIGINS`
   (kamida ikkita origin), `ADMIN_INITIAL_*`, `MAIL_CODE_*`,
   `FILESYSTEM_UPLOAD_DISK`, va yoqilgan bo'lsa `PAYME_*` / `CLICK_*` / `GOOGLE_*`.
2. `APP_KEY` va `JWT_SECRET` faqat **birinchi** o'rnatishda yaratiladi.
   Mavjud o'rnatishda ularni **o'zgartirmaslik** shart.
3. Yangi migratsiyalarni ishga tushirish (`--force`). Ular ustun qo'shadi va
   `files.path` ni himoyalangan marshrutga qaratadi; hech qanday ma'lumot
   o'chirilmaydi.
4. Eski OTP kodlari bekor qilinadi — foydalanuvchilar yangi kod so'raydi.
5. Mavjud fayllarni `storage/app/private` ga ko'chirish tavsiya etiladi.
   Ko'chirilmasa ham o'qish ishlaydi (eski disklar tekshiriladi), lekin
   `public/storage` symlinki **yaratilmasligi** kerak.
6. **Mavjud adminlar bloklanib qolmasligi uchun BIR MARTA bajarish shart:**

   ```bash
   php artisan db:seed --class="Database\Seeders\RoleSeeder" --force
   ```

   Bu buyruq `employee_id` bor va hech qanday roli yo'q foydalanuvchilarga
   `admin` rolini beradi. Roli borlar tegilmaydi, shuning uchun buyruqni
   qayta ishga tushirish xavfsiz. `RoleSeeder` ataylab `DatabaseSeeder`
   ga qo'shilmagan - bu ko'rinadigan, ongli qadam bo'lishi kerak.

7. Mavjud bazadagi pullik mahsulotlarga tegishli `payment_type = 'free'`
   orderlarni tekshirish: ular eski zaiflik orqali yaratilgan bo'lishi
   mumkin. Yangi kod ularni bloklaydi, lekin ularni ko'rib chiqish kerak.

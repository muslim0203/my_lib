# Marketplace API — mobil ilova uchun qo‘llanma

Laravel 10 backend, JWT autentifikatsiya. Bu hujjat Android/iOS
ilovalarini yozish uchun yetarli kontraktni tasvirlaydi.

> **Muhim:** javob formati yaqinda **bir xillashtirildi**. Ilgari to‘rt xil
> shakl bor edi (ba’zan `success`, ba’zan `status` kaliti). Endi bitta
> shakl — quyida. Eski API bo‘yicha eslatmalaringiz bo‘lsa, ularni
> tashlab yuboring.

---

## 1. Asosiy ma’lumot

| | |
|---|---|
| Bazaviy manzil | `https://<domen>/api` |
| Format | JSON (`Content-Type: application/json`) |
| Til | `Accept-Language: oz` yoki `uz`, `ru` — standart `oz` |
| Autentifikatsiya | `Authorization: Bearer <JWT>` |
| Endpointlar | 68 ta (31 tasi token talab qiladi, 37 tasi ochiq) |

Til `Accept-Language` sarlavhasi orqali tanlanadi va **barcha xabarlar**
(xato matnlari ham) o‘sha tilda qaytadi. Qiymatlar: `oz` (lotin),
`uz` (kirill), `ru`.

---

## 2. Javob formati — hamma joyda bir xil

Har bir javob, muvaffaqiyat ham xato ham, aynan shu beshta kalitdan
iborat. Bitta model qilib parse qilsangiz bo‘ladi.

```json
{
  "success": true,
  "message": "Muvaffaqiyatli",
  "code": 200,
  "data": {},
  "errors": null
}
```

| Kalit | Turi | Izoh |
|---|---|---|
| `success` | bool | Yagona tekshiriladigan bayroq |
| `message` | string | Foydalanuvchiga ko‘rsatish uchun, tanlangan tilda |
| `code` | int | HTTP status kodi, tanada ham takrorlanadi |
| `data` | object, array yoki null | Foydali yuk. Yuk bo‘lmasa `null` |
| `errors` | object yoki null | Faqat validatsiyada: `{"maydon": ["xabar"]}` |

### Haqiqiy misollar

Validatsiya xatosi — `POST /api/auth/send-token-to-mail`, bo‘sh tana:

```json
{
  "success": false,
  "message": "Ma’lumotlar noto‘g‘ri to‘ldirilgan",
  "code": 422,
  "data": null,
  "errors": { "email": ["email maydoni majburiy."] }
}
```

Token yo‘q yoki muddati o‘tgan:

```json
{
  "success": false,
  "message": "Avtorizatsiya tokeni topilmadi",
  "code": 401,
  "data": null,
  "errors": null
}
```

### Kutiladigan HTTP kodlari

| Kod | Qachon | Ilova nima qilishi kerak |
|---|---|---|
| `200` | Muvaffaqiyat | `data` ni o‘qing |
| `401` | Token yo‘q, yaroqsiz yoki muddati o‘tgan | Refresh, bo‘lmasa login ekraniga |
| `403` | Huquq yo‘q, masalan pullik kontent sotib olinmagan | Sotib olish oqimiga yo‘naltiring |
| `404` | Topilmadi | |
| `405` | Noto‘g‘ri metod | Eski chaqiruv — 8-bo‘limga qarang |
| `422` | Validatsiya | `errors` ni maydonlar ostiga chiqaring |
| `429` | Chegaradan oshdi | **Majburiy ishlov bering** — 5-bo‘lim |
| `500` | Server xatosi | Umumiy xabar, tafsilot yuborilmaydi |

---

## 3. Autentifikatsiya — pochta + OTP

Parol yo‘q. Foydalanuvchi pochtasini kiritadi, **6 xonali** kod keladi,
kod bilan JWT olinadi.

> Kod ilgari 4 xonali edi — endi **6 xonali**. Kiritish ekranini moslang.

### 3.1. Kod so‘rash

```
POST /api/auth/send-token-to-mail
Content-Type: application/json

{ "email": "user@example.com" }
```

Pochta ro‘yxatda bo‘lmasa, hisob avtomatik yaratiladi — ro‘yxatdan o‘tish
alohida qadam emas.

### 3.2. Kod bilan kirish

```
POST /api/auth/login-by-email

{ "email": "user@example.com", "code": 123456 }
```

Muvaffaqiyatda:

```json
{
  "success": true,
  "message": "Muvaffaqiyatli kirildi",
  "code": 200,
  "data": { "type": "Bearer", "access_token": "eyJ0eXAiOiJKV1Qi..." },
  "errors": null
}
```

Tokenni saqlang va keyingi so‘rovlarda `Authorization: Bearer <token>`
sifatida yuboring.

### 3.3. Kod qoidalari

| Qoida | Qiymat |
|---|---|
| Uzunlik | 6 xona |
| Amal qilish muddati | 5 daqiqa (`MAIL_CODE_EXPIRE_AT`) |
| Noto‘g‘ri urinishlar | 5 ta, keyin kod bekor qilinadi |
| Qayta ishlatish | Mumkin emas, kod bir martalik |
| Qayta yuborish | Eski kodni bekor qiladi |

**Diqqat:** xato sabablari ataylab ajratilmaydi. Noto‘g‘ri kod, muddati
o‘tgan kod, mavjud bo‘lmagan pochta va urinishlar chegarasi — hammasi
bir xil `401` va bir xil xabar qaytaradi. Bu enumeratsiyaning oldini
oladi. Ilovada “kod muddati o‘tdi” kabi alohida holatlar qurmang:
umumiy xabar va “kodni qayta yuborish” tugmasi yetarli.

### 3.4. Token yangilash va chiqish

```
GET /api/auth/refresh-token
GET /api/auth/logout          (Authorization talab qilinadi)
```

`logout` tokenni blacklist ga qo‘shadi — shundan keyin u ishlamaydi.

### 3.5. Google orqali kirish

```
GET /api/auth/redirect-to-auth-by-google   -> avtorizatsiya havolasi
GET /api/auth/login-by-google              -> callback, JWT qaytaradi
```

Socialite `stateless` rejimida — sessiya cookie kerak emas, mobil ilova
uchun mos.

> `POST /api/auth/login-by-sms` mavjud, lekin **ishlamaydi**: servis
> metodi hali yozilmagan (`// TODO`). Ishlatmang.

---

## 4. So‘rov chegaralari (429)

| Endpoint | Chegara |
|---|---|
| Barcha `/api/*` | 60 so‘rov / daqiqa (foydalanuvchi yoki IP bo‘yicha) |
| `auth/send-token-to-mail` | 5/daqiqa IP, 2/daqiqa pochta, 20/kun pochta |
| `auth/login-by-email` | 10/daqiqa IP, 5/daqiqa pochta |

429 qaytganda `Retry-After` sarlavhasiga qarang va shuncha kuting.
OTP ekranidagi “qayta yuborish” tugmasini taymer bilan bloklang — aks
holda foydalanuvchi tez orada chegaraga uriladi.

---

## 5. Fayllar — muqova, kitob, audio

Fayllar **web root dan tashqarida** saqlanadi va faqat bitta manzil
orqali beriladi:

```
GET /api/file-view/{filename}
```

Havola API javoblarida tayyor keladi (`file` maydoni) — o‘zingiz
yasashingiz shart emas.

| Fayl turi | Token kerakmi |
|---|---|
| Bepul mahsulot muqovasi, oldindan ko‘rish | Yo‘q |
| **Pullik mahsulotning manba fayli va audiosi** | **Ha**, va sotib olingan bo‘lishi shart |
| Shaxsiy hujjatlar: diplom, litsenziya, sertifikat, patent | Faqat moderator |

Pullik faylni tokensiz yoki sotib olmasdan so‘rasangiz — `403`.
Rasm yuklovchi kutubxonaga (Glide, Coil, Kingfisher) `Authorization`
sarlavhasini uzatishni unutmang, aks holda pullik kontent yuklanmaydi.

Audio uchun `Range` so‘rovlari qo‘llab-quvvatlanadi — oldinga va orqaga
siljitish ishlaydi.

---

## 6. To‘lovlar

Bu yerda ilgari chalkashlik ko‘p bo‘lgan, shuning uchun aniq ajratamiz.

### Ilova chaqiradigan endpointlar

| Endpoint | Nima qiladi |
|---|---|
| `POST /api/click/get-redirect-url` | Click to‘lov sahifasiga havola |
| `POST /api/click/generate-qr-code` | Click QR kodi |
| `POST /api/payme/get-redirect-url` | Payme to‘lov sahifasiga havola |
| `GET /api/payme/generate-qr/{id}` | Payme QR kodi |

Hammasi `{ "product_id": <int> }` qabul qiladi va **JWT talab qiladi**.

Oqim: havolani oling, tashqi brauzer yoki WebView da oching,
foydalanuvchi to‘laydi, **to‘lov tizimi serverimizni o‘zi xabardor
qiladi**, ilova mahsulot holatini qayta so‘raydi.

### Ilova CHAQIRMAYDIGAN endpointlar

`POST /api/click/prepare-payment`, `POST /api/click/complete-payment`,
`POST /api/payme/pay`, `POST /api/payme/check` — bularni **to‘lov
tizimining serverlari** chaqiradi. Ular token talab qilmaydi, lekin
provayder autentifikatsiyasi bilan himoyalangan: Click uchun
`sign_string` (MD5 imzo), Payme uchun Basic auth. Ilovadan ularga
murojaat qilmang.

### To‘lovdan keyin

To‘lov tasdiqlangach server order yaratadi. Ilova mahsulotni qayta
so‘raganda `is_allow: true` bo‘ladi va `source_file` hamda `audio_files`
to‘ldiriladi.

**Muhim:** order mavjudligining o‘zi yetarli emas — pullik mahsulot uchun
to‘lov tizimi tasdiqlagan bo‘lishi shart. Ilgari shu tekshiruv yo‘qligi
sababli pullik kitoblarni bepul olish mumkin edi.

### Bepul mahsulot

```
POST /api/product/buy/{id}        (JWT)
```

Faqat haqiqatan bepul mahsulot uchun. Pullik mahsulotga `403`.
**Bu ilgari `GET` edi** — eski chaqiruv endi `405` beradi.

---

## 7. Mahsulot obyektidagi muhim maydonlar

`GET /api/product/view/{id}` javobidagi `data` ichida. Bu endpoint
**token talab qilmaydi**, lekin token yuborilsa javob boyiydi:
`is_allow` va pullik fayllar faqat token bo‘lganda to‘g‘ri to‘ldiriladi.
Shuning uchun foydalanuvchi tizimga kirgan bo‘lsa, **doim token bilan
so‘rang**.

| Maydon | Izoh |
|---|---|
| `is_free` | Mahsulot bepulmi |
| `price_value`, `discount_price` | Narx va chegirmali narx |
| `is_allow` | Joriy foydalanuvchida kontentga huquq bormi |
| `wrapper_file` | Muqova — har doim ochiq |
| `source_file` | Kitob fayli. Pullik bo‘lsa, huquq yo‘q holatda `null` |
| `audio_files` | Audio ro‘yxati. Huquq yo‘q bo‘lsa `null` |
| `assessment`, `assessment_level` | O‘rtacha baho va foydalanuvchi bahosi |

Ilovada kontent tugmalarini `is_allow` bo‘yicha boshqaring, `source_file`
mavjudligiga tayanmang.

---

## 8. Eski API dan farqlar

Eski versiya bo‘yicha eslatmalaringiz bo‘lsa:

| Nima | Ilgari | Endi |
|---|---|---|
| Javob konverti | 4 xil shakl | Bitta: `{success, message, code, data, errors}` |
| Validatsiya xatosi | `500`, `data` massiv | `422`, `errors` obyekt |
| Auth xatosi | `500` | `401` yoki `403` |
| OTP kodi | 4 xona | **6 xona** |
| OTP xatolari | Har biri alohida xabar | Bitta umumiy `401` |
| `product/buy/{id}` | `GET` | **`POST`** |
| Fayl havolalari | `/storage/...` | `/api/file-view/...` |
| Pullik fayllar | Havolani bilgan har kim ochardi | Token va sotib olish shart |
| So‘rov chegarasi | Yo‘q | Bor — `429` ga tayyor bo‘ling |
| `proverb/list` | Ochiq | **JWT talab qiladi** |

---

## 9. Hali tayyor emas

Halollik uchun: quyidagilar backend tomonda **PostgreSQL muhitida
tekshirilmagan**, chunki ishlab chiqish mashinasida baza to‘liq emas edi.

- Mahsulot, xarid, to‘lov va fayl oqimlari uchidan uchiga sinalmagan.
- Hisobot endpointlari (`/api/report/*`) yangi tuzatishdan keyin haqiqiy
  bazada bir marta tekshirilishi kerak.
- `auth/login-by-sms` umuman yozilmagan.

Integratsiya paytida shu joylarda kutilmagan xatti-harakat uchrasa,
backend tomonga xabar bering.

---

## 10. Endpointlar ro‘yxati

“Token” ustuni `JWT` bo‘lsa, `Authorization: Bearer` majburiy.
“So‘rov maydonlari” validatsiya qoidalaridan avtomatik olingan;
to‘liq qoidalar `app/Http/Requests/` da.

### Autentifikatsiya

| Metod | Manzil | Token | So’rov maydonlari |
|---|---|:--:|---|
| `POST` | `/api/auth/login-by-email` | — | `email`, `code`<br><sub>throttle otp-verify</sub> |
| `GET` | `/api/auth/login-by-google` | — | — |
| `POST` | `/api/auth/login-by-sms` | — | — |
| `GET` | `/api/auth/logout` | JWT | — |
| `GET` | `/api/auth/redirect-to-auth-by-google` | — | — |
| `GET` | `/api/auth/refresh-token` | — | — |
| `POST` | `/api/auth/send-token-to-mail` | — | `email`<br><sub>throttle otp-send</sub> |

### Foydalanuvchi

| Metod | Manzil | Token | So’rov maydonlari |
|---|---|:--:|---|
| `GET` | `/api/user` | — | —<br><sub>Authenticate:sanctum</sub> |
| `GET` | `/api/user/detail-list` | JWT | — |
| `PUT` | `/api/user/edit` | JWT | `first_name`, `last_name`, `middle_name`, `current_address`, `description` …(+3) |
| `GET` | `/api/user/interest-list` | JWT | — |
| `POST` | `/api/user/select-interest` | JWT | `types`, `genres` |

### Mahsulotlar

| Metod | Manzil | Token | So’rov maydonlari |
|---|---|:--:|---|
| `GET` | `/api/product/assessment-list/{id}` | — | — |
| `POST` | `/api/product/assessment/{id}` | JWT | `level`, `comment` |
| `POST` | `/api/product/author-product-list` | — | `author_id`, `type_id` |
| `POST` | `/api/product/buy/{id}` | JWT | — |
| `GET` | `/api/product/comment-list/{id}` | — | — |
| `POST` | `/api/product/comment/{id}` | JWT | `comment`, `parent_id` |
| `DELETE` | `/api/product/delete/{id}` | JWT | — |
| `POST` | `/api/product/favorite-list` | — | `ids`, `ids.*` |
| `GET` | `/api/product/list` | — | `category`, `category_id`, `title` |
| `GET` | `/api/product/my-list` | JWT | — |
| `GET` | `/api/product/personal-list` | JWT | — |
| `GET` | `/api/product/personal-view/{id}` | JWT | — |
| `GET` | `/api/product/set-view-count/{id}` | — | — |
| `GET` | `/api/product/similar-product/{id}` | — | — |
| `GET` | `/api/product/view/{id}` | — | — |

### Fayllar

| Metod | Manzil | Token | So’rov maydonlari |
|---|---|:--:|---|
| `GET` | `/api/file-view/{filename}` | — | — |
| `POST` | `/api/file/upload` | JWT | `file` |

### To’lov: Click

| Metod | Manzil | Token | So’rov maydonlari |
|---|---|:--:|---|
| `POST` | `/api/click/complete-payment` | — | `click_trans_id`, `service_id`, `click_paydoc_id`, `merchant_trans_id`, `merchant_prepare_id` …(+6) |
| `POST` | `/api/click/generate-qr-code` | JWT | `product_id` |
| `POST` | `/api/click/get-redirect-url` | JWT | `product_id` |
| `POST` | `/api/click/prepare-payment` | — | `click_trans_id`, `service_id`, `click_paydoc_id`, `merchant_trans_id`, `amount` …(+5) |

### To’lov: Payme

| Metod | Manzil | Token | So’rov maydonlari |
|---|---|:--:|---|
| `POST` | `/api/payme/check` | — | — |
| `GET` | `/api/payme/generate-qr/{id}` | JWT | — |
| `POST` | `/api/payme/get-redirect-url` | JWT | `product_id` |
| `POST` | `/api/payme/pay` | — | `_`<br><sub>PaymeAuthenticationMiddleware</sub> |

### Arizalar

| Metod | Manzil | Token | So’rov maydonlari |
|---|---|:--:|---|
| `POST` | `/api/request/create-author/{id?}` | JWT | `first_name`, `last_name`, `middle_name`, `birthdate`, `pin_fl` …(+22) |
| `POST` | `/api/request/create-authority/{id?}` | JWT | `name_oz`, `name_uz`, `name_ru`, `inn`, `account_number` …(+14) |
| `POST` | `/api/request/create-product/{id?}` | JWT | `_` |
| `GET` | `/api/request/list` | JWT | `status`, `request_type_id` |
| `GET` | `/api/request/status` | JWT | — |
| `GET` | `/api/request/view/{id}` | JWT | — |

### Mualliflar va tashkilotlar

| Metod | Manzil | Token | So’rov maydonlari |
|---|---|:--:|---|
| `POST` | `/api/author/comment-list/{id}` | — | — |
| `POST` | `/api/author/comment/{id}` | JWT | `comment`, `parent_id` |
| `POST` | `/api/author/show-profile` | — | `author_id` |
| `PUT` | `/api/authority/edit/{id}` | JWT | `name_oz`, `name_uz`, `name_ru`, `inn`, `account_number` …(+14) |
| `POST` | `/api/link-author-subscriber/subscribe` | — | `author_id`, `subscribe` |
| `POST` | `/api/link-author-subscriber/subscriber-count` | — | — |

### Hisobotlar

| Metod | Manzil | Token | So’rov maydonlari |
|---|---|:--:|---|
| `GET` | `/api/report/pay-list` | JWT | — |
| `POST` | `/api/report/purchase-statistics` | JWT | `report_type_id`, `from_date`, `to_date` |
| `GET` | `/api/report/type-list` | JWT | — |

### Bildirishnomalar

| Metod | Manzil | Token | So’rov maydonlari |
|---|---|:--:|---|
| `GET` | `/api/notification/list` | JWT | `enabled` |

### Kompaniya

| Metod | Manzil | Token | So’rov maydonlari |
|---|---|:--:|---|
| `GET` | `/api/company-partner/view` | — | — |
| `GET` | `/api/company-social-network/view` | — | — |
| `GET` | `/api/company/view` | — | — |

### Ma’lumotnomalar (enum)

| Metod | Manzil | Token | So’rov maydonlari |
|---|---|:--:|---|
| `GET` | `/api/academic-degree/list` | — | — |
| `GET` | `/api/academic-position/list` | — | — |
| `GET` | `/api/activity-type/list` | JWT | — |
| `GET` | `/api/education-type/list` | — | — |
| `GET` | `/api/enum-categories/list` | — | — |
| `GET` | `/api/product-genre/list` | — | — |
| `GET` | `/api/product-price-type/list` | — | — |
| `GET` | `/api/product-tag/list` | — | — |
| `GET` | `/api/product-type/list` | — | — |

### Boshqa

| Metod | Manzil | Token | So’rov maydonlari |
|---|---|:--:|---|
| `GET` | `/api/main-banner/list` | — | — |
| `GET` | `/api/proverb/list` | JWT | — |
| `GET` | `/api/question/list` | — | — |

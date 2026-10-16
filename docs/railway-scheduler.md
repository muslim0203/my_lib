# Railway: scheduler servisi

Laravel scheduler (`App\Console\Kernel::schedule`) o'z-o'zidan ishlamaydi:
uni doimiy ishlab turadigan alohida jarayon chaqirishi kerak. Docker'da bu
`docker-compose.prod.yml` dagi `scheduler` konteyneri, Railway'da esa
`mylib` loyihasidagi `scheduler` servisi.

Hozirgi vazifalar:

| Vazifa | Vaqt (UTC) | Nima uchun |
|---|---|---|
| `model:prune --model=AppErrorLog` | har kuni 03:30 | Maxfiylik siyosati 5.3-band: IP va user agent 12 oydan ortiq saqlanmaydi |

## Railway'dagi sozlama

`scheduler` servisi (production muhiti):

- **Manba**: `muslim0203/my_lib`, `master` branch (backend bilan bir xil kod).
- **Builder**: Railpack.
- **Start command**: `php artisan schedule:work --no-interaction`.
- **Restart policy**: ON_FAILURE, 10 marta.
- **Replikalar**: 1 ta (`us-west2`). Scheduler faqat bitta nusxada ishlashi
  kerak, aks holda vazifalar ikki marta bajariladi.
- **Domen**: yo'q, servis tashqaridan so'rov qabul qilmaydi.
- **Variables**: barchasi backend'ga havola, masalan
  `APP_KEY=${{backend.APP_KEY}}`. Backend'da qiymat o'zgarsa, bu yerda ham
  o'zgaradi. Backend'ga yangi o'zgaruvchi qo'shilsa va u scheduler
  vazifalariga kerak bo'lsa, uni shu yerga ham havola sifatida qo'shing.

Railway config-as-code (`railway.json`) endi qabul qilinmaydi, shuning uchun
sozlama repozitoriyda emas, servisning o'zida saqlanadi.

## Tekshirish

Deploy loglarida har daqiqada `No scheduled commands are ready to run.`
(yoki vazifa nomi) chiqishi kerak. Ro'yxatni ko'rish uchun Railway CLI:

```bash
railway link            # mylib loyihasi, scheduler servisi
railway run php artisan schedule:list
```

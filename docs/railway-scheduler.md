# Railway: scheduler servisi

Laravel scheduler (`App\Console\Kernel::schedule`) o'z-o'zidan ishlamaydi:
uni doimiy ishlab turadigan alohida jarayon chaqirishi kerak. Docker'da bu
`docker-compose.prod.yml` dagi `scheduler` konteyneri, Railway'da esa
alohida servis.

Hozirgi vazifalar:

| Vazifa | Vaqt (UTC) | Nima uchun |
|---|---|---|
| `model:prune --model=AppErrorLog` | har kuni 03:30 | Maxfiylik siyosati 5.3-band: IP va user agent 12 oydan ortiq saqlanmaydi |

## Bir martalik sozlash (Railway paneli)

1. Loyihada **+ New → GitHub Repo** → `muslim0203/my_lib` ni tanlang
   (backend servisi bilan bir xil repo va branch).
2. Yangi servis nomini `scheduler` qiling.
3. **Settings → Config-as-code** da fayl yo'lini kiriting:
   `/railway.scheduler.json`. U start buyrug'ini
   `php artisan schedule:work` ga almashtiradi. Asosiy backend servisiga
   bu fayl ta'sir qilmaydi.
4. **Variables**: backend servisidagi barcha o'zgaruvchilarni bering
   (`APP_KEY`, `DB_*`, `JWT_SECRET` va boshqalar). Eng qulayi - Shared
   Variables yoki `${{backend.APP_KEY}}` ko'rinishidagi havolalar, shunda
   qiymatlar ikki joyda alohida saqlanmaydi.
5. **Networking**: domen bermang, servis tashqaridan so'rov qabul qilmaydi.
6. Deploy qiling. Loglarda har daqiqada `No scheduled commands are ready
   to run.` yoki vazifa nomi chiqishi kerak.

Muhim: scheduler'ni **faqat bitta** servisda/replikada ishga tushiring,
aks holda vazifalar ikki marta bajariladi.

## Tekshirish

Railway CLI orqali (`railway link`, keyin `scheduler` servisini tanlab):

```bash
railway run php artisan schedule:list
```

Ro'yxatda `model:prune` ko'rinishi kerak.

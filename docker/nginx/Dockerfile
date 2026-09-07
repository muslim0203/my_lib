# Base image: "nginx:1.25.4-alpine" -> "nginx:1.27-alpine" (qo'llab-quvvatlanadigan
# suzuvchi patch tegi). Teg mavjudligini bu mashinadan TEKSHIRA OLMADIM.
FROM nginx:1.27-alpine

# M6 ESLATMA: ilgari faqat default.conf image ichiga ko'chirilardi; nginx.conf
# konteynerga FAQAT compose'dagi bind-mount orqali yetib borardi. Ya'ni image'ni
# compose'siz ishga tushirsangiz server_tokens/gzip_types sozlamalari yo'qolardi.
# Endi ikkalasi ham image ichiga ko'chiriladi; compose bind-mount esa lokal
# tahrirlash uchun ustidan yozadi (ixtiyoriy).
COPY ./config/nginx.conf   /etc/nginx/nginx.conf
COPY ./config/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/app

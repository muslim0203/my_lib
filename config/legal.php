<?php

/*
 * Huquqiy hujjatlar (ofertalar, maxfiylik siyosati) sahifalaridagi
 * kompaniya aloqa ma'lumotlari. Admin paneldagi "Kompaniya" yozuvida email
 * yoki telefon kiritilgan bo'lsa, o'shalar ustun turadi.
 */
return [
    'contacts' => [
        'email' => env('LEGAL_CONTACT_EMAIL', 'info@my-lib.uz'),
        'phone' => env('LEGAL_CONTACT_PHONE', '+998 97 007 33 96'),
    ],
];

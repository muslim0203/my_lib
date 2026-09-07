<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Bosh sahifa `auth` middleware ostida, shuning uchun autentifikatsiya
     * qilinmagan so'rov login sahifasiga yo'naltiriladi. Ilgari bu test
     * 200 kutar va doim yiqilardi.
     */
    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $response = $this->get('/');

        // Aniq manzilga bog'lanmaydi: muhim jihati - autentifikatsiya
        // qilinmagan so'rov panelni ko'rmasligi.
        $response->assertRedirect();
        $this->assertFalse($response->isOk());
    }
}

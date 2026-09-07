<?php

namespace Tests\Feature;

use App\Core\Enums\LanguageEnum;
use App\Core\Enums\Users\UserStatusEnum;
use App\Http\Middleware\LocalizationWeb;
use App\Models\Users\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * S2b (3): ikkinchi darajali SQL injection.
 *
 * `GET language/{locale}` URL segmentini hech qanday tekshiruvsiz
 * sessiyaga yozardi; App\Http\Middleware\LocalizationWeb esa uni har bir
 * so'rovda `App::setLocale()` ga uzatardi. Keyin
 * App\Core\Helpers\Lang\LanguageHelper::getTitle() shu qiymatdan
 * `'title_' . $locale` ustun nomini yasab, uni App\Core\Filters\Reports\*
 * va App\Core\Search\ProductOrderSearch ichida XOM SQL ga qo'shardi.
 *
 * Ya'ni autentifikatsiyadan o'tgan har qanday foydalanuvchi o'z sessiyasini
 * SQL payload bilan "zaharlab" qo'yishi mumkin edi.
 */
class LocaleValidationTest extends TestCase
{
    private const PAYLOAD = "oz\" FROM products WHERE 1=1 UNION SELECT password FROM users --";

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('login_type')->nullable();
            $table->unsignedBigInteger('social_user_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->integer('status')->default(UserStatusEnum::_ACTIVE->value);
            $table->timestamps();
        });

        // `web` guruhi orqali o'tadigan zond: LocalizationWeb aynan shu
        // guruhda ro'yxatdan o'tgan (App\Http\Kernel).
        Route::middleware('web')->get('__test/locale-probe', function () {
            return response()->json([
                'locale' => app()->getLocale(),
                'session' => session('locale'),
            ]);
        });
    }

    private function user(): User
    {
        $user = new User();
        $user->fill([
            'username' => 'locale-tester',
            'password' => 'secret-password-123',
            'email' => 'locale@example.test',
            'employee_id' => 42,
            'status' => UserStatusEnum::_ACTIVE->value,
        ]);
        $user->save();

        return $user;
    }

    /**
     * SQL payload sessiyaga umuman tushmasligi kerak.
     */
    public function test_sql_payload_never_reaches_the_session(): void
    {
        $response = $this->actingAs($this->user())
            ->get('/language/' . rawurlencode(self::PAYLOAD));

        // Marshrut namunasi (`[A-Za-z]{2}`) uni allaqachon rad etadi.
        $response->assertNotFound();

        $this->assertNull(session('locale'));
        $this->assertNotSame(self::PAYLOAD, app()->getLocale());
    }

    /**
     * Namunaga mos, lekin LanguageEnum da yo'q kod ham rad etiladi.
     */
    public function test_unknown_two_letter_locale_is_ignored(): void
    {
        $response = $this->actingAs($this->user())->get('/language/xx');

        $response->assertRedirect();

        $this->assertNull(session('locale'));
        $this->assertNotSame('xx', app()->getLocale());
    }

    /**
     * Ro'yxatdagi til kodi ilgarigidek ishlashi kerak (regressiya).
     */
    public function test_allowed_locale_is_still_accepted(): void
    {
        $response = $this->actingAs($this->user())
            ->get('/language/' . LanguageEnum::_RU->value);

        $response->assertRedirect();
        $this->assertSame(LanguageEnum::_RU->value, session('locale'));

        $probe = $this->actingAs($this->user())->getJson('/__test/locale-probe');
        $probe->assertJsonPath('locale', LanguageEnum::_RU->value);
    }

    /**
     * Sessiya boshqa yo'l bilan zaharlangan bo'lsa ham (masalan, eski,
     * tuzatishdan oldingi sessiya cookie'si), LocalizationWeb uni
     * qo'llamasligi va tozalab tashlashi kerak.
     */
    public function test_poisoned_session_is_ignored_and_cleared(): void
    {
        $probe = $this->actingAs($this->user())
            ->withSession(['locale' => self::PAYLOAD])
            ->getJson('/__test/locale-probe');

        $probe->assertOk();
        $probe->assertJsonPath('session', null);
        $this->assertNotSame(self::PAYLOAD, $probe->json('locale'));
        $this->assertContains($probe->json('locale'), LocalizationWeb::allowed());
    }

    /**
     * Oq ro'yxat aynan App\Core\Enums\LanguageEnum dan olinadi.
     */
    public function test_allow_list_matches_the_language_enum(): void
    {
        $this->assertSame(['oz', 'uz', 'ru'], LocalizationWeb::allowed());

        $this->assertNull(LocalizationWeb::sanitize('en'));
        $this->assertNull(LocalizationWeb::sanitize(self::PAYLOAD));
        $this->assertNull(LocalizationWeb::sanitize(null));
        $this->assertNull(LocalizationWeb::sanitize(['oz']));
        $this->assertSame('uz', LocalizationWeb::sanitize('uz'));
        $this->assertContains(LocalizationWeb::defaultLocale(), LocalizationWeb::allowed());
    }
}

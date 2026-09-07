<?php

namespace Tests\Feature;

use App\Core\Repository\User\UsersVerifyTokenMailRepository;
use App\Core\Services\Mail\interfaces\MailInterface;
use App\Models\Users\UsersVerifyMailToken;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * OTP kodi uchun regressiya testlari.
 *
 * Auditdagi S2: to'rt xonali `rand()` kod, ochiq matnda saqlash,
 * bo'sh solishtirish (`==`), urinishlar chegarasi va bir martalik
 * iste'molning yo'qligi.
 */
class OtpTest extends TestCase
{
    protected const MAX_ATTEMPTS = 5;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users_verify_mail_tokens', function (Blueprint $table) {
            $table->integer('user_id')->unique();
            $table->string('token', 25);
            $table->string('token_hash', 255)->nullable();
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->timestamp('used_at')->nullable();
            $table->timestamp('expire_at');
            $table->boolean('enabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * @param string $code
     * @param int $minutes Musbat - kelajak, manfiy - muddati o'tgan
     * @return UsersVerifyMailToken
     */
    protected function issue(string $code, int $minutes = 5): UsersVerifyMailToken
    {
        // VerifyMailListener kabi: foydalanuvchi uchun bitta qator
        // bo'ladi va qayta yuborish uni ustiga yozadi.
        $token = UsersVerifyMailToken::query()->where('user_id', 1)->first()
            ?? new UsersVerifyMailToken();
        $token->setUserId(1);
        $token->setToken('');
        $token->setTokenHash(Hash::make($code));
        $token->setAttempts(0);
        $token->setUsedAt(null);
        $token->setExpireAt(now()->addMinutes($minutes)->format('Y-m-d H:i:s'));
        $token->setEnabled(true);
        $token->save();

        return $token;
    }

    protected function repository(): UsersVerifyTokenMailRepository
    {
        return app(UsersVerifyTokenMailRepository::class);
    }

    public function test_generated_code_is_six_digits_and_never_stored_in_plain_text(): void
    {
        /**
         * @var MailInterface $mailService
         */
        $mailService = app(MailInterface::class);

        $code = (string)$mailService->getCode();

        $this->assertMatchesRegularExpression('/^[1-9][0-9]{5}$/', $code);

        $hash = $mailService->getHashCode();

        $this->assertNotSame($code, $hash, 'Hash ochiq matnli kodga teng bo\'lmasligi kerak');
        $this->assertTrue(Hash::check($code, $hash));

        // Hash bir marta hisoblanib eslab qolinishi kerak, aks holda
        // yuborilgan kod bilan saqlangan hash mos kelmasdi.
        $this->assertSame($hash, $mailService->getHashCode());
    }

    public function test_correct_code_is_accepted_once_and_cannot_be_reused(): void
    {
        $this->issue('123456');

        $this->assertTrue(
            $this->repository()->consume(1, '123456', self::MAX_ATTEMPTS)
        );

        $this->assertFalse(
            $this->repository()->consume(1, '123456', self::MAX_ATTEMPTS),
            'Bir marta ishlatilgan kod qayta qabul qilinmasligi kerak'
        );

        $token = UsersVerifyMailToken::query()->where('user_id', 1)->first();
        $this->assertNotNull($token->getUsedAt());
        $this->assertFalse($token->isEnable());
    }

    public function test_wrong_code_is_rejected_and_counted(): void
    {
        $this->issue('123456');

        $this->assertFalse($this->repository()->consume(1, '000000', self::MAX_ATTEMPTS));

        $token = UsersVerifyMailToken::query()->where('user_id', 1)->first();
        $this->assertSame(1, $token->getAttempts());
        $this->assertTrue($token->isEnable(), 'Bitta xato urinish kodni bekor qilmasligi kerak');
    }

    public function test_sixth_wrong_attempt_is_blocked_and_disables_the_code(): void
    {
        $this->issue('123456');

        for ($i = 1; $i <= self::MAX_ATTEMPTS; $i++) {
            $this->assertFalse(
                $this->repository()->consume(1, '000000', self::MAX_ATTEMPTS),
                "Urinish {$i} rad etilishi kerak"
            );
        }

        $token = UsersVerifyMailToken::query()->where('user_id', 1)->first();
        $this->assertSame(self::MAX_ATTEMPTS, $token->getAttempts());
        $this->assertFalse($token->isEnable(), 'Chegaraga yetganda kod bekor qilinishi kerak');

        // Oltinchi urinish - endi hatto TO'G'RI kod ham qabul qilinmaydi.
        $this->assertFalse(
            $this->repository()->consume(1, '123456', self::MAX_ATTEMPTS),
            'Chegaradan keyin to\'g\'ri kod ham qabul qilinmasligi kerak'
        );
    }

    public function test_expired_code_is_rejected(): void
    {
        $this->issue('123456', -1);

        $this->assertFalse($this->repository()->consume(1, '123456', self::MAX_ATTEMPTS));
    }

    public function test_disabled_code_is_rejected(): void
    {
        $token = $this->issue('123456');
        $token->setEnabled(false);
        $token->save();

        $this->assertFalse($this->repository()->consume(1, '123456', self::MAX_ATTEMPTS));
    }

    /**
     * Kodni qayta yuborish eskisini bekor qiladi va urinishlar
     * hisobini nolga qaytaradi.
     */
    public function test_resending_supersedes_the_previous_code(): void
    {
        $this->issue('111111');

        $this->repository()->consume(1, '000000', self::MAX_ATTEMPTS);

        $this->issue('222222');

        $this->assertFalse(
            $this->repository()->consume(1, '111111', self::MAX_ATTEMPTS),
            'Eski kod yangi kod chiqarilgandan keyin ishlamasligi kerak'
        );

        $this->assertTrue(
            $this->repository()->consume(1, '222222', self::MAX_ATTEMPTS)
        );
    }

    public function test_code_for_another_user_is_rejected(): void
    {
        $this->issue('123456');

        $this->assertFalse($this->repository()->consume(2, '123456', self::MAX_ATTEMPTS));
    }

    /**
     * Hech qanday kod chiqarilmagan foydalanuvchi uchun ham xato emas,
     * oddiy rad javobi qaytadi.
     */
    public function test_missing_token_row_is_rejected_without_error(): void
    {
        $this->assertFalse($this->repository()->consume(99, '123456', self::MAX_ATTEMPTS));
    }
}

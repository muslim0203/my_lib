<?php

namespace App\Core\Services\Pay\Helpers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Log;

class ClickResult implements Arrayable
{
    const CODE_SIGN_IN_FAILED = -1;
    const CODE_INCORRECT_AMOUNT = -2;
    const CODE_ACTION_NOT_FOUND = -3;
    const CODE_ALREADY_PAID = -4;
    const CODE_USER_DOES_NOT_EXIST = -5;
    const CODE_TRANSACTION_DOES_NOT_EXIST = -6;
    const CODE_FAILED_TO_UPDATE_USER = -7;
    const CODE_ERROR_FROM_CLICK = -8;
    const CODE_TRANSACTION_CANCELLED = -9;

    /**
     * @param int $error
     * @param string|null $errorNote
     * @param int|null $clickTransId
     * @param int|null $merchantTransId
     * @param int|null $merchantPrepareId
     * @param int|null $merchantConfirmId
     */
    public function __construct(
        public readonly int     $error,
        public readonly ?string $errorNote = null,
        public readonly ?int    $clickTransId = null,
        public readonly ?int    $merchantTransId = null,
        public readonly ?int    $merchantPrepareId = null,
        public readonly ?int    $merchantConfirmId = null,
    )
    {
    }

    public static function signInFailed(): self
    {
        Log::error('Click. CODE_SIGN_IN_FAILED!');
        return new self(error: self::CODE_SIGN_IN_FAILED);
    }

    public static function userNotFound(): self
    {
        Log::error('Click. User is not found!');
        return new self(error: self::CODE_USER_DOES_NOT_EXIST);
    }

    public static function actionNotFound(): self
    {
        Log::error('Click. CODE_ACTION_NOT_FOUND!');
        return new self(error: self::CODE_ACTION_NOT_FOUND);
    }

    public static function incorrectAmount(): self
    {
        Log::error('Click. CODE_INCORRECT_AMOUNT!');
        return new self(error: self::CODE_INCORRECT_AMOUNT);
    }

    public static function alreadyPaid(): self
    {
        Log::error('Click. CODE_ALREADY_PAID!');
        return new self(error: self::CODE_ALREADY_PAID);
    }

    public static function paymentNotFound(): self
    {
        Log::error('Click. CODE_TRANSACTION_DOES_NOT_EXIST!');
        return new self(error: self::CODE_TRANSACTION_DOES_NOT_EXIST);
    }

    public static function transactionCancelled(): self
    {
        Log::error('Click. CODE_TRANSACTION_CANCELLED!');
        return new self(error: self::CODE_TRANSACTION_CANCELLED);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'error' => $this->error,
            'error_note' => $this->errorNote,
            'click_trans_id' => $this->clickTransId,
            'merchant_trans_id' => $this->merchantTransId,
            'merchant_prepare_id' => $this->merchantPrepareId,
            'merchant_confirm_id' => $this->merchantConfirmId
        ];
    }
}

<?php

namespace App\Core\Enums\Pay;

use App\Rules\Pay\Payme\CancelTransactionRule;
use App\Rules\Pay\Payme\CheckPerformTransactionRule;
use App\Rules\Pay\Payme\CheckTransactionRule;
use App\Rules\Pay\Payme\CreateTransactionRule;
use App\Rules\Pay\Payme\GetStatementRule;
use App\Rules\Pay\Payme\PerformTransactionRule;

enum PaymeMethodsEnum: string
{
    case CHECK_PERFORM_TRANSACTION = 'CheckPerformTransaction';
    case CREATE_TRANSACTION = 'CreateTransaction';
    case PERFORM_TRANSACTION = 'PerformTransaction';
    case CANCEL_TRANSACTION = 'CancelTransaction';
    case CHECK_TRANSACTION = 'CheckTransaction';
    case GET_STATEMENT = 'GetStatement';
    case SET_FISCAL_DATA = 'SetFiscalData';

    /**
     * @return array
     */
    public static function list(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array
     */
    public static function getRules(): array
    {
        return [
            self::CHECK_PERFORM_TRANSACTION->value => new CheckPerformTransactionRule(),
            self::CREATE_TRANSACTION->value => new CreateTransactionRule(),
            self::PERFORM_TRANSACTION->value => new PerformTransactionRule(),
            self::CHECK_TRANSACTION->value => new CheckTransactionRule(),
            self::CANCEL_TRANSACTION->value => new CancelTransactionRule(),
            self::GET_STATEMENT->value => new GetStatementRule(),
        ];
    }
}

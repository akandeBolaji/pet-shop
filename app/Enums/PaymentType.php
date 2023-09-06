<?php

// app/Enums/PaymentType.php

namespace App\Enums;

class PaymentType
{
    const CREDIT_CARD = 'credit_card';
    const CASH_ON_DELIVERY = 'cash_on_delivery';
    const BANK_TRANSFER = 'bank_transfer';

    public static function values(): array
    {
        return [
            self::CREDIT_CARD,
            self::CASH_ON_DELIVERY,
            self::BANK_TRANSFER,
        ];
    }
}

<?php

namespace App\Enum;

abstract class TransactionTypes{
    const PAGAMENTO_DE_CONTA = 1;
    const DEPOSITO= 2;
    const TRANSFERENCIA = 3;
    const RECARDA_DE_CELULAR = 4;
    const COMPRA = 5;

    const TYPES = [
        self::PAGAMENTO_DE_CONTA => 'Pagamento de Conta',
        self::DEPOSITO => 'Deposito',
        self::TRANSFERENCIA => 'Transferencia',
        self::RECARDA_DE_CELULAR => 'Recarda de Celular',
        self::COMPRA => 'Compra (Credito)',
    ];
}

<?php

namespace App\Enum;

abstract class AccountTypes{
    const Company = 1;
    const Person = 2;

    const TYPES = [
        self::Company => 'Empresa',
        self::Person => 'Pessoal',
    ];
}

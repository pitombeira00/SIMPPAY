<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Transactions
    |--------------------------------------------------------------------------
    |
    | Este arquivo contém os status do model Transactions [status],
    | Qual será o retorno que irá autorizar a ApiAuthorizationTransactionService [return],
    |
    */
    'status' => [
        'pending' => '1',
        'end' => '2',
        'notAuthorized' => '3'
    ],

    'return' => [
        'ApiAuthorizationMessage' => 'Autorizado'
    ]

];

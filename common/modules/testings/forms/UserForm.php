<?php

return [
    'activeForm'=>[
        'id' => 'testing-user-form',
    ],
    'elements' => [
        'sex' => []
            'type'  => 'dropdownlist',
            'items' => User::$sex_list
        ],
        'last_name' => ['type' => 'text'],
        'first_name' => ['type' => 'text'],
        'patronymic' => ['type' => 'text'],
        'company_name' => ['type' => 'text'],
        'city' => ['type' => 'text'],
        'email' => ['type' => 'text'],
        'tki' => ['type' => 'text'],
        'region' => ['type' => 'text'],
    ],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];






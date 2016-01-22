<?php

return [
    'activeForm'=>[
        'id' => 'testing-test-form',
    ],
    'elements'       => [
        'name' => ['type' => 'text'],
        'start_date' => ['type' => 'datetime', 'htmlOptions' => ['class'=>'text']],
        'end_date' => ['type' => 'datetime', 'htmlOptions' => ['class'=>'text']],
    ],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];

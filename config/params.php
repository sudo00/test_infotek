<?php

return [
    'smsPilot' => [
        'apiKey' => getenv('SMSPILOT_API_KEY') ?: 'эмулятор',
        'sender' => 'INFORM',
    ],
];

<?php

return array(
    'active' => 'querymail',
    'querymail' => array(
        'type' => 'pdo',
        'connection' => array(
            'dsn' => 'sqlite:' . APPPATH . '../../sqlite/querymail',
            'username' => '',
            'password' => '',
        ),
        'identifier' => '`',
        'table_prefix' => '',
        'charset' => '',
        'enable_cache' => true,
        'profiling' => false,
    ),
);

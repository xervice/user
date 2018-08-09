<?php

use Xervice\DataProvider\DataProviderConfig;
use Xervice\Database\DatabaseConfig;

$config[DataProviderConfig::DATA_PROVIDER_GENERATED_PATH] = dirname(__DIR__) . '/src/Generated';
$config[DataProviderConfig::DATA_PROVIDER_PATHS] = [
    dirname(__DIR__) . '/src/',
    dirname(__DIR__) . '/vendor/',
];

if (class_exists(DatabaseConfig::class)) {
    $config[DatabaseConfig::PROPEL_CONF_DIR] = __DIR__ . '/propel';
    $config[DatabaseConfig::PROPEL_CONF_ADAPTER] = 'pgsql';
    $config[DatabaseConfig::PROPEL_CONF_HOST] = '127.0.0.1';
    $config[DatabaseConfig::PROPEL_CONF_PORT] = '5432';
    $config[DatabaseConfig::PROPEL_CONF_DBNAME] = 'scrutinizer';
    $config[DatabaseConfig::PROPEL_CONF_USER] = 'scrutinizer';
    $config[DatabaseConfig::PROPEL_CONF_PASSWORD] = 'scrutinizer';
    $config[DatabaseConfig::SCHEMA_PATHS] = [
        dirname(__DIR__) . '/src'
    ];
    $config[DatabaseConfig::SCHEMA_TARGET] = dirname(__DIR__) . '/src/Orm/Schema';

    $dsn = sprintf(
        '%s:host=%s;port=%d;dbname=%s;user=%s;password=%s',
        $config[DatabaseConfig::PROPEL_CONF_ADAPTER],
        $config[DatabaseConfig::PROPEL_CONF_HOST],
        $config[DatabaseConfig::PROPEL_CONF_PORT],
        $config[DatabaseConfig::PROPEL_CONF_DBNAME],
        $config[DatabaseConfig::PROPEL_CONF_USER],
        $config[DatabaseConfig::PROPEL_CONF_PASSWORD]
    );

    $config[DatabaseConfig::PROPEL] = [
        'propel' => [
            'database'  => [
                'connections' => [
                    'default' => [
                        'adapter'    => $config[DatabaseConfig::PROPEL_CONF_ADAPTER],
                        'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                        'dsn'        => $dsn,
                        'user'       => $config[DatabaseConfig::PROPEL_CONF_USER],
                        'password'   => $config[DatabaseConfig::PROPEL_CONF_PASSWORD],
                        'attributes' => [
                            'ATTR_EMULATE_PREPARES' => false,
                            'ATTR_TIMEOUT'          => 30
                        ]
                    ]
                ],
            ],
            'runtime'   => [
                'defaultConnection' => 'default',
                'connections'       => ['default']
            ],
            'generator' => [
                'defaultConnection' => 'default',
                'connections'       => ['default'],
                'recursive'         => true,
                'tablePrefix'       => 'app_'
            ],
            'paths'     => [
                'projectDir'   => dirname(__DIR__),
                'schemaDir'    => $config[DatabaseConfig::SCHEMA_TARGET],
                'outputDir'    => dirname(__DIR__) . '/src/Orm/Output',
                'phpDir'       => dirname(__DIR__) . '/src/',
                'migrationDir' => dirname(__DIR__) . '/src/Orm/Migrations',
                'phpConfDir'   => dirname(__DIR__) . '/src/Orm/Config',
                'sqlDir'       => dirname(__DIR__) . '/src/Orm/Sql'
            ],
        ]
    ];
}
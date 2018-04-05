<?php

    /*##################################################################################################################
    * RAIZ DO PROJETO
    ##################################################################################################################*/

    define('ROOT_PATH', __DIR__);

    /*##################################################################################################################
    * CONFIGURAÇÃOES DO PHP
    ##################################################################################################################*/

    # Remove o limite de memória.
    ini_set('memory_limit', '-1');

    /*##################################################################################################################
    * BASE DE DADOS DE ORIGEM
    ##################################################################################################################*/

    # Producao

    define('DB_HOST_ORIGEN_PRO', '192.168.0.10');
    define('DB_BASE_ORIGEN_PRO', 'overmin');
    define('DB_USER_ORIGEN_PRO', 'mdi');
    define('DB_PASS_ORIGEN_PRO', 'md1dB1502');

    # Teste

    define('DB_HOST_ORIGEN_TEST', '192.168.0.16');
    define('DB_BASE_ORIGEN_TEST', 'zadmin_overdev');
    define('DB_USER_ORIGEN_TEST', 'mydev');
    define('DB_PASS_ORIGEN_TEST', 'devdbov');


    /*##################################################################################################################
    * BASE DE DADOS DE DESTINO
    ##################################################################################################################*/

    # Producao

    define('DB_HOST_DESTINY_PRO', '192.168.0.10');
    define('DB_BASE_DESTINY_PRO', 'overmin_xp');
    define('DB_USER_DESTINY_PRO', 'mdi');
    define('DB_PASS_DESTINY_PRO', 'md1dB1502');

    # Teste

    define('DB_HOST_DESTINY_TEST', '192.168.0.16');
    define('DB_BASE_DESTINY_TEST', 'overmin_dev');
    define('DB_USER_DESTINY_TEST', 'mydev');
    define('DB_PASS_DESTINY_TEST', 'devdbov');


?>

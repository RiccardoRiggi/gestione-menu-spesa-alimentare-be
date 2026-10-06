<?php

include '../config-LOCALE.php';
include '../utils/cifraturaUtils.php';
include '../utils/httpResponseCodeUtils.php';
include '../utils/logUtils.php';
include '../utils/indirizzoIpUtils.php';

include '../utils/validatorsUtils.php';
include '../utils/tokenUtils.php';
include '../database/database.php';

include '../database/exceptions.php';


if (ABILITA_CORS) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Expose-Headers: *');
        header('CUSTOM-HEADER: CUSTOM-VALUE');
        header('Access-Control-Max-Age: 86400');
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'options')
                exit();
}
try {
        verificaIndirizzoIp();
} catch (OtterGuardianException $e) {
        http_response_code($e->getStatus());
        $oggetto = new stdClass();
        $oggetto->codice = $e->getStatus();
        $oggetto->descrizione = $e->getMessage();
        exit(json_encode($oggetto));
}

<?php


if (!function_exists('cifraStringa')) {
    function cifraStringa($stringaDaCifrare)
    {
        $stringaCifrata = openssl_encrypt($stringaDaCifrare, CIPHERING_VALUE, ENCRYPTION_KEY, CIPHERING_OPTIONS, ENCRYPTION_IV_VALUE);
        return $stringaCifrata;
    }
}

if (!function_exists('decifraStringa')) {
    function decifraStringa($stringaCifrata)
    {
        $stringaDecifrata = openssl_decrypt($stringaCifrata, CIPHERING_VALUE, ENCRYPTION_KEY, CIPHERING_OPTIONS, ENCRYPTION_IV_VALUE);
        return $stringaDecifrata;
    }
}

if (!function_exists('cifraStringaPreToken')) {
    function cifraStringaPreToken($stringaDaCifrare)
    {
        $stringaCifrata = openssl_encrypt($stringaDaCifrare, CIPHERING_VALUE_PRE_TOKEN, ENCRYPTION_KEY_PRE_TOKEN, CIPHERING_OPTIONS_PRE_TOKEN, ENCRYPTION_IV_VALUE_PRE_TOKEN);
        return $stringaCifrata;
    }
}

if (!function_exists('decifraStringaPreToken')) {
    function decifraStringaPreToken($stringaCifrata)
    {
        $stringaDecifrata = openssl_decrypt($stringaCifrata, CIPHERING_VALUE_PRE_TOKEN, ENCRYPTION_KEY_PRE_TOKEN, CIPHERING_OPTIONS_PRE_TOKEN, ENCRYPTION_IV_VALUE_PRE_TOKEN);
        return $stringaDecifrata;
    }
}

if (!function_exists("generaUUID")) {
    function generaUUID()
    {
        return uniqid("", true) . "-" . uniqid("", true);
    }
}

if (!function_exists("generaCodiceSeiCifre")) {
    function generaCodiceSeiCifre()
    {
        return rand(100000, 999999);
    }
}
if (!function_exists("generaSegretoTOTP")) {
    function generaSegretoTOTP($lunghezza = 32)
    {
        $caratteri = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $segreto = '';
        for ($i = 0; $i < $lunghezza; $i++) {
            $segreto .= $caratteri[random_int(0, 31)];
        }
        return $segreto;
    }
}

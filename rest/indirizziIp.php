<?php

include './importManager.php';
include '../services/indirizziIpService.php';


try {

    switch ($_GET["nomeMetodo"]) {

        case 'getIndirizziIp':

            verificaMetodoHttp("GET");
            $response = getIndirizziIp(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'sbloccaIndirizzoIp':

            verificaMetodoHttp("PUT");
            $response = sbloccaIndirizzoIp(recuperaParametroJsonBody("indirizzoIp"));
            http_response_code(200);
            exit(null);

        case 'bloccaIndirizzoIp':

            verificaMetodoHttp("PUT");
            $response = bloccaIndirizzoIpLista(recuperaParametroJsonBody("indirizzoIp"));
            http_response_code(200);
            exit(null);

        case 'azzeraContatoreAlert':

            verificaMetodoHttp("PUT");
            $response = azzeraContatoreAlert(recuperaParametroJsonBody("indirizzoIp"));
            http_response_code(200);
            exit(null);


        default:
            throw new OtterGuardianException(500, "Metodo non implementato");
            exit(null);
    }
} catch (AccessoNonAutorizzatoLoginException $e) {
    httpAccessoNonAutorizzatoLogin();
} catch (AccessoNonAutorizzatoException $e) {
    httpAccessoNonAutorizzato();
} catch (MetodoHttpErratoException $e) {
    httpMetodoHttpErrato();
} catch (ErroreServerException $e) {
    httpErroreServer($e->getMessage());
} catch (OtterGuardianException $e) {
    http_response_code($e->getStatus());
    $oggetto = new stdClass();
    $oggetto->codice = $e->getStatus();
    $oggetto->descrizione = $e->getMessage();
    exit(json_encode($oggetto));
} catch (Exception $e) {
    generaLogSuFile("Errore sconosciuto: " . $e->getMessage());
    httpErroreServer("Errore sconosciuto");
}

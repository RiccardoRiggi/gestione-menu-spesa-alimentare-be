<?php

include './importManager.php';
include '../services/logService.php';


try {

    switch ($_GET["nomeMetodo"]) {

        case 'getLogs':

            verificaMetodoHttp("GET");
            $response = getLogs(recuperaParametroGet("pagina"), recuperaParametroGet("livelloLog"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getLogsTelegram':

            verificaMetodoHttp("GET");
            $response = getLogsTelegram(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getNotificheTelegram':

            verificaMetodoHttp("GET");
            $response = getNotificheTelegram(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

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

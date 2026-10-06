<?php

include './importManager.php';
include '../services/statisticheService.php';


try {

    switch ($_GET["nomeMetodo"]) {

        case 'getStatisticheMetodi':

            verificaMetodoHttp("GET");
            $response = getStatisticheMetodi();
            http_response_code(200);
            exit(json_encode($response));

        case 'getNumeroDispositiviFisiciAttivi':

            verificaMetodoHttp("GET");
            $response = getNumeroDispositiviFisiciAttivi();
            http_response_code(200);
            exit(json_encode($response));

        case 'getNumeroIndirizziIp':

            verificaMetodoHttp("GET");
            $response = getNumeroIndirizziIp();
            http_response_code(200);
            exit(json_encode($response));

        case 'getNumeroLogin':

            verificaMetodoHttp("GET");
            $response = getNumeroLogin();
            http_response_code(200);
            exit(json_encode($response));

        case 'getNumeroRisorse':

            verificaMetodoHttp("GET");
            $response = getNumeroRisorse();
            http_response_code(200);
            exit(json_encode($response));

        case 'getNumeroAccessiAttivi':

            verificaMetodoHttp("GET");
            $response = getNumeroAccessiAttivi();
            http_response_code(200);
            exit(json_encode($response));

        case 'getNumeroUtenti':

            verificaMetodoHttp("GET");
            $response = getNumeroUtenti();
            http_response_code(200);
            exit(json_encode($response));

        case 'getNumeroRuoli':

            verificaMetodoHttp("GET");
            $response = getNumeroRuoli();
            http_response_code(200);
            exit(json_encode($response));

        case 'getNumeroVociMenu':

            verificaMetodoHttp("GET");
            $response = getNumeroVociMenu();
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

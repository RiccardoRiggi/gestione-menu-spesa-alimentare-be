<?php

include './importManager.php';
include '../services/autenticazioneService.php';

include '../services/webHookTelegramService.php';




try {

    switch ($_GET["nomeMetodo"]) {

        case 'generaPreToken':

            verificaMetodoHttp("GET");
            $totpCodice = isset($_SERVER["HTTP_TOTP"]) ? $_SERVER["HTTP_TOTP"] : null;
            $response = generaPreToken($totpCodice);
            http_response_code(200);
            $oggetto = new stdClass();
            $oggetto->preToken = $response;
            exit(json_encode($oggetto));

        case 'getMedotoAutenticazionePredefinito':

            verificaMetodoHttp("POST");
            $response = getMedotoAutenticazionePredefinito(recuperaParametroJsonBody("email"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getMetodiAutenticazioneSupportati':

            verificaMetodoHttp("POST");
            $response = getMetodiAutenticazioneSupportati(recuperaParametroJsonBody("email"));
            http_response_code(200);
            exit(json_encode($response));

        case 'effettuaAutenticazione':

            verificaMetodoHttp("POST");
            verificaParametroJsonBody("email");

            $jsonBody = json_decode(file_get_contents('php://input'), true);

            if (!isset($jsonBody["password"])) {
                if (!isset($jsonBody["tipoAutenticazione"]) || str_contains($jsonBody["tipoAutenticazione"], "PSW")) {
                    throw new OtterGuardianException(400, "Il campo password è richiesto");
                    exit(null);
                }
            }

            $response = effettuaAutenticazione(getParametroJsonBody("email"), isset($jsonBody["password"]) ? $jsonBody["password"] : null, isset($jsonBody["tipoAutenticazione"]) ? $jsonBody["tipoAutenticazione"] : null);
            http_response_code(200);
            exit(json_encode($response));

        case 'confermaAutenticazione':

            verificaMetodoHttp("POST");
            confermaAutenticazione(recuperaParametroJsonBody("idLogin"), recuperaParametroJsonBody("codice"));
            http_response_code(200);
            exit(null);

        case 'recuperaTokenDaLogin':

            verificaMetodoHttp("GET");
            recuperaTokenDaLogin(recuperaParametroGet("idLogin"));
            http_response_code(200);
            exit(null);

        case 'generaQrCode':

            verificaMetodoHttp("GET");
            $response = generaQrCode();
            http_response_code(200);
            $oggetto = new stdClass();
            $oggetto->idQrCode = $response;
            exit(json_encode($oggetto));

        case 'recuperaTokenDaQrCode':

            verificaMetodoHttp("GET");
            recuperaTokenDaQrCode(recuperaParametroGet("idQrCode"));
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

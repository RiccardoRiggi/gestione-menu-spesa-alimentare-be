<?php

include './importManager.php';
include '../services/utenteLoggatoService.php';
include '../services/webHookTelegramService.php';


try {

    switch ($_GET["nomeMetodo"]) {

        case 'getChiaveGlobale':

            verificaMetodoHttp("GET");
            $response = getChiaveGlobale();
            http_response_code(200);
            exit(json_encode($response));

        case 'getUtenteLoggato':

            verificaMetodoHttp("GET");
            $response = getUtenteLoggato();
            http_response_code(200);
            exit(json_encode($response));

        case 'generaCodiciBackup':

            verificaMetodoHttp("GET");
            $response = generaCodiciBackup();
            http_response_code(200);
            exit(json_encode($response));

        case 'verificaAutenticazione':

            verificaMetodoHttp("GET");
            verificaAutenticazione();
            http_response_code(200);
            exit(null);

        case 'invalidaToken':

            verificaMetodoHttp("PUT");
            invalidaTokenSpecifico();
            http_response_code(200);
            exit(json_encode(null));

        case 'getStoricoAccessi':

            verificaMetodoHttp("GET");
            $response = getStoricoAccessi(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getMetodiAutenticazionePerUtenteLoggato':

            verificaMetodoHttp("GET");
            $response = getMetodiAutenticazionePerUtenteLoggato();
            http_response_code(200);
            exit(json_encode($response));

        case 'abilitaTipoMetodoLogin':

            verificaMetodoHttp("PUT");
            $response = abilitaTipoMetodoLogin(recuperaParametroGet("idTipoMetodoLogin"));
            http_response_code(200);
            exit(json_encode($response));

        case 'disabilitaTipoMetodoLogin':

            verificaMetodoHttp("PUT");
            $response = disabilitaTipoMetodoLogin(recuperaParametroGet("idTipoMetodoLogin"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getMetodiRecuperoPasswordPerUtenteLoggato':

            verificaMetodoHttp("GET");
            $response = getMetodiRecuperoPasswordPerUtenteLoggato();
            http_response_code(200);
            exit(json_encode($response));

        case 'abilitaTipoRecuperoPassword':

            verificaMetodoHttp("PUT");
            $response = abilitaTipoRecuperoPassword(recuperaParametroGet("idTipoMetodoRecPsw"));
            http_response_code(200);
            exit(json_encode($response));

        case 'disabilitaTipoRecuperoPassword':

            verificaMetodoHttp("PUT");
            $response = disabilitaTipoRecuperoPassword(recuperaParametroGet("idTipoMetodoRecPsw"));
            http_response_code(200);
            exit(json_encode($response));

        case 'aggiornaSottoscrizione':

            verificaMetodoHttp("POST");
            $response = aggiornaSottoscrizione(recuperaParametroJsonBody("idSottoscrizione"));
            http_response_code(200);
            exit();



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

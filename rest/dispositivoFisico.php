<?php

include './importManager.php';
include '../services/dispositivoFisicoService.php';
include '../services/autenticazioneService.php';


try {

    switch ($_GET["nomeMetodo"]) {

        case 'generaChiavePersonaleTOTP':

            verificaMetodoHttp("GET");
            $response = generaChiavePersonaleTOTP();
            http_response_code(200);
            exit(json_encode($response));

        case 'generaIdentificativoDispositivoFisico':

            verificaMetodoHttp("GET");
            $response = generaIdentificativoDispositivoFisico();
            http_response_code(200);
            exit(json_encode($response));

        case 'generaIdentificativoTelegram':

            verificaMetodoHttp("GET");
            $response = generaIdentificativoTelegram();
            http_response_code(200);
            exit(json_encode($response));

        case 'isDispositivoAbilitato':

            verificaMetodoHttp("GET");
            $response = isDispositivoAbilitato(recuperaParametroGet("idDispositivoFisico"));
            http_response_code(200);
            exit(json_encode($response));

        case 'isDispositivoTelegramAbilitato':

            verificaMetodoHttp("GET");
            $response = isDispositivoTelegramAbilitato(recuperaParametroGet("idDispositivoFisico"));
            http_response_code(200);
            exit(json_encode($response));

        case 'abilitaDispositivoFisico':

            verificaMetodoHttp("PUT");
            $response = abilitaDispositivoFisico(recuperaParametroJsonBody("idDispositivoFisico"), recuperaParametroJsonBody("nomeDispositivo"));
            http_response_code(200);
            exit(json_encode($response));

        case 'disabilitaDispositivoFisico':

            verificaMetodoHttp("PUT");
            $response = disabilitaDispositivoFisico(recuperaParametroJsonBody("idDispositivoFisico"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getDispositiviFisici':

            verificaMetodoHttp("GET");
            $response = getDispositiviFisici(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getDispositiviFisiciTelegram':

            verificaMetodoHttp("GET");
            $response = getDispositiviFisiciTelegram(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getRichiesteDiAccessoPendenti':

            verificaMetodoHttp("POST");
            $response = getRichiesteDiAccessoPendenti(recuperaParametroJsonBody("idDispositivoFisico"));
            http_response_code(200);
            exit(json_encode($response));

        case 'autorizzaAccesso':

            verificaMetodoHttp("POST");
            autorizzaAccesso(recuperaParametroJsonBody("idDispositivoFisico"), recuperaParametroJsonBody("idTwoFact"));
            http_response_code(200);
            exit(null);

        case 'autorizzaQrCode':

            verificaMetodoHttp("POST");
            autorizzaQrCode(recuperaParametroJsonBody("idDispositivoFisico"), recuperaParametroJsonBody("idQrCode"));
            http_response_code(200);
            exit(null);

        case 'getListaDispositiviFisici':

            verificaMetodoHttp("GET");
            $response = getListaDispositiviFisici(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getListaDispositiviFisiciTelegram':

            verificaMetodoHttp("GET");
            $response = getListaDispositiviFisiciTelegram(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'rimuoviDispositivoFisico':

            verificaMetodoHttp("PUT");
            $response = rimuoviDispositivoFisico(recuperaParametroJsonBody("idDispositivoFisico"));
            http_response_code(200);
            exit(json_encode($response));

        case 'rimuoviDispositivoFisicoTelegram':

            verificaMetodoHttp("PUT");
            $response = rimuoviDispositivoFisicoTelegram(recuperaParametroJsonBody("idDispositivoFisico"));
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

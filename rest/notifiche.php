<?php

include './importManager.php';
include '../services/notificheService.php';
include '../services/webHookTelegramService.php';
include '../services/utentiService.php';


try {

    switch ($_GET["nomeMetodo"]) {

        case 'getListaNotifiche':

            verificaMetodoHttp("GET");
            $response = getListaNotifiche(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'inserisciNotifica':

            verificaMetodoHttp("POST");
            inserisciNotifica(recuperaParametroJsonBody("titolo"), recuperaParametroJsonBody("testo"));
            http_response_code(200);
            exit(null);

        case 'modificaNotifica':

            verificaMetodoHttp("PUT");
            $response = modificaNotifica(recuperaParametroJsonBody("titolo"), recuperaParametroJsonBody("testo"), recuperaParametroGet("idNotifica"));
            http_response_code(200);
            exit(null);

        case 'eliminaNotifica':

            verificaMetodoHttp("DELETE");
            eliminaNotifica(recuperaParametroGet("idNotifica"));
            http_response_code(200);
            exit(null);

        case 'getNotifica':

            verificaMetodoHttp("GET");
            $response = getNotifica(recuperaParametroGet("idNotifica"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getDestinatariNotifica':

            verificaMetodoHttp("GET");
            $response = getDestinatariNotifica(recuperaParametroGet("pagina"), recuperaParametroGet(["idNotifica"]));
            http_response_code(200);
            exit(json_encode($response));

        case 'inviaNotificaTutti':

            verificaMetodoHttp("POST");
            $response = inviaNotificaTutti(recuperaParametroGet("idNotifica"), isset($_GET["invioViaTelegram"]));
            http_response_code(200);
            exit(json_encode($response));

        case 'inviaNotificaRuolo':

            verificaMetodoHttp("POST");
            $response = inviaNotificaRuolo(recuperaParametroGet("idNotifica"), recuperaParametroGet("idTipoRuolo"), isset($_GET["invioViaTelegram"]));
            http_response_code(200);
            exit(json_encode($response));

        case 'inviaNotificaUtente':

            verificaMetodoHttp("POST");
            $response = inviaNotificaUtente(recuperaParametroGet("idNotifica"), recuperaParametroGet(["idUtente"]), isset($_GET["invioViaTelegram"]));
            http_response_code(200);
            exit(json_encode($response));

        case 'getNotificaLatoUtente':

            verificaMetodoHttp("GET");
            $response = getNotificaLatoUtente(recuperaParametroGet("idNotifica"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getNotificheLatoUtente':

            verificaMetodoHttp("GET");
            $response = getNotificheLatoUtente(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'eliminaNotificaLatoUtente':

            verificaMetodoHttp("DELETE");
            eliminaNotificaLatoUtente(recuperaParametroGet("idNotifica"));
            http_response_code(200);
            exit(null);

        case 'leggiNotificheLatoUtente':

            verificaMetodoHttp("PUT");
            leggiNotificheLatoUtente();
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

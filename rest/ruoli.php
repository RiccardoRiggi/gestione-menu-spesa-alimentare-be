<?php

include './importManager.php';
include '../services/ruoliService.php';


try {

    switch ($_GET["nomeMetodo"]) {

        case 'getRuoli':

            verificaMetodoHttp("GET");
            $response = getRuoli(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'inserisciRuolo':

            verificaMetodoHttp("POST");

            if (str_starts_with(recuperaParametroJsonBody("idTipoRuolo"), "AMM")) {
                throw new OtterGuardianException(400, "Non puoi inserire il ruolo AMM");
            }

            if (str_starts_with(recuperaParametroJsonBody("idTipoRuolo"), "USER")) {
                throw new OtterGuardianException(400, "Non puoi inserire il ruolo USER");
            }

            inserisciRuolo(recuperaParametroJsonBody("idTipoRuolo"), recuperaParametroJsonBody("descrizione"));
            http_response_code(200);
            exit(null);

        case 'modificaRuolo':

            verificaMetodoHttp("PUT");

            if (str_starts_with(recuperaParametroGet("idTipoRuolo"), "AMM")) {
                throw new OtterGuardianException(400, "Non puoi modificare il ruolo AMM");
            }

            if (str_starts_with(recuperaParametroGet("idTipoRuolo"), "USER")) {
                throw new OtterGuardianException(400, "Non puoi modificare il ruolo USER");
            }

            $response = modificaRuolo(recuperaParametroJsonBody("descrizione"), recuperaParametroGet("idTipoRuolo"));
            http_response_code(200);
            exit(null);

        case 'eliminaRuolo':

            verificaMetodoHttp("DELETE");

            if (str_starts_with(recuperaParametroGet("idTipoRuolo"), "AMM")) {
                throw new OtterGuardianException(400, "Non puoi eliminare il ruolo AMM");
            }

            if (str_starts_with(recuperaParametroGet("idTipoRuolo"), "USER")) {
                throw new OtterGuardianException(400, "Non puoi eliminare il ruolo USER");
            }

            eliminaRuolo(recuperaParametroGet("idTipoRuolo"));
            http_response_code(200);
            exit(null);

        case 'getRuolo':

            verificaMetodoHttp("GET");
            $response = getRuolo(recuperaParametroGet("idTipoRuolo"));
            http_response_code(200);
            exit(json_encode($response));

        case 'associaRuoloUtente':

            verificaMetodoHttp("PUT");
            associaRuoloUtente(recuperaParametroGet("idTipoRuolo"), recuperaParametroGet("idUtente"));
            http_response_code(200);
            exit(null);

        case 'dissociaRuoloUtente':

            verificaMetodoHttp("PUT");
            dissociaRuoloUtente(recuperaParametroGet("idTipoRuolo"), recuperaParametroGet("idUtente"));
            http_response_code(200);
            exit(null);

        case 'getUtentiPerRuolo':

            verificaMetodoHttp("GET");
            $response = getUtentiPerRuolo(recuperaParametroGet("idTipoRuolo"), recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'associaRuoloRisorsa':

            verificaMetodoHttp("PUT");
            associaRuoloRisorsa(recuperaParametroGet("idTipoRuolo"), recuperaParametroGet("idRisorsa"));
            http_response_code(200);
            exit(null);

        case 'dissociaRuoloRisorsa':

            verificaMetodoHttp("PUT");
            dissociaRuoloRisorsa(recuperaParametroGet("idTipoRuolo"), recuperaParametroGet("idRisorsa"));
            http_response_code(200);
            exit(null);

        case 'getRisorsePerRuolo':

            verificaMetodoHttp("GET");
            $response = getRisorsePerRuolo(recuperaParametroGet("idTipoRuolo"), recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'associaRuoloVoceMenu':

            verificaMetodoHttp("PUT");
            associaRuoloVoceMenu(recuperaParametroGet("idTipoRuolo"), recuperaParametroGet("idVoceMenu"));
            http_response_code(200);
            exit(null);

        case 'dissociaRuoloVoceMenu':

            verificaMetodoHttp("PUT");
            dissociaRuoloVoceMenu(recuperaParametroGet("idTipoRuolo"), recuperaParametroGet("idVoceMenu"));
            http_response_code(200);
            exit(null);

        case 'getVociMenuPerRuolo':

            verificaMetodoHttp("GET");
            $response = getVociMenuPerRuolo(recuperaParametroGet("idTipoRuolo"), recuperaParametroGet("pagina"));
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

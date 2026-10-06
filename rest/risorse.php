<?php

include './importManager.php';
include '../services/risorseService.php';


try {

    switch ($_GET["nomeMetodo"]) {

        case 'getRisorse':

            verificaMetodoHttp("GET");
            $response = getRisorse(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'inserisciRisorsa':

            verificaMetodoHttp("POST");

            if (str_starts_with(getParametroJsonBody("idRisorsa"), "AMM_")) {
                throw new OtterGuardianException(400, "Il prefisso dell'id risorsa non è utilizzabile per la creazione di nuove risorse");
            }

            if (str_starts_with(getParametroJsonBody("idRisorsa"), "USER_")) {
                throw new OtterGuardianException(400, "Il prefisso dell'id risorsa non è utilizzabile per la creazione di nuove risorse");
            }

            inserisciRisorsa(recuperaParametroJsonBody("idRisorsa"), recuperaParametroJsonBody("nomeMetodo"), recuperaParametroJsonBody("descrizione"));
            http_response_code(200);
            exit(null);

        case 'modificaRisorsa':

            verificaMetodoHttp("PUT");

            if (str_starts_with(recuperaParametroGet("idRisorsa"), "AMM_")) {
                throw new OtterGuardianException(400, "Il prefisso dell'id risorsa non è utilizzabile per la modifica di una risorsa");
            }

            if (str_starts_with(recuperaParametroGet("idRisorsa"), "USER_")) {
                throw new OtterGuardianException(400, "Il prefisso dell'id risorsa non è utilizzabile per la modifica di una risorsa");
            }

            $response = modificaRisorsa(recuperaParametroJsonBody("nomeMetodo"), recuperaParametroJsonBody("descrizione"), recuperaParametroGet("idRisorsa"));
            http_response_code(200);
            exit(null);

        case 'eliminaRisorsa':

            verificaMetodoHttp("DELETE");

            if (str_starts_with(recuperaParametroGet("idRisorsa"), "AMM_")) {
                throw new OtterGuardianException(400, "Il prefisso dell'id risorsa non è utilizzabile per l'eliminazione di una risorsa");
            }

            if (str_starts_with(recuperaParametroGet("idRisorsa"), "USER_")) {
                throw new OtterGuardianException(400, "Il prefisso dell'id risorsa non è utilizzabile per l'eliminazione di una risorsa");
            }

            eliminaRisorsa(recuperaParametroGet("idRisorsa"));
            http_response_code(200);
            exit(null);

        case 'getRisorsa':

            verificaMetodoHttp("GET");
            $response = getRisorsa(recuperaParametroGet("idRisorsa"));
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

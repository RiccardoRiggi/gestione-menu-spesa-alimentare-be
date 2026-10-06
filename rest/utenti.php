<?php

include './importManager.php';
include '../services/utentiService.php';


try {

    switch ($_GET["nomeMetodo"]) {

        case 'getListaUtenti':

            verificaMetodoHttp("GET");
            $response = getListaUtenti(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'inserisciUtente':

            verificaMetodoHttp("POST");

            if (recuperaParametroJsonBody("confermaPassword") !== recuperaParametroJsonBody("password")) {
                throw new OtterGuardianException(400, "Il campo password deve essere uguale al campo confermaPassword");
            }

            inserisciUtente(recuperaParametroJsonBody("nome"), recuperaParametroJsonBody("cognome"), recuperaParametroJsonBody("email"), recuperaParametroJsonBody("password"));
            http_response_code(200);
            exit(null);

        case 'modificaUtente':

            verificaMetodoHttp("PUT");
            $response = modificaUtente(recuperaParametroJsonBody("nome"), recuperaParametroJsonBody("cognome"), recuperaParametroGet("idUtente"));
            http_response_code(200);
            exit(null);

        case 'eliminaUtente':

            verificaMetodoHttp("DELETE");
            eliminaUtente(recuperaParametroGet("idUtente"));
            http_response_code(200);
            exit(null);

        case 'getUtente':

            verificaMetodoHttp("GET");
            $response = getUtente(recuperaParametroGet("idUtente"));
            http_response_code(200);
            exit(json_encode($response));

        case 'bloccaUtente':

            verificaMetodoHttp("PUT");
            bloccaUtente(recuperaParametroGet("idUtente"));
            http_response_code(200);
            exit(null);

        case 'sbloccaUtente':

            verificaMetodoHttp("PUT");
            sbloccaUtente(recuperaParametroGet("idUtente"));
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

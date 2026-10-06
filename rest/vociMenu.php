<?php

include './importManager.php';
include '../services/vociMenuService.php';


try {

    switch ($_GET["nomeMetodo"]) {

        case 'getVociMenu':

            verificaMetodoHttp("GET");
            $response = getVociMenu(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'inserisciVoceMenu':

            verificaMetodoHttp("POST");
            $response = inserisciVoceMenu(recuperaParametroJsonBody("idVoceMenuPadre"), recuperaParametroJsonBody("descrizione"), recuperaParametroJsonBody("path"), recuperaParametroJsonBody("icona"), recuperaParametroJsonBody("ordine"));
            http_response_code(200);
            exit(json_encode($response));

        case 'modificaVoceMenu':

            verificaMetodoHttp("PUT");
            $response = modificaVoceMenu(recuperaParametroJsonBody("idVoceMenuPadre"), recuperaParametroJsonBody("descrizione"), recuperaParametroJsonBody("path"), recuperaParametroJsonBody("icona"), recuperaParametroJsonBody("ordine"), recuperaParametroGet("idVoceMenu"));
            http_response_code(200);
            exit(null);

        case 'eliminaVoceMenu':

            verificaMetodoHttp("DELETE");
            $response = eliminaVoceMenu(recuperaParametroGet("idVoceMenu"));
            http_response_code(200);
            exit(null);

        case 'getVociMenuPerUtente':

            verificaMetodoHttp("GET");
            $response = getVociMenuPerUtente();
            http_response_code(200);
            exit(json_encode($response));

        case 'getVoceMenu':

            verificaMetodoHttp("GET");
            $response = getVoceMenu(recuperaParametroGet("idVoceMenu"));
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

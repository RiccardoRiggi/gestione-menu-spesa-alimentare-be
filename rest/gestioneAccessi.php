<?php

include './importManager.php';
include '../services/gestioneAccessiService.php';


try {

    switch ($_GET["nomeMetodo"]) {

        case 'getListaAccessi':

            verificaMetodoHttp("GET");
            $response = getListaAccessi(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'terminaAccesso':

            verificaMetodoHttp("PUT");
            $response = terminaAccesso(recuperaParametroJsonBody("token"));
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

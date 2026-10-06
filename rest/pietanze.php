<?php

include './importManager.php';
include '../services/pietanzeService.php';

try {

    switch ($_GET["nomeMetodo"]) {

        case 'getPietanze':

            verificaMetodoHttp("GET");
            $response = getPietanze(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getPietanzeNoPaginate':

            verificaMetodoHttp("GET");
            $response = getPietanzeNoPaginate();
            http_response_code(200);
            exit(json_encode($response));

        case 'inserisciPietanza':

            verificaMetodoHttp("POST");
            $id = inserisciPietanza(recuperaParametroJsonBody("nome"), recuperaParametroJsonBody("note"));
            http_response_code(200);
            exit(json_encode($id));

        case 'modificaPietanza':

            verificaMetodoHttp("PUT");
            $response = modificaPietanza(recuperaParametroJsonBody("nome"), recuperaParametroJsonBody("note"), recuperaParametroGet("idPietanza"));
            http_response_code(200);
            exit(null);

        case 'eliminaPietanza':

            verificaMetodoHttp("DELETE");
            eliminaPietanza(recuperaParametroGet("idPietanza"));
            http_response_code(200);
            exit(null);

        case 'getPietanza':

            verificaMetodoHttp("GET");
            $response = getPietanza(recuperaParametroGet("idPietanza"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getUltimeDateSomministrazionePietanza':

            verificaMetodoHttp("GET");
            $response = getUltimeDateSomministrazionePietanza(recuperaParametroGet("idPietanza"));
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
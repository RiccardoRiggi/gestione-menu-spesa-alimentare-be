<?php

include './importManager.php';
include '../services/spesaService.php';


try {

    switch ($_GET["nomeMetodo"]) {

        case 'getListaSpese':

            verificaMetodoHttp("GET");
            $response = getListaSpese(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'inserisciSpesa':

            verificaMetodoHttp("POST");
            $id = inserisciSpesa(recuperaParametroJsonBody("idIngrediente"), recuperaParametroJsonBody("dataSpesa"), recuperaParametroJsonBody("note"));
            http_response_code(200);
            exit(json_encode($id));

        case 'modificaSpesa':

            verificaMetodoHttp("PUT");
            $response = modificaSpesa(recuperaParametroJsonBody("idIngrediente"), recuperaParametroJsonBody("dataSpesa"), recuperaParametroJsonBody("dataAcquisto"), recuperaParametroJsonBody("note"), recuperaParametroGet("idSpesa"));
            http_response_code(200);
            exit(null);

        case 'eliminaSpesa':

            verificaMetodoHttp("DELETE");
            eliminaSpesa(recuperaParametroGet("idSpesa"));
            http_response_code(200);
            exit(null);

        case 'getListaSpesa':

            verificaMetodoHttp("GET");
            $response = getListaSpesa(recuperaParametroGet("dataSpesa"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getIngredientiDaComprare':

            verificaMetodoHttp("GET");
            $response = getIngredientiDaComprare(recuperaParametroGet("dataInizio"), recuperaParametroGet("dataFine"));
            http_response_code(200);
            exit(json_encode($response));

        case 'getDataUltimoAcquisto':

            verificaMetodoHttp("GET");
            $response = getDataUltimoAcquisto(recuperaParametroGet("idIngrediente"));
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
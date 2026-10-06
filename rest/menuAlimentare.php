<?php

include './importManager.php';
include '../services/menuAlimentareService.php';

try {

    switch ($_GET["nomeMetodo"]) {

        case 'getMenuAlimentari':

            verificaMetodoHttp("GET");
            $response = getMenuAlimentari(recuperaParametroGet("dataInizio"), recuperaParametroGet("dataFine"));
            http_response_code(200);
            exit(json_encode($response));

        case 'inserisciMenuAlimentare':

            verificaMetodoHttp("POST");
            $id = inserisciMenuAlimentare(recuperaParametroJsonBody("idTipoPasto"), recuperaParametroJsonBody("dataPasto"), recuperaParametroJsonBody("idPietanza"), recuperaParametroJsonBody("note"));
            http_response_code(200);
            exit(json_encode($id));

        case 'modificaMenuAlimentare':

            verificaMetodoHttp("PUT");
            $response = modificaMenuAlimentare(recuperaParametroJsonBody("idTipoPasto"), recuperaParametroJsonBody("dataPasto"), recuperaParametroJsonBody("idPietanza"), recuperaParametroJsonBody("note"), recuperaParametroGet("idMenuAlimentare"));
            http_response_code(200);
            exit(null);

        case 'eliminaMenuAlimentare':

            verificaMetodoHttp("DELETE");
            eliminaMenuAlimentare(recuperaParametroGet("idMenuAlimentare"));
            http_response_code(200);
            exit(null);

        case 'getMenuAlimentare':

            verificaMetodoHttp("GET");
            $response = getMenuAlimentare(recuperaParametroGet("idMenuAlimentare"));
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
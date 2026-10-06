<?php

include './importManager.php';
include '../services/ingredientiService.php';


const NOME_METODO_GET_SINGOLO = "getIngrediente";


try {

    switch ($_GET["nomeMetodo"]) {

        case 'getIngredienti':

            verificaMetodoHttp("GET");
            $response = getIngredienti(recuperaParametroGet("pagina"));
            http_response_code(200);
            exit(json_encode($response));

        case 'inserisciIngrediente':

            verificaMetodoHttp("POST");
            $id = inserisciIngrediente(recuperaParametroJsonBody("nome"), recuperaParametroJsonBody("prezzoRiferimento"));
            http_response_code(200);
            exit(json_encode($id));

        case 'modificaIngrediente':

            verificaMetodoHttp("PUT");
            $response = modificaIngrediente(recuperaParametroJsonBody("nome"), recuperaParametroJsonBody("prezzoRiferimento"), recuperaParametroGet("idIngrediente"));
            http_response_code(200);
            exit(null);

        case 'eliminaIngrediente':

            verificaMetodoHttp("DELETE");
            eliminaIngrediente(recuperaParametroGet("idIngrediente"));
            http_response_code(200);
            exit(null);

        case 'getIngrediente':

            verificaMetodoHttp("GET");
            $response = getIngrediente(recuperaParametroGet("idIngrediente"));
            http_response_code(200);
            exit(json_encode($response));

        case 'associaIngredientePietanza':

            verificaMetodoHttp("PUT");
            $response = associaIngredientePietanza(recuperaParametroGet("idIngrediente"), recuperaParametroGet("idPietanza"));
            http_response_code(200);
            exit(null);

        case 'dissociaIngredientePietanza':

            verificaMetodoHttp("PUT");
            $response = dissociaIngredientePietanza(recuperaParametroGet("idIngrediente"), recuperaParametroGet("idPietanza"));
            http_response_code(200);
            exit(null);
            
        case 'getIngredientiByPietanza':

            verificaMetodoHttp("GET");
            $response = getIngredientiByPietanza(recuperaParametroGet("idPietanza"));
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
<?php

include './importManager.php';
include '../services/recuperoPasswordService.php';

include '../services/webHookTelegramService.php';



try {

    switch ($_GET["nomeMetodo"]) {

        case 'getMetodiRecuperoPasswordSupportati':

            verificaMetodoHttp("POST");
            $response = getMetodiRecuperoPasswordSupportati(recuperaParametroJsonBody("email"));
            http_response_code(200);
            exit(json_encode($response));

        case 'effettuaRichiestaRecuperoPassword':

            verificaMetodoHttp("POST");
            $response = effettuaRichiestaRecuperoPassword(recuperaParametroJsonBody("email"), recuperaParametroJsonBody("tipoRecuperoPassword"));
            http_response_code(200);
            exit(json_encode($response));

        case 'confermaRecuperoPassword':

            verificaMetodoHttp("POST");

            if (recuperaParametroJsonBody("nuovaPassowrd") != recuperaParametroJsonBody("confermaNuovaPassword"))
                throw new OtterGuardianException(400, "Le password non corrispondono");

            confermaRecuperoPassword(recuperaParametroJsonBody("idRecPsw"), recuperaParametroJsonBody("codice"), recuperaParametroJsonBody("nuovaPassowrd"));
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

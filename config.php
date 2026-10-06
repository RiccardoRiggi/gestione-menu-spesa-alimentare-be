<?php

/*
Abilita modalità per eseguire l'import delle configurazioni di default del db
*/
if (!defined("ABILITA_MODALITA_IMPORT_DB"))
    define("ABILITA_MODALITA_IMPORT_DB", true);

//Informazioni per collegare il db
if (!defined("HOST_DATABASE"))
    define("HOST_DATABASE", "localhost");
if (!defined("NOME_DATABASE"))
    define("NOME_DATABASE", "menu-spesa-alimentare-db");
if (!defined("USERNAME_DATABASE"))
    define("USERNAME_DATABASE", "root");
if (!defined("PASSWORD_DATABASE"))
    define("PASSWORD_DATABASE", "");

/*
Prefisso delle tavole, in questo modo è possibile avere più installazioni su un unico db. 
Dovrai cambiare i prefissi dalle tavole manualmente, il default è "au"
*/
if (!defined("PREFISSO_TAVOLA"))
    define("PREFISSO_TAVOLA", "au");

if (!defined("NOME_APPLICAZIONE"))
    define("NOME_APPLICAZIONE", "Menu Spesa Alimentare");

/*
Questa variabile contiene il numero di elementi che verranno restituiti per ogni pagina nelle liste in cui è implementata
la paginazione
*/
if (!defined("ELEMENTI_PER_PAGINA"))
    define("ELEMENTI_PER_PAGINA", 10);

/*
Questa variabile di configurazione abilita la verifica del token e degli eventuali permessi per accedere a determinate risorse.
Non impostarlo a false per nessun motivo se stai utilizzando il software su rete pubblica
*/
if (!defined("ABILITA_VERIFICA_TOKEN"))
    define("ABILITA_VERIFICA_TOKEN", true);

/*
Questa variabile di configurazione abilita la verifica del pre token per invocare i primi servizi di autenticazione.
Non impostarlo a false per nessun motivo se stai utilizzando il software su rete pubblica
*/
if (!defined("ABILITA_VERIFICA_PRE_TOKEN"))
    define("ABILITA_VERIFICA_PRE_TOKEN", true);

/*
Questa variabile di configurazione abilita il controllo dello stesso indirizzo ip con il quale è stato generato il token    
*/
if (!defined("ABILITA_VERIFICA_STESSO_INDIRIZZO_IP"))
    define("ABILITA_VERIFICA_STESSO_INDIRIZZO_IP", false);

/*
Questa variabile di configurazione abilita il controllo dello stesso User Agent con il quale è stato generato il token    
*/
if (!defined("ABILITA_VERIFICA_STESSO_USER_AGENT"))
    define("ABILITA_VERIFICA_STESSO_USER_AGENT", false); //METTERE A TRUE

/*
Questa variabile serve per abilitare i cors se FE e BE si trovano su host differenti
*/
if (!defined("ABILITA_CORS"))
    define("ABILITA_CORS", false); //METTERE A FALSE

/*
Questa variabile serve per abilitare l'invio delle email
*/
if (!defined("ABILITA_INVIO_EMAIL"))
    define("ABILITA_INVIO_EMAIL", false);

/*
Questa variabile serve per abilitare anche il log su file
*/
if (!defined("ABILITA_LOG_FILE"))
    define("ABILITA_LOG_FILE", true);

//Informazioni per la cifratura, da non cambiare dopo l'installazione
if (!defined("CIPHERING_VALUE"))
    define("CIPHERING_VALUE", "AES-128-CTR");
if (!defined("CIPHERING_OPTIONS"))
    define("CIPHERING_OPTIONS", 0);
if (!defined("ENCRYPTION_IV_VALUE"))
    define("ENCRYPTION_IV_VALUE", "");
if (!defined("ENCRYPTION_KEY"))
    define("ENCRYPTION_KEY", "");

//Informazioni per la cifratura pre token, da non cambiare dopo l'installazione
if (!defined("CIPHERING_VALUE_PRE_TOKEN"))
    define("CIPHERING_VALUE_PRE_TOKEN", "AES-128-CTR");
if (!defined("CIPHERING_OPTIONS_PRE_TOKEN"))
    define("CIPHERING_OPTIONS_PRE_TOKEN", 0);
if (!defined("ENCRYPTION_IV_VALUE_PRE_TOKEN"))
    define("ENCRYPTION_IV_VALUE_PRE_TOKEN", "");
if (!defined("ENCRYPTION_KEY_PRE_TOKEN"))
    define("ENCRYPTION_KEY_PRE_TOKEN", "");
if (!defined("TOTP_GLOBALE"))
    define("TOTP_GLOBALE", "");



/*
Variabili per la gestione del BOT Telegram
*/
if (!defined("NOME_BOT_TELEGRAM"))
    define("NOME_BOT_TELEGRAM", "");
if (!defined("TOKEN_TELEGRAM"))
    define("TOKEN_TELEGRAM", "");

/*
Variabili per ONE SIGNAL notifiche
*/
if (!defined("ONE_SIGNAL_APP_ID"))
    define("ONE_SIGNAL_APP_ID", "");
if (!defined("ONE_SIGNAL_API_KEY"))
    define("ONE_SIGNAL_API_KEY", "");
if (!defined("ONE_SIGNAL_URL_ICONA"))
    define("ONE_SIGNAL_URL_ICONA", "");
if (!defined("ONE_SIGNAL_URL_DESTINAZIONE"))
    define("ONE_SIGNAL_URL_DESTINAZIONE", "");
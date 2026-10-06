<?php

const ARRAY_RISULTATO_FILTRATO_PIETANZE = array("idMenuAlimentare", "idTipoPasto", "idPietanza", "dataPasto", "noteMenuAlimentare", "nomeTipoPasto", "nomePietanza", "notePietanza");
const NOME_TABELLA_PIETANZE = "menu_alimentare";
const PRIMARY_KEY_PIETANZE = "idMenuAlimentare";

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getMenuAlimentariCronJob
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getMenuAlimentariCronJob')) {
    function getMenuAlimentariCronJob($dataInizio, $dataFine)
    {

        $sql = "SELECT t.idTipoPasto, p.idPietanza, m.idMenuAlimentare, m.dataPasto, m.note AS noteMenuAlimentare, t.nome AS nomeTipoPasto, t.ordine, p.nome AS nomePietanza, p.note AS notePietanza FROM " . PREFISSO_TAVOLA . "_menu_alimentare m JOIN " . PREFISSO_TAVOLA . "_t_pasti t ON m.idTipoPasto = t.idTipoPasto JOIN " . PREFISSO_TAVOLA . "_pietanze p ON m.idPietanza = p.idPietanza WHERE m.dataEliminazione IS NULL AND m.dataPasto BETWEEN :dataInizio AND :dataFine ORDER BY t.ordine";
        generaLogSuFile($sql);

        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dataInizio', $dataInizio);
        $stmt->bindParam(':dataFine', $dataFine);

        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);

        return restituisciOggettoFiltrato($result, ARRAY_RISULTATO_FILTRATO_PIETANZE);
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getMenuAlimentari
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getMenuAlimentari')) {
    function getMenuAlimentari($dataInizio, $dataFine)
    {
        verificaValiditaToken();


        $sql = "SELECT t.idTipoPasto, p.idPietanza, m.idMenuAlimentare, m.dataPasto, m.note AS noteMenuAlimentare, t.nome AS nomeTipoPasto, t.ordine, p.nome AS nomePietanza, p.note AS notePietanza FROM " . PREFISSO_TAVOLA . "_menu_alimentare m JOIN " . PREFISSO_TAVOLA . "_t_pasti t ON m.idTipoPasto = t.idTipoPasto JOIN " . PREFISSO_TAVOLA . "_pietanze p ON m.idPietanza = p.idPietanza WHERE m.dataEliminazione IS NULL AND m.dataPasto BETWEEN :dataInizio AND :dataFine ORDER BY t.ordine";
        generaLogSuFile($sql);

        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dataInizio', $dataInizio);
        $stmt->bindParam(':dataFine', $dataFine);

        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);

        return restituisciOggettoFiltrato($result, ARRAY_RISULTATO_FILTRATO_PIETANZE);
    }
}


/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: inserisciMenuAlimentare
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('inserisciMenuAlimentare')) {
    function inserisciMenuAlimentare($idTipoPasto, $dataPasto, $idPietanza, $note)
    {
        verificaValiditaToken();

        $sql = "INSERT INTO " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " (idTipoPasto, dataPasto, idPietanza, note, dataCreazione) VALUES (:idTipoPasto, :dataPasto, :idPietanza, :note ,current_timestamp)";


        $conn = apriConnessione();

        $stmt = $conn->prepare($sql);
        generaLogSuFile($dataPasto);
        $stmt->bindParam(':idTipoPasto', $idTipoPasto);
        $stmt->bindParam(':dataPasto', $dataPasto);
        $stmt->bindParam(':idPietanza', $idPietanza);
        $stmt->bindParam(':note', $note);
        $stmt->execute();
        $id = $conn->lastInsertId();
        chiudiConnessione($conn);
        return $id;
    }
}


/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: modificaMenuAlimentare
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('modificaMenuAlimentare')) {
    function modificaMenuAlimentare($idTipoPasto, $dataPasto, $idPietanza, $note, $idPrimaryKey)
    {
        verificaValiditaToken();

        $sql = "UPDATE " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " SET idTipoPasto= :idTipoPasto , dataPasto= :dataPasto , idPietanza= :idPietanza , note= :note WHERE " . PRIMARY_KEY_PIETANZE . " = :" . PRIMARY_KEY_PIETANZE . " AND dataEliminazione IS NULL";

        $conn = apriConnessione();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idTipoPasto', $idTipoPasto);
        $stmt->bindParam(':dataPasto', $dataPasto);
        $stmt->bindParam(':idPietanza', $idPietanza);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(":" . PRIMARY_KEY_PIETANZE, $idPrimaryKey);
        $stmt->execute();
        chiudiConnessione($conn);
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: eliminaMenuAlimentare
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/


if (!function_exists('eliminaMenuAlimentare')) {
    function eliminaMenuAlimentare($idPrimaryKey)
    {
        verificaValiditaToken();

        $sql = "UPDATE " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " SET dataEliminazione = current_timestamp WHERE " . PRIMARY_KEY_PIETANZE . " = :" . PRIMARY_KEY_PIETANZE . " AND dataEliminazione IS NULL";

        $conn = apriConnessione();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":" . PRIMARY_KEY_PIETANZE, $idPrimaryKey);
        $stmt->execute();

        chiudiConnessione($conn);
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getMenuAlimentare
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getMenuAlimentare')) {
    function getMenuAlimentare($idPrimaryKey)
    {
        verificaValiditaToken();

        $sql = "SELECT t.idTipoPasto, p.idPietanza, m.idMenuAlimentare, m.dataPasto, m.note AS noteMenuAlimentare, t.nome AS nomeTipoPasto, t.ordine, p.nome AS nomePietanza, p.note AS notePietanza FROM " . PREFISSO_TAVOLA . "_menu_alimentare m JOIN " . PREFISSO_TAVOLA . "_t_pasti t ON m.idTipoPasto = t.idTipoPasto JOIN " . PREFISSO_TAVOLA . "_pietanze p ON m.idPietanza = p.idPietanza WHERE m.dataEliminazione IS NULL AND " . PRIMARY_KEY_PIETANZE . " = :" . PRIMARY_KEY_PIETANZE . "";

        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":" . PRIMARY_KEY_PIETANZE, $idPrimaryKey);
        $stmt->execute();
        $result = $stmt->fetch();
        chiudiConnessione($conn);
        return restituisciOggettoFiltrato($result, ARRAY_RISULTATO_FILTRATO_PIETANZE);
    }
}



/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getUltimeDateSomministrazionePietanza
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getUltimeDateSomministrazionePietanza')) {
    function getUltimeDateSomministrazionePietanza($idPrimaryKey)
    {
        verificaValiditaToken();

        $sql = "SELECT * FROM " . PREFISSO_TAVOLA . "_menu_alimentare WHERE " . PRIMARY_KEY_PIETANZE . " = :" . PRIMARY_KEY_PIETANZE . " AND dataEliminazione IS NULL ORDER BY dataPasto DESC LIMIT 10";

        generaLogSuFile($sql);

        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":" . PRIMARY_KEY_PIETANZE, $idPrimaryKey);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);

        return restituisciOggettoFiltrato($result, array("idPietanza", "dataPasto", "note"));
    }
}
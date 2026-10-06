<?php

const ARRAY_RISULTATO_FILTRATO_PIETANZE = array("idPietanza", "nome", "note", "dataCreazione");
const NOME_TABELLA_PIETANZE = "pietanze";
const PRIMARY_KEY_PIETANZE = "idPietanza";

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getPietanze
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getPietanze')) {
    function getPietanze($pagina)
    {
        verificaValiditaToken();
        $paginaDaEstrarre = ($pagina - 1) * ELEMENTI_PER_PAGINA;


        $sql = "SELECT p.idPietanza, p.nome, p.note, p.dataCreazione, p.dataEliminazione FROM " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " p WHERE p.dataEliminazione IS NULL ORDER BY p.nome LIMIT :pagina, " . ELEMENTI_PER_PAGINA;

        generaLogSuFile($sql);

        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':pagina', $paginaDaEstrarre, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);

        return restituisciOggettoFiltrato($result, ARRAY_RISULTATO_FILTRATO_PIETANZE);
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getPietanzeNoPaginate
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getPietanzeNoPaginate')) {
    function getPietanzeNoPaginate()
    {
        verificaValiditaToken();


        $sql = "SELECT p.idPietanza, p.nome, p.note, p.dataCreazione, p.dataEliminazione FROM " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " p WHERE p.dataEliminazione IS NULL ORDER BY p.nome ";

        generaLogSuFile($sql);

        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);

        return restituisciOggettoFiltrato($result, ARRAY_RISULTATO_FILTRATO_PIETANZE);
    }
}


/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: inserisciPietanza
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('inserisciPietanza')) {
    function inserisciPietanza($nome, $note)
    {
        verificaValiditaToken();

        $sql = "INSERT INTO " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " (nome, note, dataCreazione) VALUES (:nome, :note ,current_timestamp)";


        $conn = apriConnessione();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':note', $note);
        $stmt->execute();
        $id = $conn->lastInsertId();
        chiudiConnessione($conn);
        return $id;
    }
}


/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: modificaPietanza
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('modificaPietanza')) {
    function modificaPietanza($nome, $note, $idPrimaryKey)
    {
        verificaValiditaToken();

        $sql = "UPDATE " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " SET nome= :nome ,note= :note WHERE " . PRIMARY_KEY_PIETANZE . " = :" . PRIMARY_KEY_PIETANZE . " AND dataEliminazione IS NULL";

        $conn = apriConnessione();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(":" . PRIMARY_KEY_PIETANZE, $idPrimaryKey);
        $stmt->execute();
        chiudiConnessione($conn);
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: eliminaPietanza
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/


if (!function_exists('eliminaPietanza')) {
    function eliminaPietanza($idPrimaryKey)
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
Funzione: getPietanza
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getPietanza')) {
    function getPietanza($idPrimaryKey)
    {
        verificaValiditaToken();

        $sql = "SELECT p.idPietanza, p.nome, p.note, p.dataCreazione, p.dataEliminazione FROM " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " p WHERE p." . PRIMARY_KEY_PIETANZE . " = :" . PRIMARY_KEY_PIETANZE . " AND  p.dataEliminazione IS NULL";

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

        return restituisciOggettoFiltrato($result, array("idPietanza", "dataPasto","note"));
    }
}
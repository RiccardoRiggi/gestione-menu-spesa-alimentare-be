<?php

const ARRAY_RISULTATO_FILTRATO_PIETANZE = array("idSpesa", "idIngrediente", "nomeIngrediente", "dataSpesa", "dataAcquisto", "note", "prezzoRiferimento");
const NOME_TABELLA_PIETANZE = "spesa";
const PRIMARY_KEY_PIETANZE = "idSpesa";

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getListaSpese
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getListaSpese')) {
    function getListaSpese($pagina)
    {
        verificaValiditaToken();
        $paginaDaEstrarre = ($pagina - 1) * ELEMENTI_PER_PAGINA;


        $sql = "SELECT DISTINCT(dataSpesa) dataSpesa FROM " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " WHERE dataEliminazione IS NULL ORDER BY dataSpesa DESC LIMIT :pagina, " . ELEMENTI_PER_PAGINA;

        generaLogSuFile($sql);

        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':pagina', $paginaDaEstrarre, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);

        return restituisciOggettoFiltrato($result, array("dataSpesa"));
    }
}


/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: inserisciSpesa
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('inserisciSpesa')) {
    function inserisciSpesa($idIngrediente, $dataSpesa, $note)
    {
        verificaValiditaToken();

        $sql = "INSERT INTO " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " (idIngrediente, dataSpesa,  note, dataCreazione) VALUES (:idIngrediente, :dataSpesa,  :note, current_timestamp)";


        $conn = apriConnessione();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idIngrediente', $idIngrediente);
        $stmt->bindParam(':dataSpesa', $dataSpesa);
        $stmt->bindParam(':note', $note);
        $stmt->execute();
        $id = $conn->lastInsertId();
        chiudiConnessione($conn);
        return $id;
    }
}


/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: modificaSpesa
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('modificaSpesa')) {
    function modificaSpesa($idIngrediente, $dataSpesa, $dataAcquisto, $note, $idPrimaryKey)
    {
        verificaValiditaToken();


        if ($dataAcquisto === false) {
            $sql = "UPDATE " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " SET idIngrediente= :idIngrediente , dataSpesa= :dataSpesa, dataAcquisto= null, note= :note WHERE " . PRIMARY_KEY_PIETANZE . " = :" . PRIMARY_KEY_PIETANZE . " AND dataEliminazione IS NULL";
        } else {
            $sql = "UPDATE " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " SET idIngrediente= :idIngrediente , dataSpesa= :dataSpesa, dataAcquisto= :dataAcquisto, note= :note WHERE " . PRIMARY_KEY_PIETANZE . " = :" . PRIMARY_KEY_PIETANZE . " AND dataEliminazione IS NULL";
        }
        $conn = apriConnessione();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idIngrediente', $idIngrediente);
        if ($dataAcquisto !== false) {
            $stmt->bindParam(':dataAcquisto', $dataAcquisto);
        }
        $stmt->bindParam(':dataSpesa', $dataSpesa);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(":" . PRIMARY_KEY_PIETANZE, $idPrimaryKey);
        $stmt->execute();
        chiudiConnessione($conn);
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: eliminaSpesa
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/


if (!function_exists('eliminaSpesa')) {
    function eliminaSpesa($idPrimaryKey)
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
Funzione: getListaSpesa
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getListaSpesa')) {
    function getListaSpesa($dataSpesa)
    {
        verificaValiditaToken();

        $sql = "SELECT s.idSpesa, s.idIngrediente, s.dataSpesa, s.dataAcquisto, s.note, i.nome nomeIngrediente, i.prezzoRiferimento FROM " . PREFISSO_TAVOLA . "_spesa s JOIN " . PREFISSO_TAVOLA . "_ingredienti i on s.idIngrediente = i.idIngrediente WHERE i.dataEliminazione IS NULL AND s.dataEliminazione IS NULL AND  dataSpesa = :dataSpesa ";

        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":dataSpesa", $dataSpesa);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);
        return restituisciOggettoFiltrato($result, ARRAY_RISULTATO_FILTRATO_PIETANZE);
    }
}


/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getIngredientiDaComprare
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getIngredientiDaComprare')) {
    function getIngredientiDaComprare($dataInizio, $dataFine)
    {
        verificaValiditaToken();


        $sql = "SELECT ma.note, ma.idTipoPasto, t.nome nomePasto, i.nome, i.idIngrediente, i.prezzoRiferimento FROM " . PREFISSO_TAVOLA . "_t_pasti t, " . PREFISSO_TAVOLA . "_pietanze p, " . PREFISSO_TAVOLA . "_menu_alimentare ma, " . PREFISSO_TAVOLA . "_ingredienti i, " . PREFISSO_TAVOLA . "_ingrediente_pietanza ip WHERE ma.idPietanza = p.idPietanza AND t.idTipoPasto = ma.idTipoPasto AND p.idPietanza = ip.idPietanza AND i.idIngrediente = ip.idIngrediente AND p.dataEliminazione IS NULL and ma.dataEliminazione IS NULL and i.dataEliminazione  IS NULL AND ma.dataPasto BETWEEN :dataInizio AND :dataFine ORDER BY i.nome";
        generaLogSuFile($sql);


        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dataInizio', $dataInizio);
        $stmt->bindParam(':dataFine', $dataFine);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);


        return restituisciOggettoFiltrato($result, array("note","idTipoPasto","nomePasto","nome","idIngrediente","prezzoRiferimento"));

    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getDataUltimoAcquisto
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getDataUltimoAcquisto')) {
    function getDataUltimoAcquisto($idIngrediente)
    {
        verificaValiditaToken();

        $sql = "SELECT dataSpesa FROM " . PREFISSO_TAVOLA . "_spesa WHERE idIngrediente = :idIngrediente ORDER BY dataSpesa DESC limit 1";

        generaLogSuFile($sql);


        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idIngrediente', $idIngrediente);
        $stmt->execute();
        $result = $stmt->fetch();
        chiudiConnessione($conn);



        return restituisciOggettoFiltrato($result, array("dataSpesa"));
    }
}
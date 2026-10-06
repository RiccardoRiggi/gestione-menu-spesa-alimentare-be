<?php

const ARRAY_RISULTATO_FILTRATO_PIETANZE = array("idIngrediente", "nome", "prezzoRiferimento", "dataCreazione");
const NOME_TABELLA_PIETANZE = "ingredienti";
const PRIMARY_KEY_PIETANZE = "idIngrediente";

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getIngredienti
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getIngredienti')) {
    function getIngredienti($pagina)
    {
        verificaValiditaToken();
        $paginaDaEstrarre = ($pagina - 1) * ELEMENTI_PER_PAGINA;


        $sql = "SELECT * FROM " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " WHERE dataEliminazione IS NULL ORDER BY nome LIMIT :pagina, " . ELEMENTI_PER_PAGINA;

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
Funzione: inserisciIngrediente
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('inserisciIngrediente')) {
    function inserisciIngrediente($nome, $prezzoRiferimento)
    {
        verificaValiditaToken();

        $sql = "INSERT INTO " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " (nome, prezzoRiferimento, dataCreazione) VALUES (:nome, :prezzoRiferimento, current_timestamp)";


        $conn = apriConnessione();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':prezzoRiferimento', $prezzoRiferimento);
        $stmt->execute();
        $id = $conn->lastInsertId();
        chiudiConnessione($conn);
        return $id;
    }
}


/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: modificaIngrediente
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('modificaIngrediente')) {
    function modificaIngrediente($nome, $prezzoRiferimento, $idPrimaryKey)
    {
        verificaValiditaToken();

        $sql = "UPDATE " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " SET nome= :nome , prezzoRiferimento= :prezzoRiferimento WHERE " . PRIMARY_KEY_PIETANZE . " = :" . PRIMARY_KEY_PIETANZE . " AND dataEliminazione IS NULL";

        $conn = apriConnessione();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':prezzoRiferimento', $prezzoRiferimento);
        $stmt->bindParam(":" . PRIMARY_KEY_PIETANZE, $idPrimaryKey);
        $stmt->execute();
        chiudiConnessione($conn);
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: eliminaMenuAlimentare
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/


if (!function_exists('eliminaIngrediente')) {
    function eliminaIngrediente($idPrimaryKey)
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
Funzione: getIngrediente
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getIngrediente')) {
    function getIngrediente($idPrimaryKey)
    {
        verificaValiditaToken();

        $sql = "SELECT * FROM " . PREFISSO_TAVOLA . "_" . NOME_TABELLA_PIETANZE . " WHERE dataEliminazione IS NULL AND " . PRIMARY_KEY_PIETANZE . " = :" . PRIMARY_KEY_PIETANZE . "";

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
Funzione: associaIngredientePietanza
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('associaIngredientePietanza')) {
    function associaIngredientePietanza($idIngrediente, $idPietanza)
    {
        verificaValiditaToken();

        $sql = "INSERT INTO " . PREFISSO_TAVOLA . "_ingrediente_pietanza (idIngrediente, idPietanza) VALUES (:idIngrediente, :idPietanza )";

        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idIngrediente', $idIngrediente);
        $stmt->bindParam(':idPietanza', $idPietanza);
        $stmt->execute();
        chiudiConnessione($conn);
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: dissociaIngredientePietanza
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/


if (!function_exists('dissociaIngredientePietanza')) {
    function dissociaIngredientePietanza($idIngrediente, $idPietanza)
    {
        verificaValiditaToken();

        $sql = "DELETE FROM " . PREFISSO_TAVOLA . "_ingrediente_pietanza WHERE idIngrediente = :idIngrediente AND idPietanza = :idPietanza";

        $conn = apriConnessione();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idIngrediente', $idIngrediente);
        $stmt->bindParam(':idPietanza', $idPietanza);
        $stmt->execute();
        chiudiConnessione($conn);
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getIngredientiByPietanza
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getIngredientiByPietanza')) {
    function getIngredientiByPietanza($idPietanza)
    {
        verificaValiditaToken();


        $sql = "SELECT i.idIngrediente, i.nome, prezzoRiferimento, CASE 
        WHEN ip.idPietanza IS NOT NULL THEN 'S'
        ELSE 'N'
    END AS usatoNellaPietanza  FROM " . PREFISSO_TAVOLA . "_ingredienti i LEFT JOIN " . PREFISSO_TAVOLA . "_ingrediente_pietanza ip ON i.idIngrediente = ip.idIngrediente AND idPietanza = :idPietanza WHERE i.dataEliminazione IS NULL ORDER BY i.nome";

        generaLogSuFile($sql);
        generaLogSuFile($idPietanza);


        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idPietanza', $idPietanza);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);



        return restituisciOggettoFiltrato($result, array("idIngrediente", "nome", "prezzoRiferimento", "usatoNellaPietanza"));
    }
}
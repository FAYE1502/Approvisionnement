<?php

function connexionDB():PDO{

try {
    $pdo = new PDO(
        "pgsql:host=localhost;dbname=approvisonnement;port=5432",
        "postgres",
        "postgres"
    );

    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    return $pdo;
} catch ( PDOException $ex) {
    die('Erreur:'.$ex->getMessage());
}
    

}

function query(PDO $pdo,string $sql, bool $single = true):array{
     $query = $pdo->query($sql);
    return $single ? $query->fetch():$query->fetchAll();
    
}

function prepare(PDO $pdo,string $sql, array $datas) {
    $prepare = $pdo->prepare($sql);
    $prepare->execute($datas);
    return $prepare;
}

function executeQuery(PDO $pdo,string $sql, array $datas, bool $single = true) : array {
    prepare($pdo, $sql,  $datas);
   
    return $single ? $statement->fetch():$statement->fetchAll();
}

function executeUpdate(PDO $pdo, string $sql, array $datas) : int {
    prepare($pdo, $sql,  $datas);
    
    return (str_starts_with(strtoupper($sql), 'INSERT'))  ? $pdo->lastInsertId() : $prepare->rowCount();
}
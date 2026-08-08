<?php
function getRapprochementEntrer():array{
        // require_once dirname(__DIR__)."/core/database.php";

    $pdo = connexionDB();
    $sql = "SELECT ap.rfBL, concat('#BL-',ap.rfBL) AS ref,f.nom,
COALESCE(sum(l.prixAchatReel*l.qteAppro),0) as valeurFacture,
COALESCE(sum(l.prixAchatReel*l.qteRecue),0) as valeurRecept,
-- (COALESCE(sum(l.prixAchatReel*l.qteRecue) - COALESCE(sum(l.prixAchatReel*l.qteRecue),0)) as calcul_ecart,
CASE 
    WHEN COALESCE(sum(l.prixAchatReel*l.qteAppro),0) = COALESCE(sum(l.prixAchatReel*l.qteRecue),0) THEN 'CONCORDE'
    ELSE COALESCE(sum(l.prixAchatReel*l.qteRecue), 0) - COALESCE(sum(l.prixAchatReel*l.qteAppro),0)  || ' ' || 'ECART'
END AS color
FROM approvisionnements ap
LEFT JOIN  fournisseurs f on ap.fournisseur_id = f.id
INNER JOIN ligneAppro l on l.approvisionnement_id = ap.id
GROUP BY ap.rfBL,f.nom";
$dattas = query($pdo, $sql, false);
    //require_once dirname(__DIR__). "/views/approvisionnement.view.html.php";
    

$pdo=null;
// echo '<pre>';
// var_dump($datas);
// echo '</pre>';
// die;
return $dattas;
    
}

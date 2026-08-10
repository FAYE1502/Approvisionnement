<?php
function getRapprochementEntrer():array{
        // require_once dirname(__DIR__)."/core/database.php";

    $pdo = connexionDB();
    $sql = "SELECT '#BL-' || ' ' || f.nom || ' ' || '-0' || ' ' || ap.rfBL AS ref,f.nom,
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
function getNiveauxStocksApprovisionnementDirect(int $max=5):array{
    $pdo = connexionDB();
    $sql = "SELECT 
    a.libelle,f.nom,a.qteStock,
CASE 
    WHEN a.qteStock=0 THEN 'danger'
    ELSE  'warning'
END AS colors
FROM articles a
INNER JOIN fournisseurs f ON a.fournisseur_id = f.id
WHERE qteStock <=:max
ORDER BY a.libelle,a.qteStock DESC";

$niveaus = executeQuery($pdo, $sql,['max' => $max], false ); 
$pdo = null;
return $niveaus;
}
function BordereauxivraisonRéception():array{
    $pdo = connexionDB();
    $sql ="SELECT 
    ap.id AS approvisionnement_id,
    ap.rfBL,
    CONCAT('#BL-0', ap.rfBL, ' • ', TO_CHAR(ap.date, 'DD MM YYYY')) AS reference_date,
    f.nom ,
  
    COALESCE(SUM(l.prixAchatReel * l.qteAppro), 0) AS total_valeur,
    
    COUNT(l.id) AS nb_articles,
   
    CASE 
        WHEN COALESCE(SUM(l.prixAchatReel * l.qteAppro), 0) = COALESCE(SUM(l.prixAchatReel * l.qteRecue), 0) THEN 'receptionne'
        ELSE 'encours'
    END AS status_code,

    CASE 
        WHEN COALESCE(SUM(l.prixAchatReel * l.qteAppro), 0) = COALESCE(SUM(l.prixAchatReel * l.qteRecue), 0) THEN 'RÉCEPTIONNÉ'
        ELSE 'EN COURS'
    END AS status_label

FROM approvisionnements ap
INNER JOIN fournisseurs f ON ap.fournisseur_id = f.id
INNER JOIN ligneAppro l ON l.approvisionnement_id = ap.id
GROUP BY ap.id, ap.rfBL, ap.date, f.nom
ORDER BY ap.date DESC;";
$borders = query($pdo,$sql, false) ?? [];

$sql1 = "SELECT l.id,a.libelle,l.qteAppro,
COALESCE(l.qteRecue,0) as qte_recue,
COALESCE(l.prixAchatReel,0) as prix_achat
FROM ligneAppro l
INNER JOIN articles a ON l.article_id=a.id
WHERE l.approvisionnement_id = :approvisionnement_id";

if (!empty($borders) && is_array($borders)) {
        foreach ($borders as &$border) {
            $border['details'] = executeQuery(
                $pdo,
                $sql1,
                ['approvisionnement_id' => $border['approvisionnement_id']],
                false
            ) ?? [];
        }
    }

$pdo = null;

return $borders;
}



function enregistreEtAugmentationStock():array{
    $pdo=$pdo = connexionDB();
    $sql= "SELECT a.id,f.id,f.nom,a.libelle || ' ' || '(' || ' '  || a.prixAchat || ' ' || ')' as produits,a.qteStock
FROM fournisseurs f
INNER JOIN articles a ON a.fournisseur_id=f.id
";
$stocks = query($pdo,$sql,false);
$pdo = null;
return $stocks;
}




function EnregistrerFournisseur(array $datas):int {
    $pdo = connexionDB();
    $sql="INSERT INTO fournisseurs(nom,telephone,adresse)
    VALUES
    (':nom',':telephone',':adresse')
    ";
    $fournisseurs = executeUpdate( $pdo, $sql,[
        'nom'=>$datas['nom'],
        telephone=>$datas['telephone'],
        'adresse'=>$datas['adresse']
    ]);
}
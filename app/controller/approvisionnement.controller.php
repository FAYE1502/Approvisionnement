<?php
require_once dirname(__DIR__). "/models/approvisionnement.model.php";
function affichages(){

    $dattas = getRapprochementEntrer();
    $niveaus = getNiveauxStocksApprovisionnementDirect();
    $borders = BordereauxivraisonRéception();
    $stocks = enregistreEtAugmentationStock();

    // echo '<pre>';
    // var_dump($datas);
    // echo '</pre>';
    // die();
    
renderView("approvisionnement.view.html.php",
[
    'dattas' => $dattas,
    'niveaus' => $niveaus,
    'borders' => $borders,
    'stocks' =>$stocks
    ]
    );
}
//  echo "ujfhghf";
function renderView(string $file,array $datas){
    extract($datas);
    require_once dirname(__DIR__) . "/views/$file";
}
//  function enregistrement(){
//     if($_SERVER['REQUEST_METHODE']===$_POST && !isset($_POST['action_'])){
//         $data=[
//             'nom'=>$_POST['nom'],
//             telephone=>$_POST['telephone'],
//             'adresse'=>$_POST['adresse']

//         ];
//     }
//  }

  function augmentationStickt(){
    if($_SERVER['REQUEST_METHODE']==='POST'){
        $stocks = enregistreEtAugmentationStock();
        
 }
 }

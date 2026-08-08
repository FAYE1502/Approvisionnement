<?php
require_once dirname(__DIR__). "/models/approvisionnement.model.php";
function affichages(){

    $dattas = getRapprochementEntrer();

    // echo '<pre>';
    // var_dump($datas);
    // echo '</pre>';
    // die();
    
renderView("approvisionnement.view.html.php",['dattas'=>$dattas]);
}
//  echo "ujfhghf";
function renderView(string $file,array $datas){
    extract($datas);
    require_once dirname(__DIR__) . "/views/$file";
}
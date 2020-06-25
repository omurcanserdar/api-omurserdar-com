<?php
include "../../db.php";
include "../../fonksiyonlar.php";
$jsonArray = array(); 
$jsonArray["hata"] = FALSE; 
 
$httpKOD = 200; 
$istekMOD = $_SERVER["REQUEST_METHOD"];
//print_r(json_decode(file_get_contents("php://input"), true));

if($istekMOD=="GET"){
    // parse_str(file_get_contents("php://input"),$veriler);
    if(isset($_GET["bid"]) && !empty(trim($_GET["bid"]))){
        $sepetsayisor=$db->prepare("select count(envanter_id) as sayi from sepet where bireysel_id=:pbid");
        $sepetsayisor->execute(array("pbid"=>$_GET["bid"]));
        $sepetsayicek=$sepetsayisor->fetch();
        if($sepetsayicek)
            $jsonArray["sayi"] = $sepetsayicek["sayi"];
    }
    else {
     $httpKOD = 400;
     $jsonArray["hata"] = TRUE; 
     $jsonArray["hataMesaj"] = "kullanici id gönder";
 }
}





SetHeader($httpKOD);
$jsonArray[$httpKOD] = HttpStatus($httpKOD);
echo json_encode($jsonArray);
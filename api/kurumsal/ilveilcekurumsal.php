<?php
include "../../db.php";
include "../../fonksiyonlar.php";
$jsonArray = array(); // son olarak json_encode işlemi yapılacak . 
$jsonArray["hata"] = FALSE; // Başlangıçta hata yok olarak kabul edelim. 
 
$httpKOD = 200; // HTTP Ok olarak durumu kabul edelim. 
$istekMOD = $_SERVER["REQUEST_METHOD"]; // istemciden gelen istek
//print_r(json_decode(file_get_contents("php://input"), true));

if($istekMOD=="GET"){
  if(isset($_GET["ilid"]) && !empty(trim($_GET["ilid"]))){
        $ilegorebilgiler=$db->prepare("SELECT kurumsal.*,il.il_adi,ilce.ilce_adi FROM il,ilce,kurumsal 
        where il.id=ilce.il_id 
        AND kurumsal.il_id=il.id 
        AND kurumsal.ilce_id=ilce.id
        AND il.id=:pilid
        GROUP BY kurumsal.ad");
        
        $ilegorebilgiler->execute(array('pilid'=>$_GET["ilid"]));
        if($ilegorebilgiler->rowCount()>0){
            /*
            $iladi=$sorgum->fetch(PDO::FETCH_ASSOC);
        	    echo "<div class='row'><p class='lead'><span class='badge badge-primary'>".$iladi['il_adi']."</span>
        	    il bilgisine göre işletmeler görüntülenir (İlçeniz <span class='badge badge-primary'>".$kume['ilce_adi']."</span> )</p></div>";
            */
            $jsonArray["ilegorebilgiler"]=array();
            while($vericek=$ilegorebilgiler->fetch(PDO::FETCH_ASSOC)){
                $kid=$vericek["id"];
                $url = "https://api.omurserdar.com/api/kurumsal/index.php?id=$kid";
                $json = file_get_contents($url);
                $jsonverilerim = json_decode($json, true);
                array_push($jsonArray["ilegorebilgiler"],$jsonverilerim);
            }
            $httpKOD = 200;
            $jsonArray["hata"] = false;
            $jsonArray["sayi"]=$ilegorebilgiler->rowCount();
        }else {
                 $httpKOD = 200;
                 $jsonArray["hata"] = false;
                 $jsonArray["sayi"] = 0;
                    }
    }
    else {
     $httpKOD = 400;
     $jsonArray["hata"] = TRUE; 
     $jsonArray["hataMesaj"] = "il id gönder";
 }
}


SetHeader($httpKOD);
$jsonArray[$httpKOD] = HttpStatus($httpKOD);
echo json_encode($jsonArray);

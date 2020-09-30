<?php
include "../../db.php";
include "../../fonksiyonlar.php";
//session_start();
$jsonArray = array(); 
$jsonArray["hata"] = FALSE; 
 
$httpKOD = 200; 
$istekMOD = $_SERVER["REQUEST_METHOD"];
//print_r(json_decode(file_get_contents("php://input"), true));

sesYoksaCik("kullanici_tip","bireysel");

$url=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; //www.api.omurserdar.com/api/favori/25
$id=explode("/",$url)[3];
settype($id,"integer");

if($istekMOD=="GET"){
    
    if(isset($id)&& $id>0){
        $favsor=$db->prepare("SELECT favori.*,envanter.ad as envad FROM bireysel,favori,envanter
WHERE bireysel.id=favori.bireysel_id 
AND envanter.id=favori.envanter_id
AND favori.id=?");
        $favsor->execute([$id]);
        if($favsor->rowCount()>0){
            
            $favcek=$favsor->fetch(PDO::FETCH_ASSOC);
            
            if($_SESSION["kullanici_id"]!=$favcek["bireysel_id"]){
                $httpKOD=401;
                $jsonArray["mesaj"]="size ait kayıt değil";
                
            }
            else{
                $jsonArray["favbilgiler"] = $favcek;
            }
        }
        else{
            $httpKOD = 404; //204-no content-> herhangi bir çıktı sunulmuyor
            $jsonArray["mesaj"] = "kayıt yok";
        }
    }
    else{
        $httpKOD = 404;
        $jsonArray["mesaj"] = "geçersiz ID";
    }
    
}

elseif($istekMOD=="POST") {

$url=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; //www.api.omurserdar.com/api/favori/25
$id=explode("/",$url)[3];
settype($id,"integer");

    //daha önce envantere bireysel favori yapmış mı
    $sorgu=$db->prepare("SELECT COUNT(id) as varmi FROM favori where bireysel_id=? AND envanter_id=?");
    $sorgu->execute(array($_POST["birid"],$_POST["envid"]));
    $cek=$sorgu->fetch(PDO::FETCH_ASSOC);
    
    //favori yoksa
    if($cek["varmi"]==0){
$favekle=$db->prepare("INSERT INTO favori set bireysel_id=?,envanter_id=?");
$favekle->execute(array($_POST["birid"],$_POST["envid"]));

 $favsay=$favekle->rowCount();
		 if($favsay==1){
		     $httpKOD = 201;
             $jsonArray["mesaj"] = "eklendi";
		 }
		 else{
		     $httpKOD = 200;
             $jsonArray["hata"] = TRUE;
             $jsonArray["mesaj"] = "eklenemedi";
		 }
    }//favori varsa
    else{
             $httpKOD = 200;
             $jsonArray["hata"] = TRUE; // bir hata olduğu bildirilsin.
             $jsonArray["mesaj"] = "mevcut";
    }
    
}

elseif($istekMOD=="DELETE"){
    
    $url=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; //www.api.omurserdar.com/api/favori/25
    $envanterid=explode("/",$url)[3];
    settype($envanterid,"integer");
             
    $favsor=$db->prepare("SELECT favori.id as fid FROM bireysel,favori,envanter
WHERE bireysel.id=favori.bireysel_id 
AND envanter.id=favori.envanter_id
AND bireysel.id=? AND envanter.id=?");
    
    $favsor->execute([$_SESSION["kullanici_id"],$envanterid]);
    $favcek=$favsor->fetch(PDO::FETCH_ASSOC);
        if($favsor->rowCount()>0){
            
                $favid=$favcek["fid"];
                
                $db->beginTransaction();
                $sil=$db->prepare("DELETE FROM favori WHERE id=?");
                $sil->execute([$favid]);
                if($sil){
                    $db->commit();
                    $httpKOD = 200; //204-no content-> herhangi bir çıktı sunulmuyor
                    $jsonArray["mesaj"] = "silindi";
                }
                else{
                    $db->rollBack();
                    $httpKOD = 200; 
                    $jsonArray["mesaj"] = "rollback";
                }
                
        }
        //favori yoksa
        else{
            $httpKOD = 404; //204-no content-> herhangi bir çıktı sunulmuyor
            $jsonArray["mesaj"] = "kayıt yok";
        }
}
    /*
    else{
        $httpKOD = 404;
        $jsonArray["mesaj"] = "geçersiz ID";
    }
    */




baslikAyarlaJSONyaz($httpKOD,$jsonArray);
?>
<?php
include "../../db.php";
include "../../fonksiyonlar.php";
$jsonArray = array(); // son olarak json_encode işlemi yapılacak . 
$jsonArray["hata"] = FALSE; // Başlangıçta hata yok olarak kabul edelim. 
 
$httpKOD = 200; // HTTP Ok olarak durumu kabul edelim. 
$istekMOD = $_SERVER["REQUEST_METHOD"]; // istemciden gelen istek
//print_r(json_decode(file_get_contents("php://input"), true));

if($istekMOD=="POST") {


    //kullanici adi veya email kayıtlarda varsa kullaniciVar mesajı dönsün ve işlem yapılsın
    $sorgu=$db->prepare("SELECT COUNT(id) as varmi FROM kurumsal where email=:pmail or kullaniciadi=:pkul");
    $sorgu->execute(array("pmail" => $_POST["email"],"pkul" => $_POST["kullaniciadi"]));
    $cek=$sorgu->fetch(PDO::FETCH_ASSOC);
    //kullanici db de yoksa
    if($cek["varmi"]==0){
$kurekle=$db->prepare("INSERT INTO kurumsal set il_id=:pil,ilce_id=:pilce,ad=:pad,kullaniciadi=:pkulad,email=:pmail,sifre=:psif,adres=:padres,ceptel=:pcep");
 $kurekle->execute(array('pil' => $_POST["il"],"pilce" => $_POST["ilce"],
 "pad" => $_POST["ad"],"pkulad" => $_POST["kullaniciadi"],
 "pmail" => $_POST["email"],"psif" => md5($_POST["sifre"]),
 "padres" => $_POST["adres"],"pcep" => $_POST["ceptel"]));

 $kursay=$kurekle->rowCount();
		 if($kursay==1){
		     $httpKOD = 201;
             $jsonArray["mesaj"] = "eklendi";
		 }
		 else{
		     $httpKOD = 400;
             $jsonArray["hata"] = TRUE;
             $jsonArray["mesaj"] = "eklenemedi";
		 }
    }//kullanici dbde varsa
    else{
             $httpKOD = 200;
             $jsonArray["hata"] = TRUE; // bir hata olduğu bildirilsin.
             $jsonArray["mesaj"] = "kullanicizatenmevcut";
    }
}

else if($istekMOD=="PUT") {
    $gelenler=json_decode(file_get_contents("php://input"));
        if($db->query("select * from kurumsal where id='$gelenler->id'")->rowCount()==0){
             $httpKOD = 400;
             $jsonArray["hata"] = TRUE;
             $jsonArray["mesaj"] = "kurumsal bulunamadı";
             }else{
                 $kurguncelle=$db->prepare("UPDATE kurumsal SET adres=:padres,minAlimTutar=:pmin,acikMi=:pacik WHERE id=:pid");
                 $kurguncelle->execute(array(
                 "padres" => $gelenler->adres,
                 "pmin" => intval($gelenler->min),
                 "pacik" => $gelenler->acik,
                 "pid" => $gelenler->id
                 ));
                 // güncelleme başarılı
                 if($kurguncelle->rowCount()>0){
                 $httpKOD = 200;
                 $jsonArray["mesaj"] = "güncellendi";
                 }
                 else {
                 // güncelleme başarısız
                 $httpKOD = 400;
                 $jsonArray["hata"] = TRUE;
                 $jsonArray["mesaj"] = "güncellenmedi";
                 }
 }
}
else if($istekMOD=="DELETE") {
       // parse_str(file_get_contents("php://input"),$veriler);
       // $bid=$veriler["id"];

}


else if($istekMOD=="GET"){
  if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $kid=$_GET["id"];
    $kurVarMi = $db->query("select * from kurumsal where id='$kid'");
    if($kurVarMi->rowCount()>0){
        $kurbilgiler=$db->query("select kurumsal.*,il.il_adi,ilce.ilce_adi from kurumsal,il,ilce where kurumsal.il_id=il.id 
            and kurumsal.ilce_id=ilce.id
            and ilce.il_id=il.id 
            and kurumsal.id='$kid'")->fetch(PDO::FETCH_ASSOC);
            $jsonArray["kurumsalbilgileri"] = $kurbilgiler;
            unset($jsonArray["kurumsalbilgileri"]["sifre"]);
            
            $tabAdveSayi=$db->prepare("select tabMenu.id,tabMenu.ad,COUNT(tabMenu.id) as envsayi FROM envanter,tabMenu,kurumsal
             WHERE envanter.tabMenu_id=tabMenu.id AND 
             tabMenu.kurumsal_id=kurumsal.id AND 
             kurumsal.id=?
             GROUP BY tabMenu.ad
             ORDER BY tabMenu.id");
            $tabAdveSayi->execute(array($kid));
  
 $jsonArray["kurumsalTumTab"]=array();
 $jsonArray["kurumsalTab"]=array();
 $jsonArray["kurumsalEnv"]=array();
 $jsonArray["kurumsalBosTab"]=array();
    
    $bul = $db->prepare("select * from tabMenu where kurumsal_id=:pkulid");
	$bul->execute(['pkulid' => $kid]); 
	while($row = $bul->fetch(PDO::FETCH_ASSOC)){
	    $sayisor=$db->prepare("SELECT count(envanter.id) as tabaitenvsayi FROM envanter,tabMenu where tabMenu.id=:ptid and envanter.tabMenu_id=tabMenu.id and tabMenu.kurumsal_id=$kid");
	    $sayisor->execute(['ptid' => $row["id"]]);
	    $sayicek=$sayisor->fetch();//tabaitenvsayi
	    
		$kurtumtabeklenecek=array("id"=>$row["id"],"ad"=>$row["ad"],"sayi"=>$sayicek[0]);
		array_push($jsonArray["kurumsalTumTab"],$kurtumtabeklenecek);
	}
		
	$bul2 = $db->prepare("SELECT tabMenu.id,tabMenu.ad FROM tabMenu WHERE tabMenu.id NOT IN (SELECT envanter.tabMenu_id FROM envanter ) AND tabMenu.kurumsal_id=:pkulid");
	$bul2->execute(['pkulid' => $kid]); 
	while($row2 = $bul2->fetch(PDO::FETCH_ASSOC)){
		$kurbostabeklenecek=array("id"=>$row2["id"],"ad"=>$row2["ad"]);
		array_push($jsonArray["kurumsalBosTab"],$kurbostabeklenecek);
	}
    
    while($cek=$tabAdveSayi->fetch(PDO::FETCH_ASSOC)){
        $ptmid=$cek["id"];$ptmad=$cek["ad"];$ptmes=$cek["envsayi"];
        $kurumsaltabeklenecek=array("id"=>$ptmid,"ad"=>$ptmad,"sayi"=>$ptmes);
        array_push($jsonArray["kurumsalTab"],$kurumsaltabeklenecek);
        
        $tabenv=$db->prepare("select envanter.ad,envanter.tanim,fiyat,alinabilirMi,envanter.id FROM envanter,tabMenu,kurumsal
        WHERE envanter.tabMenu_id=tabMenu.id AND
        tabMenu.kurumsal_id=kurumsal.id AND
        kurumsal.id=?
        and tabMenu.id=?");
        $tabenv->execute(array($kid,$ptmid));
        while($cekenv=$tabenv->fetch(PDO::FETCH_ASSOC)){
            $tid=$cek["id"]; $tad=$cek["ad"]; $ead=$cekenv["ad"]; 
            $etan=$cekenv["tanim"]; $efi=$cekenv["fiyat"]; 
            $eal=$cekenv["alinabilirMi"]; $envid=$cekenv["id"];
            $kurumsalenveklenecek=array("tabid"=>$tid,"tabad"=>$tad,"ad"=>$ead,"tanim"=>$etan,"fiyat"=>$efi,"alim"=>$eal,"envanterid"=>$envid );
            array_push($jsonArray["kurumsalEnv"],$kurumsalenveklenecek);
            }
      }
///$jsonArray["kurumsalTabVerilerim"] = json_encode($f,JSON_FORCE_OBJECT);
             $httpKOD = 200;
             }else {
                 $httpKOD = 400;
                 $jsonArray["hata"] = TRUE;
                 $jsonArray["hataMesaj"] = "Üye bulunamadı";
                    }
    }
    else {
     $httpKOD = 400;
     $jsonArray["hata"] = TRUE; 
     $jsonArray["hataMesaj"] = "kurumsal id gönder";
 }
}





SetHeader($httpKOD);
$jsonArray[$httpKOD] = HttpStatus($httpKOD);
echo json_encode($jsonArray);







?>
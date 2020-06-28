<?php
session_start();
include "db.php";
$btn=strip_tags($_POST['secim']);

if($_SESSION["kullanici_tip"]!="bireysel"){
    echo "yetkiniz yok"; //yetki hata sayfasına yönlendirilebilir
    exit;
}

$birid=$_SESSION['kullanici_id'];

if($btn=="btnsepetEkle"){ 
    //sepette giriş yapan kullanıcıya ait en az bir ürün varsa
    if(isset($_POST["id"])&& !empty($_POST["id"])){
        $id=strip_tags($_POST["id"]);
    }
    else{
        echo "envyok";
        exit;
    }
    //sepetteki tüm k_id leri cek
        $sepetsor=$db->prepare("select * from sepet where bireysel_id=:pbid");
        $sepetsor->execute(array('pbid'=>$birid));
        //envanter bilgilerini al
        $url = "https://api.omurserdar.com/api/envanter?id=$id";
        $json = file_get_contents($url);
        $jsonverilerim = json_decode($json, true);   
        $kume=$jsonverilerim["envanterBilgi"];
        //envanter bilgi al son

        if($sepetsor->rowCount()>0){
           $kiddizi=array(); //kullanıcının sepetindeki kurumsal_id ici
           while($sepetcek=$sepetsor->fetch(PDO::FETCH_ASSOC)){
               array_push($kiddizi,$sepetcek["kurumsal_id"]);
           }
           //kullanıcının sepetindeki kurumsallar bir diziye eklendi
           
           //aynı envid sepet sorgu 
           $envsayisor=$db->prepare("select count(id) as envsayi from sepet where bireysel_id=:pbid and envanter_id=:penvid");
           $envsayisor->execute(array("pbid"=>$birid,"penvid"=>$id));
           $envsayicek=$envsayisor->fetch(PDO::FETCH_ASSOC);
           $envsayi=$envsayicek["envsayi"];
           //aynı envid sepet sorgu son
           
           //aynı envid sepette varsa arttir
	        if($envsayi>0){
                    $sepetadetguncelle=$db->prepare("update sepet set adet=adet+1 where bireysel_id=:pbir and envanter_id=:penv");
                    $sepetadetguncelle->execute(array('pbir'=>$birid,'penv'=>$id));
                    //$_SESSION["sepet"][$sayac]["fiyat"]=$birimFiyat*$_SESSION["sepet"][$sayac]["adet"];
                    if($sepetadetguncelle->rowCount()==1)
                        echo "arti";
                    exit;
    	        }
    	  elseif($envsayi==0&&in_array($kume["kurumsal_id"],$kiddizi)==true){ //aynı env yoksa dogrudan ekle
    	      $sepetenvekle=$db->prepare("INSERT INTO sepet set bireysel_id=:pbid,kurumsal_id=:pkid,envanter_id=:penvid");
              $sepetenvekle->execute(array('pbid'=>$birid,'pkid' => $kume["kurumsal_id"],"penvid" => $id));
                if($sepetenvekle->rowCount()==1)
                    echo "eklendi";
                 exit;
    	  }
    	  elseif(in_array($kume["kurumsal_id"],$kiddizi)==false){
    	      echo "farkli";
    	      exit;
    	  }
        }
	else{ //kullanıcıya ait sepette env yok ise
                $sepetenvekle=$db->prepare("INSERT INTO sepet set bireysel_id=:pbid,kurumsal_id=:pkid,envanter_id=:penvid");
                $sepetenvekle->execute(array('pbid'=>$birid,'pkid' => $kume["kurumsal_id"],"penvid" => $id));
                if($sepetenvekle->rowCount()==1)
                    echo "eklendi";
                else
                    echo $sepetenvekle->debugDumpParams();
                 exit;
	}
}

elseif($btn=="btnSepetEnvKaldir"){
    $kaldircevap=array();
    $kaldirilacakEnvId=$_POST["envid"];

    $sepetsor=$db->prepare("select * from sepet where bireysel_id=:pbid");
    $sepetsor->execute(array('pbid'=>$birid));
    $sepetenvsayisi=$sepetsor->rowCount();
    if($sepetenvsayisi>0){
        while($sepetcek=$sepetsor->fetch(PDO::FETCH_ASSOC)){
            if($kaldirilacakEnvId==$sepetcek["envanter_id"]){
                //env fiyatını al
                $url = "https://api.omurserdar.com/api/envanter?id=$kaldirilacakEnvId";
                $json = file_get_contents($url);
                $jsonverilerim = json_decode($json, true);   
                $envfiyat=$jsonverilerim["envanterBilgi"]["fiyat"];
                //son env fiyat
            //$silinecekTutar=floatval($val["fiyat"]);
            //unset($_SESSION["sepet"][$select]);
                $silsepetEnv=$db->prepare("DELETE FROM sepet WHERE id=:psepid");
                $silsepetEnv->execute(array('psepid'=>$sepetcek["id"]));
                if($silsepetEnv->rowCount()==1){
                    $kaldircevap["sepetkalanenvsayi"]=$sepetenvsayisi-1;
                    $kaldircevap["cevap"]="silindi";
                    $kaldircevap["silinenTutar"]=floatval($sepetcek["adet"]*floatval($envfiyat));
                }
                else{
                    $kaldircevap["cevap"]="silinemedi";
                    }
                }
            }
        }
    echo json_encode($kaldircevap);
    exit;  
}


if(isset($_GET["secim"]) && $_GET["secim"]=="btnSepetim"){
    try{
    if(isset($_SESSION["kullanici_tip"])&&$_SESSION["kullanici_tip"]=="bireysel"){
	   $top=0;$k_min=0;
	$sepetsor=$db->prepare("select * from sepet where bireysel_id=:pbid");
    $sepetsor->execute(array('pbid'=>$birid));
	   
	$response.="<center>";
	
	$alinamazenvsayac=0;
	while($sepetcek=$sepetsor->fetch(PDO::FETCH_ASSOC)){
	    $id=$sepetcek["envanter_id"];
        //sepetteki her bir ürün için envanter bilgilerini al
        $url = "https://api.omurserdar.com/api/envanter?id=$id";
        $json = file_get_contents($url);
        $jsonverilerim = json_decode($json, true);   
        $kume=$jsonverilerim["envanterBilgi"];
           
        //envanterküme boş değilse
        if(!empty($kume)){
            
             //envanterküme kurumsal_id değerine ulaş ve kurumsal bilgilerini getir
           $idk=$kume['kurumsal_id'];
           $url2 = "https://api.omurserdar.com/api/kurumsal?id=$idk";
           $json2 = file_get_contents($url2);
           $jsonverilerim2 = json_decode($json2, true);   
           $kurBilgi=$jsonverilerim2["kurumsalbilgileri"];
            //kurumsal.id==envanter.kurumsal_id ise
            if($kurBilgi["id"]==$kume['kurumsal_id']){
                if($kume["alinabilirMi"]==0){
                    $renv="danger";
                    $sipenvdurumyazi="envanter şu anda alınamaz durumda, <b><i>sipariş verebilmek için envanteri sepetten kaldır ya da alınabilir durumda olmasını bekle</i></b>";
                    $alinamazenvsayac++;
                }
                else{
                    $renv="success";
                    $sipenvdurumyazi="";
                }
                
                $ad=$kurBilgi["ad"];
                $response.='<div class="card border-'.$renv.' mb-3 text-center" sepetenvid="'.$kume["id"].'" id="cardsepenv'.$kume["id"].'" style="max-width: 20rem;">
                <div class="card-body">
                 <span class="badge badge-success">'.$kume["ad"].'</span><hr>
                 <span data-id="'.$kume["fiyat"].'" class="efiyat'.$kume["id"].' badge badge-info">her biri '.$kume["fiyat"].' &#8378; </span>
                 <span data-id="'.$sepetcek["adet"].'" class="eadet'.$kume["id"].' badge badge-warning">'.$sepetcek["adet"].' adet</span>
                 <span data-id="'.$sepetcek["adet"]*$kume["fiyat"].'" class="etutar'.$kume["id"].' badge badge-danger">toplam tutar '.$sepetcek["adet"]*$kume["fiyat"].' &#8378; </span>
                 <span class="text-warning">'.$sipenvdurumyazi.'</span><hr/>
                 <button type="button" class="btnSepetEnvKaldir btn btn-outline-danger btn-block card-link" id="btnsepkal'.$kume["id"].'" data-id="'.$kume["id"].'"><i class="fas fa-trash"></i> Sepetimden Kaldır </button>
                </div>
                </div>';
                $top+=$sepetcek["adet"]*$kume["fiyat"];
              }
            } 
	     }
	     $k_min=$kurBilgi["minAlimTutar"];
	     $nekadar=$k_min-$top;
	     $response.='<span limit="'.$k_min.'" data-id="'.$top.'" class="toptutar badge badge-info" id="'.$top.'">tutar : '.$top.' &#8378; </span>';
	     /* if($top<$k_min){
	          $response.='<span class="badge badge-danger">sepetinde en az '.$nekadar.' &#8378; tutarında daha ürün(ler) bulunmalı </span>';
	     } */
	     if($top!=0 && $top>=$k_min && $kurBilgi["acikMi"]!=0 && $alinamazenvsayac==0 ){
	         $response.='<button type="button" class="btnSiparisVer btn btn-primary btn-lg btn-block mt-4"><i class="fas fa-check-circle"></i> SEPETİ ONAYLA </button>';
	     }
	     if($kurBilgi["acikMi"]==0){
	         $response.="<div><span class='lead'>Kurumsal şu anda hizmet dışı</span></div>";
	     }
	     
	     
	      $response.="</center>";
	     
$dizim=array();
$dizim["cevap"]=$response;
$dizim["sayi"]=$sepetsor->rowCount();
$dizim["minalim"]=$k_min;
echo json_encode($dizim);
	    }
      }

      catch (Exception $e){
$dizim=array();
$dizim["cevap"]=$e->getMessage();
$dizim["sayi"]=$sepetsor->rowCount();
echo json_encode($dizim);
      }
}

?>
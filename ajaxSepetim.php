<?php
session_start();
include "db.php";
$btn=strip_tags($_POST['secim']);

if($_SESSION["kullanici_tip"]!="bireysel"){
    echo "yetkiniz yok"; //yetki hata sayfasına yönlendirilebilir
    exit;
}

$birid=$_SESSION['kullanici_id'];


//SEPET EKLE 
if($btn=="btnsepetEkle"){ 
    //sepette giriş yapan kullanıcıya ait en az bir ürün varsa
    if(isset($_POST["id"])&& !empty($_POST["id"])){
        $id=strip_tags($_POST["id"]);
    }
    else{
        echo "post edilen envanter yok!!!";
        exit;
    }
    
    //sepetteki tüm k_id leri cek
        $sepetsor=$db->prepare("select * from sepet where bireysel_id=:pbid");
        $sepetsor->execute(array('pbid'=>$birid));
        
    //envanter bilgilerini al
        $url = "https://api.omurserdar.com/api/envanter?id=$id";
        $json = file_get_contents($url);
        $jsonverilerim = json_decode($json, true);   
        $tblenvkume=$jsonverilerim["envanterBilgi"];
    //envanter bilgi al son

        if($sepetsor->rowCount()>0){
           $kiddizi=array(); //kullanıcının sepetindeki kurumsal_id ici
           
           while($sepetcek=$sepetsor->fetch(PDO::FETCH_ASSOC)){
               array_push($kiddizi,$sepetcek["kurumsal_id"]);
           }
           //kullanıcının sepetindeki kurumsallar bir diziye eklendi
           
           //aynı envid sepet sorgu 
           $envsayisor=$db->prepare("select count(id) as envsayi,adet from sepet where bireysel_id=:pbid and envanter_id=:penvid");
           $envsayisor->execute(array("pbid"=>$birid,"penvid"=>$id));
           $envsayicek=$envsayisor->fetch(PDO::FETCH_ASSOC);
           $envsayi=$envsayicek["envsayi"];
           //aynı envid sepet sorgu son
           
           //aynı envid sepette varsa arttir
	        if($envsayi>0){
	            
	            if($envsayicek["adet"]==5){
	                echo "limit";
	                exit;
	            }
	            
                    $sepetadetguncelle=$db->prepare("update sepet set adet=adet+1 where bireysel_id=:pbir and envanter_id=:penv");
                    $sepetadetguncelle->execute(array('pbir'=>$birid,'penv'=>$id));
                    //$_SESSION["sepet"][$sayac]["fiyat"]=$birimFiyat*$_SESSION["sepet"][$sayac]["adet"];
                    if($sepetadetguncelle->rowCount()==1)
                        echo "arti";
                    exit;
    	        }
    	        
    	        //aynı env yoksa dogrudan ekle
    	  elseif($envsayi==0&&in_array($tblenvkume["kurumsal_id"],$kiddizi)==true){
    	      $sepetenvekle=$db->prepare("INSERT INTO sepet set bireysel_id=:pbid,kurumsal_id=:pkid,envanter_id=:penvid");
              $sepetenvekle->execute(array('pbid'=>$birid,'pkid' => $tblenvkume["kurumsal_id"],"penvid" => $id));
                if($sepetenvekle->rowCount()==1)
                    echo "eklendi";
                 exit;
    	  }
    	  
    	  elseif(in_array($tblenvkume["kurumsal_id"],$kiddizi)==false){
    	      echo "farkli";
    	      exit;
    	  }
        }
	else{ //kullanıcıya ait sepette env yok ise
                $sepetenvekle=$db->prepare("INSERT INTO sepet set bireysel_id=:pbid,kurumsal_id=:pkid,envanter_id=:penvid");
                $sepetenvekle->execute(array('pbid'=>$birid,'pkid' => $tblenvkume["kurumsal_id"],"penvid" => $id));
                if($sepetenvekle->rowCount()==1)
                    echo "eklendi";
                else
                    echo $sepetenvekle->debugDumpParams();
                 exit;
	}
}

//SON SEPETEKLE


//SEPETİMDEN KALDIR

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

//SON SEPETİMDEN KALDIR

//SEPETİMDEN 1 AZALT
elseif($btn=="btnSepetEnvAzalt"){
    
    $mesaj="";
    $azaltEnvId=strip_tags($_POST["envid"]);
    
    $sepetsor=$db->prepare("select * from sepet where envanter_id=:psepenvid and bireysel_id=:pbid");
    $sepetsor->execute(array(':psepenvid'=>$azaltEnvId,':pbid'=>$birid));
    $sepetenvsayisi=$sepetsor->rowCount();
    
    if($sepetenvsayisi>0){
        $sepetcek=$sepetsor->fetch(PDO::FETCH_ASSOC);
            if($azaltEnvId==$sepetcek["envanter_id"]){
                
                //env fiyatını al
                $url = "https://api.omurserdar.com/api/envanter?id=$azaltEnvId";
                $json = file_get_contents($url);
                $jsonverilerim = json_decode($json, true);   
                
                //$envfiyat=$jsonverilerim["envanterBilgi"]["fiyat"];
                //son env fiyat
                //$silinecekTutar=floatval($val["fiyat"]);
                //unset($_SESSION["sepet"][$select]);
                
                $guncelleEnv=$db->prepare("UPDATE sepet SET adet=adet-1 WHERE id=:psepid AND envanter_id=:psepenvid");
                $guncelleEnv->execute(array(':psepid'=>$sepetcek["id"],':psepenvid'=>$azaltEnvId));
                
                if($guncelleEnv->rowCount()==1){
                    //$azaltcevap["sepetkalanenvsayi"]=$sepetenvsayisi-1;
                    $mesaj="azaldi";
                    //$azaltcevap["silinenTutar"]=floatval($sepetcek["adet"]*floatval($envfiyat));
                }
                else{
                $mesaj="islembasarisiz";
                }
            }
        }
        else{
            $mesaj="envanter sayısı 0dan küçük";
            exit;
        }
    echo $mesaj;
    exit;  
}
//SON SEPETİMDEN 1 AZALT

//SEPETİM

elseif($_GET["secim"]=="btnSepetim"){
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
        $tblenvkume=$jsonverilerim["envanterBilgi"];
           
        //envanterküme boş değilse
        if(!empty($tblenvkume)){
            
             //envanterküme kurumsal_id değerine ulaş ve kurumsal bilgilerini getir
           $idk=$tblenvkume['kurumsal_id'];
           $url2 = "https://api.omurserdar.com/api/kurumsal?id=$idk";
           $json2 = file_get_contents($url2);
           $jsonverilerim2 = json_decode($json2, true);   
           $kurBilgi=$jsonverilerim2["kurumsalbilgileri"];
            //kurumsal.id==envanter.kurumsal_id ise
            $kurumsal=$kurBilgi["ad"];
            
            if($kurBilgi["id"]==$tblenvkume['kurumsal_id']){
                if($tblenvkume["alinabilirMi"]==0){
                    $renv="danger";
                    $sipenvdurumyazi="envanter şu anda alınamaz durumda, <b><i>sipariş verebilmek için envanteri sepetten kaldır ya da alınabilir durumda olmasını bekle</i></b>";
                    $alinamazenvsayac++;
                }
                else{
                    $renv="success";
                    $sipenvdurumyazi="";
                }
                
                $ad=$kurBilgi["ad"];
                $response.='<div class="card border-'.$renv.' mb-3 text-center" sepetenvid="'.$tblenvkume["id"].'" id="cardsepenv'.$tblenvkume["id"].'" style="max-width: 20rem;">
                <div class="card-body">';
                
                if($sepetcek["adet"]>1){
                    $response.='<button type="button" class="btnAdetAzalt btn btn-outline-primary btn-sm card-link float-left" id="btnadetazalt'.$tblenvkume["id"].'" data-id="'.$tblenvkume["id"].'" alt="1 adet azalt" title="1 adet azalt"><i class="fas fa-minus"></i> </button>';
                }
                 $response.='<span class="badge badge-success">'.$tblenvkume["ad"].'</span>
                 
                 <button type="button" class="btnAdetArttir btn btn-outline-primary btn-sm card-link float-right" id="'.$tblenvkume["id"].'" data-id="'.$tblenvkume["id"].'" alt="1 adet arttır" title="1 adet arttır"><i class="fas fa-plus"></i> </button>
                 
                 <hr>
                 <span data-id="'.$tblenvkume["fiyat"].'" class="efiyat'.$tblenvkume["id"].' badge badge-info">her biri '.$tblenvkume["fiyat"].' &#8378; </span>
                 <span data-id="'.$sepetcek["adet"].'" class="eadet'.$tblenvkume["id"].' badge badge-warning">'.$sepetcek["adet"].' adet</span>
                 <span data-id="'.$sepetcek["adet"]*$tblenvkume["fiyat"].'" class="etutar'.$tblenvkume["id"].' badge badge-danger">toplam tutar '.$sepetcek["adet"]*$tblenvkume["fiyat"].' &#8378; </span>
                 <span class="text-warning">'.$sipenvdurumyazi.'</span><hr/>
                 <button type="button" class="btnSepetEnvKaldir btn btn-outline-danger btn-block card-link" id="btnsepkal'.$tblenvkume["id"].'" data-id="'.$tblenvkume["id"].'"><i class="fas fa-trash"></i> Sepetimden Kaldır </button>
                </div>
                </div>';
                $top+=$sepetcek["adet"]*$tblenvkume["fiyat"];
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
$dizim["kurumsal"]=$kurumsal;
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

//SON SEPETİM

?>
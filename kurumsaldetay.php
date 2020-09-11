<?php
ob_start();



 include "db.php";
 

    $urlm=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; //url cek
    $dizim=explode("/",$urlm); // url parcala(/'a gore')
	$urlusername=strip_tags($dizim[2]);
	
	$usernamesor=$db->query("select * from kurumsal where kullaniciadi='$urlusername'");
	
	if($usernamesor){
	    $cek=$usernamesor->fetchAll();
	    $title="Kurumsal Detay - ".$cek[0]["ad"]." | API";
        $desc=$cek[0]["ad"]." kurumsal üyenin ulaşım, değerlendirme, sipariş, envanter, menü ve daha birçok bilgileri ile istatistiklerine bu sayfadan erişilebilir";
	}

	include "header.php";
	
	if($usernamesor->rowCount()>0){

	   $sor=$db->query("select id from kurumsal where kullaniciadi='$urlusername'");
	   $cek=$sor->fetch(PDO::FETCH_ASSOC);
	   $id=$cek["id"];
	   
	   $url = "https://api.omurserdar.com/api/kurumsal/index.php?id=$id";
       $json = file_get_contents($url);
       $jsonverilerim = json_decode($json, true);
	   
	   
$kumekurbilgi=$jsonverilerim["kurumsalbilgileri"];
$kumekurbilgikurtab=$jsonverilerim["kurumsalTab"];
$kumekurbilgi_kurenvtab=$jsonverilerim["kurumsalEnv"];




?>



    <div class="container-fluid mt-1">
        <div class="row">
            <div class="col-md-4">
    
            <div class="card bg-light mb-3">
                
              <div class="card-header"><?=$kumekurbilgi["ad"]?> (<?=$kumekurbilgi["kullaniciadi"]?>) 
              
              <button type="button" class="btn btn-outline-primary btn-sm float-right" data-toggle="modal" data-target="#konumModal">
                  <i class="fas fa-map-marker-alt"></i> Haritada Göster
                </button>
              
              <!-- Konum Modal -->
    		        <div class="modal bd-example-modal-lg" id="konumModal" tabindex="-1" role="dialog" aria-labelledby="konumModalLabel">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            
                          <div class="modal-header">
                            <h4 class="modal-title text-info" id="konumModalLabel"><?=$kumekurbilgi["ad"]?> Konum Bilgileri </h4>
                            
                            <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Kapat</button>
                          </div>
                          
                          <div class="modal-body">
                              <div class="col-md-6"> <!-- google maps iframe kullanılmasını zorunlu kılıyor -->
                          		<iframe width="750" height="400" frameborder="0" scrolling="no" 
                          		marginheight="0" marginwidth="0" 
                          		src="https://www.google.com.tr/maps?q=<?=$kumekurbilgi["adres"]; ?> &output=embed"> 
                          		</iframe>
                          		 </div>
                          </div>
                          
                      </div>
                    </div>
    		    </div>   
		    <!-- Konum Modal Son -->
              
              </div>
              
              <div class="card-body">
                  
        <div id="accordion">
            <!-- genel collapse -->
                  <div class="card">
                    <div class="card-header">
                      <a class="card-link" data-toggle="collapse" href="#collapseGenel">
                        Genel Bilgiler <i id="cgi" class="fas fa-chevron-down"></i>
                      </a>
                    </div>
                    <div id="collapseGenel" class="collapse" data-parent="#accordion">
                      <div class="card-body lead text-info">
                        
                <p>E-mail: <?=$kumekurbilgi["email"]?></p>
                <p>Minimum Sepet Tutarı: <?=$kumekurbilgi["minAlimTutar"]?> ₺</p>
                <p>Sipariş Verilebilir Mi: <?=$kumekurbilgi["acikMi"]==1 ? "EVET":"HAYIR"  ?> </p>
                
                <p>Kayıt Tarihi: <?=turkcetarih($kumekurbilgi["kayit_tarihi"])?>  </p>        
                      </div>
                    </div>
                  </div>
            <!-- son genel collapse-->    
                
            <!-- konum collapse -->    
                  <div class="card mt-1">
                    <div class="card-header">
                      <a class="card-link" data-toggle="collapse" href="#collapseKonum">
                        Konum Bilgileri <i id="cki" class="fas fa-chevron-down"></i>
                      </a>
                    </div>
                    <div id="collapseKonum" class="collapse" data-parent="#accordion">
                      <div class="card-body lead text-info">
                        <p>Bölge: <?=$kumekurbilgi["ilce_adi"]." / ".$kumekurbilgi["il_adi"]?> </p>
                        <p>Adres: <?=$kumekurbilgi["adres"]?> </p>
                        
                        <div class="row">
                            
                            <div class="col-md-6 alert alert-info">
                                <p class="mr-1"> Yol tarifi için QR kodu verilmiştir, QR kodunu okutarak yol tarifi alabilirsiniz </p>
                            </div>
                            
                            <div class="col-md-4">
                                <div yol="<?=$kumekurbilgi["adres"]." ".$kumekurbilgi["ilce_adi"]."/".$kumekurbilgi["il_adi"]?>" id="qrcode"></div>
                        
                
                        <script>
                        
                        var qrcode = new QRCode("qrcode");
                       var adres=$("#qrcode").attr("yol");
                        
                        
                        $('#qrcode').qrcode({ 
                            //render : "table",
                            text : "https://www.google.com.tr/maps/dir//"+adres+"/",
                            width: 128,
                            height: 128,
                            colorDark : "#000000",
                            colorLight : "#ffffff",
                            correctLevel : QRCode.CorrectLevel
                            //text : "https://www.google.com.tr/maps/dir//40.95543425212482,29.25795778425504"
                        });
                        
                        </script>
                            </div>
                            
                        </div>
                        
                    
                        
                        
                      </div>
                    </div>
                  </div>
            <!-- son konum collapse -->      
                  
                  
                  <? 
                 //değerlendirme varsa
                    
                if(isset($jsonverilerim["degerlendirme"])){?>
                
                <div class="card mt-1">
                    <div class="card-header">
                      <a class="card-link" data-toggle="collapse" href="#collapseDegerlendirme">
                        Değerlendirme Bilgileri <i id="cdi" class="fas fa-chevron-down"></i>
                      </a>
                    </div>
                    <div id="collapseDegerlendirme" class="collapse" data-parent="#accordion">
                      <div class="card-body lead text-info">
                        
                        <?
                 $varhiz=$jsonverilerim["degerlendirme"]["ortalamahiz"];
    			 $varlezzet=$jsonverilerim["degerlendirme"]["ortalamalezzet"];	
    			 echo '<p>Değerlendirme Sayısı: '.$jsonverilerim["degerlendirme"]["sayi"].'</p>';	
    			echo '<p>Ortalama Hız Puanı: ';	
    			//HIZ İÇİN
				for($i=0; $i<$varhiz; $i++)
					echo '<span><i class="fas fa-star" style="color:#FFA500"></i></span>';
				for($i=0; $i<(5-$varhiz); $i++)
					echo '<span><i class="far fa-star"></i></span>';
				echo " ($varhiz/5) </p>";	
				//SON HIZ İÇİN
							   
				//LEZZET İÇİN
					echo '<p>Ortalama Lezzet Puanı: ';
					for($i=0; $i<$varlezzet; $i++)
						echo '<span><i class="fas fa-star" style="color:#FFA500"></i></span>';
					for($i=0; $i<(5-$varlezzet); $i++)
						echo '<span><i class="far fa-star"></i></span>';
					echo "($varlezzet/5)</p>";
							   //SON LEZZET İÇİN

                ?>
                        
                      </div>
                    </div>
                  </div>
                
                <?
                }
                //son değerlendirme varsa yıldızlasın
                ?>
                
                <!-- siparis collapse -->
                <div class="card mt-1">
                    <div class="card-header">
                      <a class="card-link" data-toggle="collapse" href="#collapseSiparis">
                        Sipariş Bilgileri <i id="csi" class="fas fa-chevron-down"></i>
                      </a>
                    </div>
                    <div id="collapseSiparis" class="collapse" data-parent="#accordion">
                      <div class="card-body lead text-info">
                        
                        <? 
                
                //SİPARİŞ SORGULARI
                $sipSayi=$db->query("SELECT COUNT(*) as spSay FROM siparis,kurumsal WHERE siparis.kurumsal_id=kurumsal.id and kurumsal.id=$id")->fetch(PDO::FETCH_ASSOC);
                if($sipSayi["spSay"]==0){
                    echo "<p> sipariş almamış </p>";
                }
                
                else if($sipSayi["spSay"]>0){
                    
                   //sadece 1 sipariş yapılmışsa
                    if($sipSayi["spSay"]==1){
                        $tekSip=$db->query("SELECT siparisTarih as st FROM siparis,kurumsal WHERE siparis.kurumsal_id=kurumsal.id and kurumsal.id=$id ORDER BY st LIMIT 0,1;")->fetch(PDO::FETCH_ASSOC);
                        echo "<p> Yalnızca 1 sipariş alınmış </p>";
                      
                        echo '<p>Siparişin Alınma Tarihi: '.turkcetarih($tekSip["st"]).'</p>';
                    }
                    //son sadece 1 sipariş yapılmışsa
                
                    //birden fazla sipariş yapılmışsa (ilk ve son sip yaz)
                    else if($sipSayi["spSay"]>1){
                        
                        echo "<p> Toplam Sipariş Sayısı: ".$sipSayi["spSay"]."</p>"?>
                        </p>
                        
                         <p>İlk Alınan Sipariş Tarihi:
                        <?
                        $ilkSip=$db->query("SELECT siparisTarih as st FROM siparis,kurumsal WHERE siparis.kurumsal_id=kurumsal.id and kurumsal.id=$id ORDER BY st LIMIT 0,1;")->fetch(PDO::FETCH_ASSOC);
                        echo turkcetarih($ilkSip["st"]);
                        ?>
                        <p>Son Alınan Sipariş Tarihi:
                        <?
                        $sonSip=$db->query("SELECT siparisTarih as st FROM siparis,kurumsal WHERE siparis.kurumsal_id=kurumsal.id and kurumsal.id=$id ORDER BY st desc LIMIT 0,1;")->fetch(PDO::FETCH_ASSOC);
                        echo turkcetarih($sonSip["st"]);
                        
                    }
                    //son birden fazla sipariş yapılmışsa (ilk ve son sip yaz)
                
                //SON SİPARİŞ SORGULARI
                
                }
                //$kumekurbilgi=$jsonverilerim["kurumsalbilgileri"];
                 ?>
               
                      </div>
                    </div>
                </div>
                <!-- son siparis collapse -->  
            
        </div> <!-- accordion -->
        
        
        	<script type="text/javascript">
$(document).ready(function(){

       $("#collapseGenel").on("hide.bs.collapse", function(){
        $("#cgi").removeClass();
        $("#cgi").addClass("fas fa-chevron-down");
      });
      $("#collapseGenel").on("show.bs.collapse", function(){
        $("#cgi").addClass("fas fa-chevron-up");
      });
      
       $("#collapseKonum").on("hide.bs.collapse", function(){
        $("#cki").removeClass();
        $("#cki").addClass("fas fa-chevron-down");
      });
      $("#collapseKonum").on("show.bs.collapse", function(){
        $("#cki").addClass("fas fa-chevron-up");
      });
      
      $("#collapseDegerlendirme").on("hide.bs.collapse", function(){
        $("#cdi").removeClass();
        $("#cdi").addClass("fas fa-chevron-down");
      });
      $("#collapseDegerlendirme").on("show.bs.collapse", function(){
        $("#cdi").addClass("fas fa-chevron-up");
      });
      
      $("#collapseSiparis").on("hide.bs.collapse", function(){
        $("#csi").removeClass();
        $("#csi").addClass("fas fa-chevron-down");
      });
      $("#collapseSiparis").on("show.bs.collapse", function(){
        $("#csi").addClass("fas fa-chevron-up");
      });
      
    });
</script>
                
      </div>
    </div>
</div><!-- col-md-4-->
    
    
    <?
    if(!empty($kumekurbilgikurtab)){
   ?>
    
    <div class="col-md-8 text-center">
    
        <div class="row">
            
    <?
    $sayac=1;
    
     foreach($kumekurbilgikurtab as $kurbilgikt){
         $menuid=$kurbilgikt["id"];
         ?>
         <div class="col-md-4">
            <div class="ml-1 mb-1 card border-success" id="accordion">
                        
                <div class="card-header">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$sayac?>" id="<?=$menuid?>"><?=$kurbilgikt["ad"]?>
                  <span class="badge badge-primary badge-pill"><?=$kurbilgikt["sayi"]?></span>
                  </a>
                </div>
                            
                <div class="card-body">
                    <div id="collapse<?=$sayac?>" class="panel-collapse collapse in">
                            
                        <div class="panel-body">
                                
         <?
         foreach($kumekurbilgi_kurenvtab as $kurbilgi_ket){
            if($kurbilgikt["id"]==$kurbilgi_ket["tabid"]){
                                    ?>
                            <div class="ml-1 mb-1 card border-dark">
                                
                                <div class="card-body">
                                                    
    <h5 class="card-title"><?=$kurbilgi_ket["ad"]?><span class="badge badge-pill badge-info ml-1"><?=$kurbilgi_ket['fiyat']?> &#8378;</span></h5>
    <?
    if($kurbilgi_ket["tanim"]!=" "){?>
    <h6 class="card-subtitle mb-2 text-muted"><?=$kurbilgi_ket["tanim"]?></h6>
    <?
    } 
    if($kurbilgi_ket["alim"]==1){?>
            <span class='badge badge-pill badge-success'><i class='fas fa-toggle-on'></i> Sipariş Verilebilir </span>
        <?}
        else{
        ?>
        <span class='badge badge-pill badge-danger'><i class='fas fa-toggle-off'></i> Sipariş Verilemez  </span>
        <?
        }
        
        // ÜRÜN SEPET SORGULARI
        $eid=$kurbilgi_ket["envanterid"];

        $envSepet=$db->prepare("select count(*) as sepenvsay from sepet,envanter
where sepet.envanter_id=envanter.id
AND envanter.id=:peid");
        $envSepet->execute(["peid"=>$eid]);
        $cek=$envSepet->fetch(PDO::FETCH_ASSOC);
        if($cek["sepenvsay"]>0){
            
            
            echo '<p>Şu anda <button type="button" class="btn btn-info btn-sm mt-1 text-white" data-toggle="modal" data-target="#envsepMod'.$eid.'">
            '.$cek["sepenvsay"].'</button> kişinin sepetinde</p>';
            
            $callproc=$db->prepare("call sp_envkiminsepetinde(:peid)");
            $callproc->execute(array("peid"=>$eid));
            
            //MODAL
        echo'<div class="modal fade" id="envsepMod'.$eid.'" tabindex="-1" role="dialog" aria-labelledby="envsepMod'.$eid.'Title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="envsepMod'.$eid.'Title">'.$kurbilgi_ket["ad"].' Kimin Sepetinde</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">';
       while($cek=$callproc->fetch(PDO::FETCH_ASSOC)){
           echo $cek["ad"]."<br>";
       }
      echo '</div>
    </div>
  </div>
</div>';
        // SON ÜRÜN SEPET SORGULARI
            
        }
         
       
        ?>
        
                                               </div>
                                           </div>
                                           
                                           <?
 }
}
?>
</div>
</div>
</div>
</div>
</div>
<?
$sayac++;
     } 
     ?>
     </div>
     </div> <!-- col-md-9 -->
<?
}
else{
    echo '<div class="alert alert-warning mt-1 text-center" role="alert">
      <h3> Kurumsala ait menü bulunmamaktadır</h3>
</div>';
}
?>
    </div>
    
    <div class="row">
        
      <? 
                 // ************ sp_kurumsalTabMenuOrtMinMax  ************
                $callproc=$db->prepare("call sp_kurumsalTabMenuOrtMinMax(:pid)");
                $callproc->execute(array("pid"=>$id));
                if($callproc->rowCount()>0){?>
                
                <div class="col-md-4">
                    <ul class="list-group mr-1 mb-1">
                        <li class="list-group-item active"><i class="fas fa-chart-line"></i> İstatistikler <span class="badge badge-light"> <?=$callproc->rowCount()?> </span> Menü</li>
                <?
                while($callcek=$callproc->fetch(PDO::FETCH_ASSOC)){
                 echo '<li class="list-group-item"><span class="badge badge-primary">'.$callcek["ad"].'</span>
                 
                 <span class="badge badge-success ml-1 mt-1">Envanter Sayısı: '.$callcek["ENVANTERSAYISI"].' </span>
                 
                 <span class="badge badge-info ml-1 mt-1">Ortalama Fiyat '.$callcek["ORTALAMAFIYAT"].' ₺</span>
                 
                 <span class="badge badge-warning ml-1 mt-1">En Düşük Fiyat '.$callcek["ENDUSUKFIYAT"].' ₺</span>
                 
                 <span class="badge badge-danger ml-1 mt-1">En Yüksek Fiyat '.$callcek["ENYUKSEKFIYAT"].' ₺</span>
                 
                 </li>';
                }
                }
                // ************ SON sp_kurumsalTabMenuOrtMinMax ************
                ?>
                 </ul>
                </div>  

                <? 
                 // ************ MAX 5 ************
                $callproc=$db->prepare("call sp_max5envanter(:pid)");
                $callproc->execute(array("pid"=>$id));
                if($callproc->rowCount()>0){?>
                
            <div class="col-md-4">    
                <ul class="list-group mr-1 ml-1">
                    <li class="list-group-item active"><i class="fas fa-sort-amount-up"></i> Fiyatı En Yüksek <span class="badge badge-light"> <?=$callproc->rowCount()?> </span> Envanter</li>
                <?
                while($callcek=$callproc->fetch(PDO::FETCH_ASSOC)){
                 echo '<li class="list-group-item"><span class="badge badge-primary mr-1">'.$callcek["ad"].'</span><span class="badge badge-info mr-1"> '.$callcek["menuad"].' </span><span class="badge badge-danger mr-1">'.$callcek["fiyat"].' ₺</span></li>';
                }
                }
                // ************ SON MAX 5 ************
                ?>
                </ul>
            </div>  
                <? 
                 // ************ MIN 5 ************
                $callproc=$db->prepare("call sp_min5envanter(:pid)");
                $callproc->execute(array("pid"=>$id));
                if($callproc->rowCount()>0){?>
              
            <div class="col-md-4">    
                <ul class="list-group">
                    <li class="list-group-item active"><i class="fas fa-sort-amount-down-alt"></i> Fiyatı En Düşük <span class="badge badge-light"> <?=$callproc->rowCount()?> </span> Envanter</li>
                <?
                while($callcek=$callproc->fetch(PDO::FETCH_ASSOC)){
                 echo '<li class="list-group-item"><span class="badge badge-primary mr-1">'.$callcek["ad"].'</span><span class="badge badge-info mr-1"> '.$callcek["menuad"].' </span><span class="badge badge-danger mr-1">'.$callcek["fiyat"].' ₺</span></li>';
                }
                }
                // ************ SON MIN 5 ************
                ?>
                </ul>
            </div>   
                <? 
                 // ************ MAX  ************
                $callproc=$db->prepare("call sp_maxenvanter(:pid)");
                $callproc->execute(array("pid"=>$id));
                if($callproc->rowCount()>0){?>
            
            <div class="col-md-4 mb-1">    
                <ul class="list-group">
                    <li class="list-group-item active"><i class="fas fa-sort-up"></i> Fiyatı En Yüksek <span class="badge badge-light"> <?=$callproc->rowCount()?> </span> Envanter</li>
                <?
                while($callcek=$callproc->fetch(PDO::FETCH_ASSOC)){
                 echo '<li class="list-group-item"><p><span class="badge badge-primary mr-1">'.$callcek["ad"].'</span><span class="badge badge-info mr-1"> '.$callcek["menuad"].' </span><span class="badge badge-danger mt-1">'.$callcek["fiyat"].' ₺</span></p></li>';
                }
                }
                // ************ SON MAX ************
                ?>
                </ul>
            </div>    
                <? 
                 // ************ MIN  ************
                $callproc=$db->prepare("call sp_minenvanter(:pid)");
                $callproc->execute(array("pid"=>$id));
                if($callproc->rowCount()>0){?>
             
            <div class="col-md-4 mb-1">    
                <ul class="list-group">
                    <li class="list-group-item active"><i class="fas fa-sort-down"></i> Fiyatı En Düşük <span class="badge badge-light"> <?=$callproc->rowCount()?> </span> Envanter</li>
                <?
                while($callcek=$callproc->fetch(PDO::FETCH_ASSOC)){
                 echo '<li class="list-group-item"><p><span class="badge badge-primary mr-1">'.$callcek["ad"].'</span><span class="badge badge-info mr-1"> '.$callcek["menuad"].' </span><span class="badge badge-danger mt-1">'.$callcek["fiyat"].' ₺</span></p></li>';
                }
                }
                // ************ SON MIN ************
                ?>
                </ul>
            </div>
    </div>
    
    
  </div>
  <?
        /*
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://www.api.omurserdar.com/ajaxKurumsalBilgiler.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "id=$id");
        
        // In real life you should use something like:
        // curl_setopt($ch, CURLOPT_POSTFIELDS, 
        //          http_build_query(array('postvar1' => 'value1')));
        
        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $server_output = curl_exec($ch);
        
        curl_close ($ch);
	  
	    var_dump(json_decode($server_output,true));
	    */
	   
	}
	else{
	    header("Location: https://www.omurserdar.com/404.php");
	}
	
	
	if(isset($_SESSION["kullanici_tip"])&&$_SESSION["kullanici_tip"]=="bireysel"){
	    include "sepetmodaljs.php";
	}
	
	include "footer.php";
	
	?>
	
	
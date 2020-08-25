<?php
 $title="Anasayfa | API";
 $desc="anasayfa api";
 include "header.php";?>
<head>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.all.js"> </script> sweetalert için js -->

<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.css"> sweetalert için css-->
 </head>
 <?php 
 //giris yapan icin event
 if(isset($_SESSION["kullanici_tip"])){?>
 <div class="container">
     <br>
      <div class="row text-center">
          
  <?if($_SESSION["kullanici_tip"]=="bireysel"){?>
          <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
             
  <li class="nav-item">
    <a class="nav-link active" id="pills-cevrem-tab" data-toggle="pill" href="#pills-cevrem" role="tab" aria-controls="pills-cevrem" aria-selected="true"><i class="fas fa-store"></i> Çevremdekiler</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-siparislerim-tab" data-toggle="pill" href="#pills-siparislerim" role="tab" aria-controls="pills-siparislerim" aria-selected="false"><i class="fas fa-history"></i> Siparişlerim</a>
  </li>
   <li class="nav-item">
    <a class="nav-link" id="pills-bilgilerim-tab" data-toggle="pill" href="#pills-bilgilerim" role="tab" aria-controls="pills-bilgilerim" aria-selected="false"><i class="fas fa-user"></i> Bilgilerim</a>
  </li>
</ul>
<?}else{ ?>

<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
   <li class="nav-item" data-toggle="tooltip" title="görüntülemek ya da yenilemek için tıkla">
    <a class="nav-link active" id="pills-kurbilgilerim-tab" data-toggle="pill" 
    href="#pills-kurbilgilerim" role="tab" aria-controls="pills-kurbilgilerim"
    aria-selected="false"><i class="fas fa-user fa-2x"></i> Bilgilerim</a>
  </li>
    <li class="nav-item" data-toggle="tooltip" title="görüntülemek ya da yenilemek için tıkla">
    <a class="nav-link" id="pills-kursiparislerim-tab" data-toggle="pill" 
    href="#pills-kursiparislerim" role="tab" aria-controls="pills-kursiparislerim" 
    aria-selected="true"><i class="fas fa-history fa-2x"></i> Siparişler</a>
  </li>
  <li class="nav-item" data-toggle="tooltip" title="görüntülemek ya da yenilemek için tıkla">
    <a class="nav-link" id="pills-kurtabMenuler-tab" data-toggle="pill" 
    href="#pills-kurtabMenuler" role="tab" aria-controls="pills-kurtabMenuler" 
    aria-selected="true"><i class="fas fa-clipboard-list fa-2x"></i> Menüler</a>
  </li>
  <li class="nav-item" data-toggle="tooltip" title="görüntülemek ya da yenilemek için tıkla">
    <a class="nav-link" id="pills-kurEnvanter-tab" data-toggle="pill" 
    href="#pills-kurEnvanter" role="tab" aria-controls="pills-kurEnvanter" 
    aria-selected="true"><i class="fas fa-utensils fa-2x"></i> Envanterler</a>
  </li>
  <li class="nav-item" data-toggle="tooltip" title="görüntülemek ya da yenilemek için tıkla">
    <a class="nav-link" id="pills-kurHizli-tab" data-toggle="pill" 
    href="#pills-kurHizli" role="tab" aria-controls="pills-kurHizli" 
    aria-selected="true"><i class="fas fa-bolt fa-2x"></i> Hızlı İşlemler</a>
  </li>
  
</ul>
<? } ?>

</div> 
    
   
      
       <!-- YUKLENIYOR MODAL -->
            <div class="modal fade" id="yukleniyorModal" role="dialog">
                <div class="modal-dialog modal-lg">
                 <!-- Modal content-->
                 <div class="modal-content">
                  <div class="modal-body modYukBody">
                  <center><div class="loaderx"></div></center>
                  </div>
                 </div>
                </div>
            </div>
        <!-- SON YUKLENIYOR MODAL -->
                     
   <!-- SİPARİS Modal -->
 <div class="modal fade" id="siparisDetayModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                <h4 class="modal-title siparisDetayModTit">Bilgiler</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- <small class="text-info ml-2 "> *** sipariş sonrasında tutar kısmı ile
                         ürünlerin toplam tutarlarının eşit olmaması ürünün daha 
                         sonrasında fiyatının güncellenmesinden (güncel fiyat gelmesinden) kaynaklanıyor sanırım</small>
                         -->
                        
                    <div class="modal-body siparisDetayModBody">
                         
                    </div>
                    <div class="modal-footer siparisDetayModFooter">
                                  
                    </div>
                </div>
            </div>
     </div>
    <!--SON SİPARİS Modal -->    
 
 
      <?if($_SESSION["kullanici_tip"]=="bireysel"){
            try{
           // $tip=$_SESSION["kullanici_tip"];
        	$kullanici_mail=$_SESSION["kullanici_mail"];
        	$kullanicisor=$db->prepare("SELECT * FROM bireysel where email=:mail");
        	$kullanicisor->execute(array('mail' => $kullanici_mail));
        	$say=$kullanicisor->rowCount();
        	$kay=$kullanicisor->fetch(PDO::FETCH_ASSOC);
                	if ($say==1) {
                	$id=$kay["id"];
           $url = "https://api.omurserdar.com/api/bireysel?id=$id";
           $json = file_get_contents($url);
           $jsonverilerim = json_decode($json, true);   
            $kume=$jsonverilerim["bireyselbilgileri"];
                    //foreach($kume as $veri)
                        //echo $veri."<br/>";
?>


              <?php
              //BİREYSEL İL İLÇE SORGULAMA 
              //	$ilsor=$db->prepare("SELECT * FROM il,ilce WHERE il.id=ilce.il_id AND il.id=:pilid AND ilce.id=:pilceid");
        	//$ilsor->execute(array('pilid' => $kume["il_id"],'pilceid'=>$kume["ilce_id"]));
        	//$ilsay=$ilsor->rowCount();
        	//$ilcek=$ilsor->fetch(PDO::FETCH_ASSOC);
        	//BİREYSEL İL İLÇE SORGULAMA SON
            ?>
            
            <!-- user div bilgiler -->
            <div id="userid" data-id="<?=$kume["id"]?>"></div> 
            <div id="ilid" data-id="<?=$kume["il_id"]?>"></div>
            <div id="ilceid" data-id="<?=$kume["ilce_id"]?>"></div>
            <div id="ilceadi" data-id="<?=$kume["ilce_adi"]?>"></div>
            <!-- son user div bilgiler -->
<?php 

    //echo count($_SESSION["sepet"]);
    // print_r($_SESSION["sepet"]);
  //echo "-------<br>";
  //foreach($_SESSION["sepet"] as $k=>$v)
   // if(1737==$v["id"])
    // echo "buldum";
   //
?>
                     
     <!-- SEPET MODAL -->
            <div class="modal fade" id="sepetModal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                         <h4 class="modal-title modSepetTitle">Sepetim</h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body modSepetBody">
                              
                        </div>
                   </div>
                </div>
            </div>
     <!-- SON SEPET MODAL-->
     
     <!-- KURUMSAL BİLGİ Modal -->
                           <div class="modal fade" id="empModal" role="dialog">
                            <div class="modal-dialog modal-lg">
                         
                             <!-- Modal content-->
                             <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title kurumsalModTit">Bilgiler</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body envanterVeTab">
                         
                              </div>
                              <div class="modal-footer">
                               <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                              </div>
                             </div>
                            </div>
                           </div>
    <!--SON KURUMSAL BİLGİ Modal -->
     
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-cevrem" role="tabpanel" aria-labelledby="pills-cevrem-tab">
      <div class="btcevrem"></div>
    <div class="container">
    <p class="lead" id="cevrebilgi"></p>
     <div class="row" id="kurumsallar">
  
     </div><!-- row -->
    </div><!-- container -->
    
<script type="text/javascript">
$("document").ready(function() { 
$('.nav-pills a[href="#pills-cevrem"]').trigger('click');
});
    
$('.nav-pills a[href="#pills-cevrem"]').on('click',function(){
    var userid=$("#userid").attr("data-id");
    var dil=$("#ilid").attr("data-id");
    var dilce=$("#ilceid").attr("data-id");
    var renkler=["success","primary","info","danger","warning"];
    spinekle(".btcevrem");
    $.ajax({
        url: 'https://www.api.omurserdar.com/api/kurumsal/ilveilcekurumsal.php',
        type: 'get',
        data: {ilid: dil},
        dataType: "json",
        contentType: "application/json",
        success: function(cevil){ 
            spinkaldir(".btcevrem");
        //cevil=JSON.parse(cevil);
       // console.log(cevil);
        if(cevil.hata==false){
            if(cevil.ilegorebilgiler){
            var il=cevil.ilegorebilgiler[0]["kurumsalbilgileri"]["il_adi"];
            }
            var ilce=$("#ilceadi").attr("data-id");
            if(cevil.sayi>0){
                $("#cevrebilgi").html("<b class='badge badge-primary'>"+il+"</b> ilinde toplam "+
                "<b class='badge badge-primary'>"+cevil.sayi+"</b> adet kayıt bulundu (İlçeniz: <b class='badge badge-pill badge-success'>"+ilce+"</b>)");
            }else
                $("#cevrebilgi").html("*** İlinizde kurumsal bulunmuyor ***");
            //her bir eleman icin card olustur
            
            var eklencek='';
                $.each(cevil.ilegorebilgiler, function (i, item) {
                    //console.log(item);
                    var kurbilgi=item.kurumsalbilgileri;
                    var renk=random_item(renkler);
                    var butdurum="";
                    var acikdurum="";
                    //ilçe farklı ise disabled olsun
                if(kurbilgi.ilce_id!=dilce){
                    butdurum='disabled';
                    ilcerenk="danger";
                }
                else{
                    butdurum='enabled';
                    if(kurbilgi.acikMi==1)
                        ilcerenk="success";
                    else
                        ilcerenk="warning";
                }//son ilçe farklı ise disabled olsun
                    
                    //açık ya da kapalı bilgisi ve min alım tutar yazsın
                    if(kurbilgi.acikMi==1)
                        acikdurum='<span class="badge badge-pill badge-success"><i class="fas fa-toggle-on"></i> AÇIK </span>'
                    else
                        acikdurum='<span class="badge badge-pill badge-danger"><i class="fas fa-toggle-off"></i> KAPALI </span>'
                    
                    var mindurum='<span class="badge badge-pill badge-info">'+kurbilgi.minAlimTutar+' ₺</span>';
                    //son açık ya da kapalı bilgisi ve min alım tutar yazsın
                    
                eklencek+='<div class="card border-'+renk+' mb-2 mr-2" style="max-width: 25rem;">'+
                '<div class="card-header"><span class="badge badge-pill badge-primary">'+kurbilgi.kullaniciadi+"</span> "+acikdurum+" "+mindurum+'</div><div class="card-body">'+
                '<button acikmi='+kurbilgi.acikMi+' type="button" data-id="'+kurbilgi.id+'" class="btnkurAd btn btn-outline-'+renk+" btn-block "+butdurum+'" durum='+butdurum+'>'+kurbilgi.ad+'</button></div>'+
                '<div class="card-footer text-muted"><p class="card-text"><i class="fas fa-map-pin"></i> '+kurbilgi.adres+'<hr/>'+
                '<span class="badge badge-pill badge-'+ilcerenk+' btn-block">'+kurbilgi.ilce_adi+'</span></p>'+
                '<a href="/kurumsal/'+kurbilgi.kullaniciadi+'" class="btn btn-outline-'+renk+' btn-block btn-sm">İncele <i class="fas fa-arrow-circle-right"></i></a>'+
                
                '</div></div>';
                
                });//each 
                 //console.log(eklencek.length);
                 $("#kurumsallar").html(eklencek);
                 
                 
$(".btnkurAd").on("mouseover",(function(){ $( this ).css( "font-size", "18px" ); }));
$( ".btnkurAd" ).on("mouseout",(function(){ $( this ).css( "font-size", "14px" ); }));
                 
                         $('.btnkurAd').on("click",function(){
                            $('.nav-pills a[href="#pills-cevrem"]').trigger('click');
                           var userid = $(this).data('id'); 
                           if($(this).attr("durum")=="disabled"){
                               var diaFarkliIlceKur=$.dialog({
                        type:'red',
                        title: '<b class="text-danger"> SİPARİŞ VERİLEMEZ <b>',
                        content: '<b><i><u>İlçeniz ile kurumsal ilçesi farklı olduğundan</u></i></b> sipariş verilemez'
                        });
                               mesajKapat(diaFarkliIlceKur,2500)
                               return false;
                           }
                           else{
                               if($(this).attr("acikmi")==0){
                                   var diaAyniIlceKapalı=$.dialog({
                        type:'orange',
                        title: '<b class="text-warning"> KURUMSAL KAPALI <b>',
                        content: '<b><i><u>Kurumsal şu anda hizmet dışı olduğundan</u></i></b> sipariş verilemez'
                        });
                               mesajKapat(diaAyniIlceKapalı,2500)
                               return false;
                               }
                           }
                          // var s=$('.sepetEnvSayi').text();
                           $.ajax({
                            url: '/ajaxKurumsalBilgiler.php',
                            type: 'post',
                            data: {id: userid},
                             beforeSend: function() {
                                    //$("#load"+userid).show();
                                    $('#yukleniyorModal').modal('show');
                                },
                            success: function(response){ 
                                response=JSON.parse(response);
                                $('.kurumsalModTit').html(response.ad);
                                $('.envanterVeTab').html(response.cevap);
                                $('#empModal').modal('show'); 
                                
                                $("#empModal").unbind("shown.bs.modal").on('shown.bs.modal', function(){
                               $('#yukleniyorModal').modal("hide"); 
                                });
                                /*
                              var url="/ajaxKurumsalBilgiler?id="+userid;
                              setInterval(function(){
                               $(".envanterVeTab").load(url+" .envanterVeTab");
                                }, 2000);
                                */
                              
$(".btnsepetEkle").on("mouseover",(function(){ $( this ).css( "font-size", "22px" ); }));
$(".btnsepetEkle" ).on("mouseout",(function(){ $( this ).css( "font-size", "18px" ); }));

                              //sepete Ekleme   
                                $('.btnsepetEkle').on("click",function(){
                                        var pEnvId = $(this).attr('id');
                                        var pEnvFiyat=$(".efiyat"+pEnvId).data("id");
                                        var dsecim="btnsepetEkle";
 
                                   $.ajax({
                         method: 'POST',
                         url : "https://www.api.omurserdar.com/ajaxSepetim.php",
                         //data : {secim:dsecim,id:pEnvId,fiyat:pEnvFiyat},
                         data : {secim:dsecim,id:pEnvId},
                         success: function(eklendiMi){
                             if(eklendiMi=="eklendi"){  
                                            var diaHarika=$.dialog({
                                //autoClose: 'cancelAction|8000',
                                type:'green',
                                title: '<b class="text-success"> <i class="fas fa-check-circle"></i> Harika </b>',
                                content: 'SEPETİNE EKLENDİ'
                                                 });
                                            mesajKapat(diaHarika);
                                            }
                                        else if(eklendiMi=="arti"){
                                           var diVardi=$.dialog({
                                    type:'purple',
                                title: '<b class="text-info"> <i class="fas fa-info"></i> Bilgilendirme <b>',
                                content: 'Zaten vardı, <b>1</b> tane daha eklendi'
                                                 });
                                            mesajKapat(diVardi);
                                        }
                                        else if(eklendiMi=="farkli"){
                                           var diFark=$.dialog({
                                    type:'orange',
                                title: '<b class="text-warning"><i class="fas fa-lock"></i> Uyarı </b>',
                                content: 'Farklı kurumsaldan ürün alabilmen için sepetini boşaltman gerek',
                                                 });
                                            mesajKapat(diFark);
                                        }
                                            else{
                                                $.dialog({
                        title: '<b class="text-danger"><i class="fas fa-times"></i> HATA </b>',
                        content: 'Sepetine Eklenemedi :( ! '+eklendiMi,
                                                        });
                                        }
                                 }
                                 });
                                        });
                              // SON sepete Ekleme 
                              
                              
                            },
                             complete:function(data){
                               $(".loader").hide();
                               }
                          });
                         }); //son btnkurad on click
                       
            }//son hata yoksa 
            }//success
        }); //ajax
    }); //click 
      
          </script>
         
                
                        <!-- SEPETIM GORUNTULE JS -->
                         <script type="text/javascript">
                          $(document).ready(function(){
                            $('.btnSepetim').on("click",function(){
                                var dsecim="btnSepetim";
                                var jc;
                                  $.ajax({
                         method: 'GET',
                         url : "https://www.api.omurserdar.com/ajaxSepetim.php",
                         data : {secim:dsecim},
                          beforeSend: function(data) {
                          //$('#yukleniyorModal').modal("show");
                          jc=$.dialog({
                                closeIcon:false,
                                icon: 'fa fa-spinner fa-spin',
                                type:'purple',
                                title: '<b class="text-info"> YÜKLENİYOR <b>',
                                content: '<i class="fas fa-spinner fa-pulse fa-4x"></i> Yükleniyor'
                                                 });
                          
                            
                        },
                         success: function(data){
                               data=JSON.parse(data);
                               jc.close();
                               if(data.sayi==0){
                                      $('.modSepetTitle').html("<h4> SEPETİM </h4>");
                                       $('.modSepetBody').html("<h2> SEPET BOŞ </h2>");
                               }
                               else{
                                     $('.modSepetTitle').html("<h4>SEPETİM <small class='text-primary'>Sipariş verebilmek için sepet tutarı en az "+data.minalim+" ₺ olmalı ! (<b>"+data.kurumsal+"</b>)</small></h4>");
                                     $('.modSepetBody').html(data.cevap);
                                    
                               }
                                       
                                       $('#sepetModal').modal("show");
                                       if(data.sayi!=0){
                                 var sepetenvDizi=[];//kaldirm click de erişeceğimden üst tanımladım
                                //unbind ile bir önceki event kaldırdım 
                                
                             $("#sepetModal").unbind("shown.bs.modal").on('shown.bs.modal', function(){
                               //$('#yukleniyorModal').modal("hide"); 
                                //$('.modSepetBody').append("1");
           
                                   
                                    var eleman=$('.modSepetBody').children("center").children(); //sepet center a eriştim
                                        eleman.each(function(i,v){
                                            if($(this).attr("sepetenvid")!=null){
                                            sepetenvDizi.push($(this).attr("sepetenvid"));
                                            }
                                        });
                                });
                                       }
                                
                                
     
                                
                                 //SİPARİS VERME
                                 $('.btnSiparisVer').click(function(){
                                     
                                        //var pt = $(".tutar").attr('id');
                                        //alert(pEnvId);
                                        var dsecim="btnSiparisVer";
                                        //var dtutar = $(".toptutar").data('id');
                                        //alert("toptutar: "+dtutar);
                                   $.ajax({
                         method: 'POST',
                         url : "https://www.api.omurserdar.com/ajaxSiparis.php",
                         //data : {secim:dsecim,toplamtutar:dtutar},
                         data : {secim:dsecim},
                         success: function(data){
                              data=JSON.parse(data);
                             if(data.cevap=="eklendi"){  
                                            var diSipKod=$.dialog({
                                type:'green',
                                title: '<b class="text-success"> S Ü P E R <b>',
                                content: '<b class="text-danger">'+data.sipKod+' </b> kodlu sipariş oluşturuldu',
                                                 });
                                            mesajKapat(diSipKod);
                                             $('.nav-pills a[href="#pills-siparislerim"]').trigger('click');
                                 $('#sepetModal').modal("hide");
                                 
                                 
                             }
                             else if(data.cevap=="minhata"){
                             var diMinHata=$.dialog({
                        type:'purple',         
                        title: ' <b class="text-warning"> UYARI <b> ',
                        content: 'Minimum alım tutarı karşılanmadı !',
                                                        });
                                           mesajKapat(diMinHata,3000);            
                             }
                                            else{
                                                $.dialog({
                        type:'red',
                        title: ' <b class="text-danger"> HATA <b> ',
                        content: 'Sipariş Oluşturulamadı :( !',
                                                        });
                                        }
                                 }
                                 });
                                        });
                                //SİPARİS VERME son
                                
                                //SEPETİMDEN KALDIR
                                $('.btnSepetEnvKaldir').on('click', function(){
                                   // alert("sepet kaldırma bitmediiii");
                                   var dEnvid=$(this).attr("data-id");
                                   var sec="btnSepetEnvKaldir";
                                   
                                  
                                   
                                     $.ajax({
                         method: 'POST',
                         url : "https://www.api.omurserdar.com/ajaxSepetim.php",
                         data : {secim:sec,envid:dEnvid},
                         success: function(sepetenvsildata){
                 sepetenvsildata=JSON.parse(sepetenvsildata);
                    if(sepetenvsildata.cevap=="silindi"){  
                        
                        /*
                         sepetenvDizi = sepetenvDizi.filter((value)=>value!=dEnvid);
                            //console.log("kaldirdim son hali: "+sepetenvDizi)
                                   
                        $("#cardsepenv"+dEnvid).slideUp(500, function() { $(this).remove();} );
                        var oncekiTopTutar=$(".toptutar").attr("id");
                        var guncelTopTutar=oncekiTopTutar-sepetenvsildata.silinenTutar;
                        $(".toptutar").attr("data-id",guncelTopTutar);//tutar data-id gncl
                        $(".toptutar").attr("id",guncelTopTutar);
                        $(".toptutar").html("tutar : "+guncelTopTutar+" &#8378;")
                            
                           var min=$(".toptutar").attr("limit");
                  

                            if(guncelTopTutar<min){
                                $(".btnSiparisVer").prop("hidden",true);
                                //$('.modSepetTitle').html("<h4> SEPETİM </h4>");
                            }
                            else{
                                  $(".btnSiparisVer").prop("hidden",false);
                            }
                            
                            if(sepetenvsildata.sepetkalanenvsayi==0){
                                $('.modSepetBody').html("<h2>SEPET BOŞ</h2>");
                                 $('.modSepetTitle').html("<h4> SEPETİM </h4>");
                            }
                        */
                        $("#sepetModal").unbind("shown.bs.modal");
                        $("#sepetModal").modal("hide");
                        $('.btnSepetim').trigger("click");
                                  }
                            }//sepetkaldir success
                                 });
                                });
                          //SEPETiMDEN KALDIR SON
                          
                          
                //attır
                          
                $('.btnAdetArttir').on('click', function(){
                   //$('.btnsepetEkle').trigger("click"); yüzlerce nesne var hangisi tetikelenecek :) :) 
                             
                             
                    var pEnvId = $(this).attr('id');
                    var pEnvFiyat=$(".efiyat"+pEnvId).data("id");
                    var dsecim="btnsepetEkle";
 
                    $.ajax({
                         method: 'POST',
                         url : "https://www.api.omurserdar.com/ajaxSepetim.php",
                         //data : {secim:dsecim,id:pEnvId,fiyat:pEnvFiyat},
                         data : {secim:dsecim,id:pEnvId},
                         success: function(eklendiMi){
                            if(eklendiMi=="arti"){
                                   
                            $("#sepetModal").unbind("shown.bs.modal");
                            $("#sepetModal").modal("hide");
                            $('.btnSepetim').trigger("click");
                                           
                            }
                                        
                            else{
                                /*
                                $.dialog({
                                title: '<b class="text-danger"><i class="fas fa-times"></i> HATA </b>',
                                content: 'Sepetine Eklenemedi :( ! '+eklendiMi,
                                    });
                                    */
                                }
                                
                        }
                    });
                              
                });
                          
               //son arttır
               
               $('.btnAdetAzalt').on('click', function(){
                   //$('.btnsepetEkle').trigger("click"); yüzlerce nesne var hangisi tetikelenecek :) :) 
                             
                             
                    var pEnvId = $(this).attr('data-id');
                    //var pEnvFiyat=$(".efiyat"+pEnvId).data("id");
                    var dsecim="btnSepetEnvAzalt";
 
                    $.ajax({
                         method: 'POST',
                         url : "https://www.api.omurserdar.com/ajaxSepetim.php",
                         //data : {secim:dsecim,id:pEnvId,fiyat:pEnvFiyat},
                         data : {secim:dsecim,envid:pEnvId},
                         success: function(resp){
                             
                            if(resp=="azaldi"){
                                   
                            $("#sepetModal").unbind("shown.bs.modal");
                            $("#sepetModal").modal("hide");
                            $('.btnSepetim').trigger("click");
                                           
                            }
                                        
                            else{
                                alert(resp);
                                /*
                                $.dialog({
                                title: '<b class="text-danger"><i class="fas fa-times"></i> HATA </b>',
                                content: 'Sepetine Eklenemedi :( ! '+eklendiMi,
                                    });
                                    */
                                }
                                
                        }
                    });
                              
                });
               
               //azalt
               
               //son azalt
                                
                                
                                 }//btnsepetim success
                                 });
                            });
                          });
                          
                          
                          
                         </script>
                      <!-- SEPETIM GORUNTULE JS SON -->
  </div><!-- tab cevre -->
  
                    <!-- TAB BİR SİPARİŞLERİM -->
  <div class="bid" data-id="<?=$_SESSION["kullanici_id"]?>"></div>
  <div class="tab-pane fade" id="pills-siparislerim" role="tabpanel" aria-labelledby="pills-siparislerim-tab">
      
      <div class="sipcard"></div>
      
  </div>
                      <!--SON TAB BİR SİPARİŞLERİM -->

                           
                           

 
  <!-- SİPARİSLERİM JS -->
  <script type="text/javascript">
       $(document).ready(function(){
           
           $('.nav-pills a[href="#pills-siparislerim"]').click(function(){
            var birid=$(".bid").data("id");
            
            $.ajax({
     method: 'GET',
     url : "https://www.api.omurserdar.com/api/siparislerim",
     data : {id:birid},
     dataType: "json",
     contentType: "application/json",
     success: function(dataSip){
         if(dataSip.sayi==0){
             $(".sipcard").html("<h2 class='text-info'> Sipariş Yok </h2>");
         }
         if(dataSip.hata==false && dataSip.sayi>0){  
     var veri='<table class="table table-striped"><thead class="thead-dark"><tr><th scope="col"><i class="fas fa-key"></i> Sipariş Kod</th><th scope="col"><i class="fas fa-store-alt"></i> Kurumsal Üye </th><th scope="col"><i class="fas fa-dolly"></i> Sipariş Durumu</th><th scope="col"><i class="fas fa-calendar-alt"></i> Sipariş Tarihi</th><th scope="col"><i class="fas fa-lira-sign"></i> Toplam Tutar</th></tr></thead><tbody><tr>';
      var metin="";
  
     for (i in dataSip.siparislerKume) { // class="atablosipKod
          metin+='<td><a style="font-size: 15px;" class="atablosipKod btn btn-outline-primary btn-sm" title="sipariş özeti için tıklayın" data-id="'+dataSip.siparislerKume[i].siparisKod+'" href="javascript:void(0);" data-toggle="modal" data-target="#siparisDetayModal">'+dataSip.siparislerKume[i].siparisKod+'</a></td>';
          metin+='<td>'+dataSip.siparislerKume[i].ad+'</td>';
          metin+='<td>'+dataSip.siparislerKume[i].tanim+durumagoreyaz(dataSip.siparislerKume[i].tanim);
            if(dataSip.siparislerKume[i].tanim=="Tamamlandı")
            {
            
            var butonyazi="";
            //bilgileri al
           $.ajax({
               async: false, //{} içerisinde değişkeni doldurdum ve dışarıda kullandım https://stackoverflow.com/questions/1457690/jquery-ajax-success-anonymous-function-scope https://stackoverflow.com/questions/16805306/jquery-return-ajax-result-into-outside-variable/16805366
            method: 'GET',
            url : "https://www.api.omurserdar.com/api/degerlendirme/index.php",
            data : {sipariskod:dataSip.siparislerKume[i].siparisKod},
            contentType: "application/json",
            success: function(data){
                
                    
                if(data.degsay==0)
                    //butonyazi="Değerlendir";
                    //metin+="<br> <a href='/degerlendir/"+dataSip.siparislerKume[i].siparisKod+"' class='btn btn-outline-info btn-block btn-sm'> degerlendir </a>";
                    metin+="<br> <button type='button' class='btn btn-outline-info btn-block btn-sm btndegerlendir' sipkod='"+dataSip.siparislerKume[i].siparisKod+"'><i class='fas fa-thumbs-up'></i> değerlendir </a>";
                else{
                    //butonyazi="Değerlendirmeyi gör";
                
                    metin+='<div class="btn-group mt-1 ml-1"><button type="button" class="btn btn-outline-danger btn-block btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cogs"></i> Değerlendirme İşlemleri</button><div class="dropdown-menu"><a data-id="'+data.degerlendirme.id+'" id="'+dataSip.siparislerKume[i].siparisKod+'" class="dropdown-item ddowndeggor" href="javascript:void(0);"><i class="fas fa-eye"></i> görüntüle </a><a <a data-id="'+data.degerlendirme.id+'" id="'+dataSip.siparislerKume[i].siparisKod+'" class="dropdown-item ddowndegduz" href="javascript:void(0);"><i class="fas fa-edit"></i> güncelle</a><a <a data-id="'+data.degerlendirme.id+'" id="'+dataSip.siparislerKume[i].siparisKod+'" class="dropdown-item ddowndegsil" href="javascript:void(0);"><i class="fas fa-trash"></i> sil</a></div>';
                }
                    
                    
                    
                }
            });
            //metin+="<br> <a href='/degerlendir/"+dataSip.siparislerKume[i].siparisKod+"' class='btn btn-outline-info btn-block btn-sm'>"+butonyazi+" </a>";
            //
 
            }
            console.log(metin);
                metin+="</td>";

          metin+='<td class="sipTarih'+dataSip.siparislerKume[i].siparisKod+'" data-id="'+dataSip.siparislerKume[i].sipTarih+'">'+tr_tarih(dataSip.siparislerKume[i].sipTarih)+'</td>'; 
          metin+='<td class="sipTutar'+dataSip.siparislerKume[i].siparisKod+'" data-id="'+dataSip.siparislerKume[i].toplamTutar+'">'+dataSip.siparislerKume[i].toplamTutar+'  &#8378</td></tr>';
        }
    metin+='</tbody></table>';
    
    $(".sipcard").html(veri+metin);
    
    
    //ddowndeggor click
    $('.ddowndeggor').on('click', function(){
    
    var sipkod=$(this).attr("id");
    
    $.ajax({
        method: 'GET',
        url : "https://www.api.omurserdar.com/api/degerlendirme",
        data : {sipariskod:sipkod},
        success: function(datasipdeger){
  
            var hizyildiz="";
            for(j=0;j<datasipdeger.degerlendirme.hiz;j++)
                    hizyildiz+='<span><i class="fas fa-star" style="color:#FFA500"></i></span>';
			for(k=0; k<(10-datasipdeger.degerlendirme.hiz); k++)
					hizyildiz+='<span><i class="far fa-star"></i></span>';
					
			var lezzetyildiz="";
            for(j=0;j<datasipdeger.degerlendirme.lezzet;j++)
                    lezzetyildiz+='<span><i class="fas fa-star" style="color:#FFA500"></i></span>';
			for(k=0; k<(10-datasipdeger.degerlendirme.lezzet); k++)
					lezzetyildiz+='<span><i class="far fa-star"></i></span>';		
					
  
        var dikurdeggor=$.dialog({
            type:'purple',
            title: '<b class="text-info">Değerlendirme Bilgileri</b>',
            content: 'HIZ: '+hizyildiz+' <br> LEZZET: '+lezzetyildiz+' <br> YORUM: '+datasipdeger.degerlendirme.yorum
         });
         
         mesajKapat(dikurdeggor,"7000");
   
             }
             });
    
         
         
                
                
    });
    //son ddowndeggor
    
    //ddowndegduz
    $(".ddowndegduz").on("click",function(){
           var sipkop=$(this).attr("id"); 
           //var degyorum=$(".yorum").html();
            
           //bilgileri al
           $.ajax({
            method: 'GET',
            url : "https://www.api.omurserdar.com/api/degerlendirme/index.php",
            data : {sipariskod:sipkop},
            contentType: "application/json",
            success: function(data){
                var bilgi=data.degerlendirme;
                //var hizyildiz="";
                /*
                for(j=0;j<bilgi.hiz;j++)
                    hizyildiz+='<span><i class="fas fa-star" style="color:#FFA500"></i></span>';
				for(k=0; k<(10-bilgi.hiz); k++)
					hizyildiz+='<span><i class="far fa-star"></i></span>';
                */
                //confirm  
                    $.confirm({
                type:'purple',
                title: '<b class="text-info"> Sipariş Değerlendirme Düzenleme</b>',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Hız: </label>' +
                '<input type="number" step="any" min="1" max="10" value="'+bilgi.hiz+'" class="form-control hizduz" required />' +
                '</div>'+
                
                '<div class="form-group">' +
                '<label>Lezzet </label>' +
                '<input type="number" step="any" min="1" max="10" value="'+bilgi.lezzet+'" class="form-control lezzetduz" required />' +
                '</div>'+
                
                '<div class="form-group">' +
                '<label>Yorum:  </label>' +
                '<textarea class="form-control" id="duzenleyorum">'+bilgi.yorum+'</textarea>' +
                 '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Gönder',
                        btnClass: 'btn-blue',
                        action: function(){
                             
                              var phiz=$(".hizduz").val();
                             
                            if(!phiz || phiz<1 || phiz>10){
                                var diDegHiz=$.dialog('<b class="text-danger">1 ile 10 arası değer girişi yapın </b>');
                                mesajKapat(diDegHiz);
                                return false;
                            }
                             
                            var plezzet=$(".lezzetduz").val();
                             
                            if(!plezzet || plezzet<1 || plezzet>10){
                                var diDegLezzet=$.dialog('<b class="text-danger">1 ile 10 arası değer girişi yapın </b>');
                                mesajKapat(diDegLezzet);
                                return false;
                            } 
                             
                            var pyorum=$.trim($("#duzenleyorum").val());
                            
                            if(!pyorum){
                                var diDegYorum=$.dialog('<b class="text-danger"> Yorum alanı boş bırakılamaz</b>');
                                mesajKapat(diDegYorum);
                                return false;
                            } 
                             
                             //burada put işlemi olacak
                             
                             var dobje={"id":bilgi.id,"hiz":phiz,"lezzet":plezzet,"yorum":pyorum};
                            dobje=JSON.stringify(dobje);
                            //alert(dobje);
                                 $.ajax({
                                method: 'PUT',
                                url : "https://www.api.omurserdar.com/api/degerlendirme/",
                                //dataType: "json",
                                //contentType: "application/json"
                                data : dobje,
                                success: function(apidegduzenle){
                                    if(apidegduzenle.mesaj=="güncellendi"){
                                var didegGuncellendi=$.dialog({
                                    type:'green',
                                    title: '<b class="text-success">Güncellendi</b>',
                                    content: 'Değerlendirme Güncellendi',
                                    onClose: function () {
                                        
                                        window.location.replace("https://api.omurserdar.com");
                                                 }
                                            });
                                    
                                                mesajKapat(didegGuncellendi);
                            //$('.nav-pills a[href="#pills-kurbilgilerim"]').trigger('click'); 
                                }
                                
                                }
                                });
                             //put işlemi son
                             
                    } //dialog action
                    },
                cancel: {
                         text:'İptal et'
                    },
             
            }
            }); //confirm
                
                
            }
           });
           //bilgileri al son
           
           
        });
    //son ddowndegduz
    
    //ddowndegsil
     $(".ddowndegsil").on("click",function(){
         
        var degid=$(this).attr("data-id"); 
        
        $.confirm({
            type:'red',    
            title: '<b class="text-danger">Silmek istediğine emin misin?</b>',
            content:  "<b class='text-danger'> Değerlendirme Silinecek </b><br>",
            buttons: {
                evet: {
                    text: 'Evet, sil',
                    btnClass: 'btn-danger',
                    action: function () {
                            $.ajax({
                            method: 'DELETE',
                            url : "https://www.api.omurserdar.com/api/degerlendirme/index.php?id="+degid,
                            success: function(apidegsil){
                                if(apidegsil.mesaj=="silindi"){
                                        
                                    var diaDegSil=$.dialog({
                                        type:'dark',    
                                        title: '<b class="text-info"          >Silindi</b>',
                                        content:  "<b class='text-info'> Değerlendirme silinmiştir </b>",
                                        
                                        onClose: function () {
                                        
                                        window.location.replace("https://api.omurserdar.com");
                                                 }
                                            });
                                                mesajKapat(diaDegSil);
                                            }
                                        
                                    }//delete success
                                 });
                                 
                        }
                    },
                    cancel: {
                         text:'İptal et'
                    }
                }
            });
         
     });
    //son ddowndegsil
    
    //btndegerlendir click
    $(".btndegerlendir").on("click",function(){
        
        var sipkod=$(this).attr("sipkod");
        //confirm deg
                    $.confirm({
                type:'purple',
                title: '<b class="text-info"> Sipariş Değerlendirme</b>',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Hız: </label>' +
                '<input type="number" step="any" min="1" max="10" value="1" class="form-control inphiz" required />' +
                '</div>'+
                
                '<div class="form-group">' +
                '<label>Lezzet: </label>' +
                '<input type="number" step="any" min="1" max="10" value="1" class="form-control inplezzet" required />' +
                '</div>'+
                
                '<div class="form-group">' +
                '<label>Yorum: </label>' +
                '<textarea class="form-control" id="tareyorum" placeholder="Yorumunuzu yazın"></textarea>' +
                 '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Gönder',
                        btnClass: 'btn-blue',
                        action: function(){
                             
                            var phiz=$(".inphiz").val();
                             
                            if(!phiz || phiz<1 || phiz>10){
                                var diDegHiz=$.dialog('<b class="text-danger">1 ile 10 arası değer girişi yapın </b>');
                                mesajKapat(diDegHiz);
                                return false;
                            }
                             
                            var plezzet=$(".inplezzet").val();
                             
                            if(!plezzet || plezzet<1 || plezzet>10){
                                var diDegLezzet=$.dialog('<b class="text-danger">1 ile 10 arası değer girişi yapın </b>');
                                mesajKapat(diDegLezzet);
                                return false;
                            } 
                             
                            var pyorum=$.trim($("#tareyorum").val());
                            
                            if(!pyorum){
                                var diDegYorum=$.dialog('<b class="text-danger"> Yorum alanı boş bırakılamaz</b>');
                                mesajKapat(diDegYorum);
                                return false;
                            } 
                            
                             //burada post işlemi olacak
                             
                             
                             
                              $.ajax({
                         method: 'POST',
                         url : "https://www.api.omurserdar.com/api/degerlendirme/index.php",
                         //data : {secim:dsecim,id:pEnvId,fiyat:pEnvFiyat},
                         data : {sipariskod:sipkod,hiz:phiz,lezzet:plezzet,yorum:pyorum},
                         success: function(datadegekle){
                             if(datadegekle.mesaj=="eklendi"){  
                                            var diaDegEklendi=$.dialog({
                                //autoClose: 'cancelAction|8000',
                                type:'green',
                                title: '<b class="text-success"> <i class="fas fa-check-circle"></i> Harika </b>',
                                content: 'Değerlendirme Eklendi',
                                onClose: function () {
                                        //eklendikten sonra tab click tetiklen
                                        //$('.nav-tabs a[href="#pills-siparislerim"]').trigger("click");
                                        
                                        window.location.replace("https://api.omurserdar.com");
                                                 }
                                            });
                                                mesajKapat(diaDegEklendi);
                                            }
                            else{
                        $.dialog({
                        title: '<b class="text-danger"><i class="fas fa-times"></i> HATA </b>',
                        content: 'Değerlendirilme Eklenemedi :( ! '+datadegekle.mesaj
                                          });
                                        }
                                 }
                                 });
                             
                             //post işlemi son
                             
                    } //dialog action
                    },
                cancel: {
                         text:'İptal et'
                    },
             
            }
            }); 
        
        //son confrim deg
    });
    //btndegerlendir click son
    
     //sipariş modal
     $('.atablosipKod').click(function(){
         var kod=$(this).data("id");
         var tarih=$('.sipTarih'+kod).data("id");
         var tutar=$('.sipTutar'+kod).data("id");
         
         $(".siparisDetayModTit").html("<b class='text-danger'>"+kod+"</b> sipariş koduna ait bilgiler");
          $.ajax({
            method: 'GET',
            url : "https://www.api.omurserdar.com/api/siparislerim/siparisEnvanterler.php",
            data : {siparisKod:kod},
            dataType: "json",
            contentType: "application/json",
            success: function(dataSipEnv){
                //var dataSipEnv = JSON.stringify(dataSipEnv);
                if(dataSipEnv.hata==false){ 
                    var metin="<center>";
                    for (i in dataSipEnv.EnvnaterlerVeSiparisler){
                        metin+='<div class="card border-info mb-3" style="max-width: 20rem;"><div class="card-body">';
                        metin+='<span class="ml-2 badge badge-success">Envanter: '+dataSipEnv.EnvnaterlerVeSiparisler[i].envanter_ad+'</span><hr>';
                        //metin+='<span class="ml-2 badge badge-info">Kurumsal:</span> <span class="ml-2 badge badge-primary">'+dataSipEnv.EnvnaterlerVeSiparisler[i].kurumsal_ad+'</span>';
                            metin+='<span class="ml-2 badge badge-warning">Adet: '+dataSipEnv.EnvnaterlerVeSiparisler[i].adet+'</span>';
                            metin+='<span class="ml-2 badge badge-danger">Tutar: '+dataSipEnv.EnvnaterlerVeSiparisler[i].tutar+' &#8378 </span></div></div>';
                            }//for
                            
                      $(".siparisDetayModBody").html(metin+"</center>");
                        $(".siparisDetayModFooter").html("KURUMSAL ÜYE: "+dataSipEnv.durum.kad+" | SİPARİŞ TARİH: "+tr_tarih(tarih)+' | TUTAR: '+tutar+" &#8378");
                     
                      $('#siparisDetayModal').modal("show");
                             } //if false
                             else{
                                 $(".siparisDetayModBody").html("hata");
                                 }
                      }, //success
                      error: function(e){
                        console.log(e);
                      }
                });
     });
     //son sipariş modal 
     
     //tablosipkod click event son
     
            }
            else
              $(".siparisDetayModTit").html("<b class='text-danger'>HATA</b>");
             },
             error: function(e){
                        console.log(e);
                      }
             });
    })
       });
       
  </script>
  <!-- SON SİPARİSLERİM JS -->
  
  
  <div class="tab-pane fade" id="pills-bilgilerim" role="tabpanel" aria-labelledby="pills-bilgilerim-tab">
       <div class="bid" data-id="<?=$_SESSION["kullanici_id"]?>"></div>
      <center>
          <div class="card border-primary mb-3">
              <div class="card-header bcas"></div> <!-- ad-soyad-->
              <div class="card-body">
                <h4 class="card-title bcem"></h4> <!-- email -->
                <p class="card-text bcka"></p> <!-- userName -->
                <p class="card-text bcilveilce"></p> <!-- il ilce-->
                <p class="card-text bckt"></p> <!-- kayit tarih -->
              </div>
         </div>
      </center>
  </div>
  
  <!-- BİR BİLGİLERİM JS -->
  <script type="text/javascript">
   
  $('.nav-pills a[href="#pills-bilgilerim"]').on('click', function(){
               var birid=$(".bid").data("id");
               $.ajax({
            method: 'GET',
            url : "https://www.api.omurserdar.com/api/bireysel/",
            data : {id:birid},
            contentType: "application/json",
            success: function(dataBirBilgi){
                $(".bcas").html("<h2> Bireysel:  <span class='badge badge-primary'>"+dataBirBilgi.bireyselbilgileri.ad +" "+dataBirBilgi.bireyselbilgileri.soyad+"</span></h2>");
                $(".bcilveilce").html("<h2> İl / İlçe: <span class='badge badge-primary'>"+dataBirBilgi.bireyselbilgileri.il_adi +" / "+dataBirBilgi.bireyselbilgileri.ilce_adi+"</span></h2>");
                $(".bcem").html("<h2> Email: <span class='badge badge-primary'>"+dataBirBilgi.bireyselbilgileri.email+"</span></h2>");
                $(".bcka").html("<h2> Kullanıcı Adı: <span class='badge badge-primary'>"+dataBirBilgi.bireyselbilgileri.kullaniciadi+"</span></h2>");
                $(".bckt").html("<h2> Kayıt Tarihi: <span class='badge badge-primary'>"+tr_tarih(dataBirBilgi.bireyselbilgileri.kayit_tarihi)+"</span></h2>");
            }
            });
           });
 
  </script>
<!-- SON BİR BİLGİLERİM JS -->
  
</div>
              
              </div>
            </div>
                <?
             }
            }
        catch (Exception $e) {
            echo 'Yakalanan olağandışılık: ',  $e->getMessage(), "\n";
             }
      }
      else{ //kurumsal
      ?>
      
       <!--         *********************************************  KUR HTML      *********************************************            -->
        <!-- KUR TAB -->
       <div class="tab-content" id="pills-tabContent">
           <div data-id="<?=$_SESSION["kullanici_id"]?>" class="kid"></div>
           
           <!-- TAB KUR BİLGİ -->
           <div class="tab-pane show active" id="pills-kurbilgilerim" role="tabpanel" aria-labelledby="pills-kurbilgilerim-tab">
            <div class="ktbs"></div>
            
            <center>
          <div class="card border-primary mb-3">
              <div class="card-header kca"></div> <!-- ad-kullaniciad-->
              <div class="card-body">
                <h4 class="card-title kcem"></h4> <!-- email -->
                <p class="card-text kcilveilce"></p> <!-- il ilce-->
                <p class="card-text kcadres"></p> <!-- adres-->
                <p class="card-text kcmin" data-id=""></p> <!-- min-->
                <p class="card-text kcacik" data-id=""></p> <!-- acikMi-->
                <p class="card-text kckt"></p> <!-- kayit tarih -->
                
              </div>
              <div class="card-footer kcislem"></div>
         </div>
      </center>
            
           </div>
           <!-- SON TAB KUR BİLGİ -->
           
            <!-- KUR BİLGİLERİM JS -->
  <script type="text/javascript">
  //sayfa yüklendiğinde a:kurbilgitab:click event tetiklensin
  $("document").ready(function() { 
        $('.nav-pills a[href="#pills-kurbilgilerim"]').trigger('click'); 
    }); 
  
      //kurbilgi tab on click
  $('.nav-pills a[href="#pills-kurbilgilerim"]').on('click', function(){
               var kurid=$(".kid").data("id");
               spinekle(".ktbs");
              
               $.ajax({
            method: 'GET',
            url : "https://www.api.omurserdar.com/api/kurumsal/",
            data : {id:kurid},
            contentType: "application/json",
            success: function(dataKurBilgi){
                spinkaldir(".ktbs");
                
                var dkb=dataKurBilgi.kurumsalbilgileri;
                var adres=dkb.adres;
                var minTutar=dkb.minAlimTutar
                var acikMiDurum=dkb.acikMi;
                var deger="";
                if(acikMiDurum==0)  
                    deger="KAPALI - Sipariş Verilemez";
                else
                    deger="AÇIK - Sipariş Verilebilir";
                    $(".kca").html("<h2> Kurumsal:  <span class='badge badge-primary'>"+dkb.ad +" - "+dkb.kullaniciadi+"</span></h2>");
                $(".kcilveilce").html("<h2> İl / İlçe: <span class='badge badge-primary'>"+dkb.il_adi +" / "+dkb.ilce_adi+"</span></h2>");
                $(".kcem").html("<h2> Email: <span class='badge badge-primary'>"+dkb.email+"</span></h2>");
                $(".kcadres").html("<h2> Adres: <span class='badge badge-primary'>"+dkb.adres+"</span></h2>");
                
                $(".kcmin").html("<h2> Minimum Alım Tutarı: <span class='badge badge-primary'>"+dkb.minAlimTutar+" ₺</span></h2>");
                $(".kcmin").attr("data-id",dkb.minAlimTutar);
                
                 $(".kcacik").html("<h2> Sipariş Verilebilir Mi? : <span class='badge badge-primary'>"+deger+"</span></h2>");
                 $(".kcacik").attr("data-id",acikMiDurum);
                 
                $(".kckt").html("<h2> Kayıt Tarihi: <span class='badge badge-primary'>"+tr_tarih(dkb.kayit_tarihi)+"</span></h2>");
                $(".kcislem").html("<button type='button' class='btn btn-outline-primary mt-1 btnkurduzenle'><i class='fas fa-edit'></i> DÜZENLE</button>");
                
                $('.btnkurduzenle').on('click', function(){ 
                //alert("AÇIK MI: "+acikMiDurum+" Min tutar: "+minTutar);
                
                //
                var metoptAlim="";
                if(acikMiDurum==0)
                    metoptAlim='<option value="0" selected>KAPALI - Sipariş Verilemez</option><option value="1">AÇIK - Sipariş Verilebilir</option>';
                else
                    metoptAlim='<option value="1" selected>AÇIK - Sipariş Verilebilir</option><option value="0">KAPALI - Sipariş Verilemez</option>';
               
             
                                    
                                    //confirm kurumsalDüzenle  
                                 $.confirm({
                type:'purple',
                title: '<b class="text-info"> Kurumsal Bilgi Düzenleme</b>',
                content: '' +
                '<form action="" class="formName">' +
                '<label>Minimum Alım Tutarı Fiyat: </label>' +
                '<input type="number" step="any" min="1" max="100" value="'+minTutar+'" class="form-control duzenleminfiyat" required />' +
                '</div>' +
                '<div class="form-group">' +
                 '<label>Adres:  </label>' +
                 '<input type="text" class="form-control" id="duzenleadres" value="'+adres+'"/>' +
                 '</div>' +
                 '<div class="form-group">' +
                 '<label>Alım Durumu: </label>' +
                 '<select class="form-control" id="duzenlealimselect">'+metoptAlim+'</select>' +
                 '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Gönder',
                        btnClass: 'btn-blue btnkbilgigonder',
                        action: function(){
                              var pkmin=$(".duzenleminfiyat").val();
                              var pkalim=$("#duzenlealimselect option:selected" ).val();
                              var padres=$("#duzenleadres").val().trim();
                            if(pkmin==minTutar && pkalim==acikMiDurum && padres==adres){
                                var diDegisikYok=$.dialog('<b class="text-danger">Değişiklik Yapılmadı !</b>');
                                mesajKapat(diDegisikYok);     
                                return false;
                            }
                            else if(padres==""){
                                var diaAdresYok=$.dialog('<b class="text-danger">Adres Boş Bırakılamaz !</b>');
                                mesajKapat(diDegisikYok);     
                                return false;
                            }
                            else if(pkmin<0){
                                var diMinNega=$.dialog('<b class="text-danger">Minimum Alım Negatif Değer İçermemelidir !</b>');
                                mesajKapat(diMinNega);     
                                return false;
                            }
                               else{
                                  //put işlemi vesonrasında js trigger click
                                  var dobje={"id":kurid,"acik":pkalim,"min":pkmin,"adres":padres};
                            dobje=JSON.stringify(dobje);
                            //alert(dobje);
                                 $.ajax({
                                method: 'PUT',
                                url : "https://www.api.omurserdar.com/api/kurumsal/",
                                //dataType: "json",
                                //contentType: "application/json"
                                data : dobje,
                                success: function(apikurbilgiduz){
                                    if(apikurbilgiduz.mesaj=="güncellendi"){
                                var diKurumGuncellendi=$.dialog({
                                    type:'green',
                                    title: '<b class="text-success">Güncellendi</b>',
                                    content: 'Kurumsal Bilgileri Güncellendi'
                                });
                                mesajKapat(diKurumGuncellendi);
                            $('.nav-pills a[href="#pills-kurbilgilerim"]').trigger('click'); 
                                }
                                // $('.btnkurAd"]').trigger('click'); burada pills-cevrem güncelletmeyi deneyeceğim 
                                //yapamam çünkü açık değil soket ile yapabilirm
                                

                                
                                     }//PUT succes
                                 }); //PUT ajax
                            } //değişiklik varsa
                    } //dialog action
                    },
                cancel: {
                         text:'İptal et'
                    },
             
            }
            }); //confirm
           }); //btn on düzenle
            }//success
               }); //ajax
  }); //son kurbilgi tab on click
            
 
  </script>
<!-- SON KUR BİLGİLERİM JS -->
           
           
           <!-- KURUMSAL SİPARİŞ TAB -->
            <div class="tab-pane" id="pills-kursiparislerim" role="tabpanel" aria-labelledby="pills-kursiparislerim-tab">
                <div class="ktss"></div>
                <div class="sipkurDiv"></div>
            </div>
            <!-- SON KURUMSAL SİPARİŞ TAB --> 
            
            <!-- MENÜLER TAB -->
            <div class="tab-pane" id="pills-kurtabMenuler" role="tabpanel" aria-labelledby="pills-kurtabMenuler-tab">
                <div class="ktms"></div>
                
             <button type="button" class="btnmenuekle btn btn-outline-primary mb-2"><i class="fas fa-plus"></i> Menü Ekle</button> 
             <span class="menusayiyazi"></span>
                <hr/>
                <div class="sipmenuDiv"></div>

            </div>
            <!--SON MENÜLER TAB -->    
            
            <!-- ENVANTER TAB -->
          <div class="tab-pane" id="pills-kurEnvanter" role="tabpanel" aria-labelledby="pills-kurEnvanter-tab">
              <div class="ktes"></div>
           <button type="button" class="btnenvekle btn btn-outline-primary mb-2"><i class="fas fa-plus"></i> Envanter Ekle</button> <span class="sipenvyazi"></span>
                          
                     <!--ENVEKLE Modal -->
            <div id="modalenvekle" class="modal fade" role="dialog">
              <div class="modal-dialog">
            
                <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">Envanter Ekle</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  
                  <div class="modal-body envEkle">
                    <form id="envekleform">
                      <div class="form-group">
                        <label for="ekletabselect" class="col-form-label">Menü Seçim:</label>
                        <select class="form-control" id="ekletabselect"></select>
                      </div>

                      <div class="form-group">
                        <label for="ekleenvad" class="col-form-label">Ad:</label>
                        <input type="text" placeholder="Örn: Köfte Burger" class="form-control" id="ekleenvad">
                      </div>
          
                      <div class="form-group">
                        <label for="ekleenvtanim" class="col-form-label">Tanım:</label>
                        <input type="text" placeholder="Örn: Patates Kızartması + Kutu İçecek" class="form-control" id="ekleenvtanim">
                      </div>
          
          
                       <div class="form-group">
                          <label class="control-label">Adet Fiyat: </label>
                          <div class="form-group">
                            <div class="input-group mb-3">
                             <input type="number" step="any" min="0.1" max="100" placeholder="Örn: 5 ya da 5.99" class="form-control" id="ekleenvfiyat">
                              <div class="input-group-append">
                                <span class="input-group-text">&#8378;</span>
                              </div>
                            </div>
                          </div>
                       </div>
        
                    <button type="button" class="btnenveklegonder btn btn-primary btn-lg btn-block">Gönder</button>
                </form>
                  </div>
                
                </div>
            
              </div>
            </div>
             <!--SON ENVEKLE Modal -->
                <!--<div class="sipenvSonraDiv row"></div>-->
                <div class="sipenvDiv"></div>
            </div>
            <!--SON ENVANTER TAB -->  
            
            
            <!-- HIZLI İŞLEMLER TAB -->
            <div class="tab-pane" id="pills-kurHizli" role="tabpanel" aria-labelledby="pills-kurHizli-tab">
          
            <nav>
               <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-sil-tab" data-toggle="tab" href="#nav-sil" role="tab" aria-controls="nav-sil" aria-selected="true"><i class="fas fa-trash fa-2x"></i>      Çoklu Envanter Silme</a>
                <a class="nav-item nav-link" id="nav-alinabilir-tab" data-toggle="tab" href="#nav-alinabilir" role="tab" aria-controls="nav-alinabilir" aria-selected="false"><i class="fas fa-lock-open fa-2x"></i> AÇIK - Sipariş Verilebilir Envanterler</a>
                <a class="nav-item nav-link" id="nav-alinamaz-tab" data-toggle="tab" href="#nav-alinamaz" role="tab" aria-controls="nav-alinamaz" aria-selected="false"><i class="fas fa-lock fa-2x"></i> KAPALI - Sipariş Verilemez Envanterler</a>
              </div>
            </nav>
          
    
            
            
    <div class="tab-content" id="nav-tabContent">
            
            <!-- coklu sil htm -->
        <div class="tab-pane show active" id="nav-sil" role="tabpanel" aria-labelledby="nav-sil-tab">
            <div class="kthss"></div>
            
            <div class="container">
                   
                <form id="frmsil">
                    <div class="row coklusil">
                    </div>
                </form>     
            </div>
        </div>
          <!-- son coklu sil htm -->
          
          
          <!-- coklu sil js -->
<script>
$(document).ready(function(){
    
    $('.nav-pills a[href="#pills-kurHizli"]').on("click",function(){
        $('.nav-tabs a[href="#nav-sil"]').trigger("click");
    });
});
      /*    
function removeFirst(arr, func){
for (var i=0; i<arr.length; i++){
        if (func.call(arr[i])){
            arr.splice(i,1);
            return arr;
        }
    }
}     
*/

$('.nav-tabs a[href="#nav-sil"]').on("click",function(){
    var kurid=$(".kid").data("id");
    //$(".coklusil").html('<i class="fas fa-spinner fa-pulse fa-4x"></i>');
    spinekle(".kthss");
    $.ajax({
        method: 'GET',
        url : "https://www.api.omurserdar.com/api/kurumsal/index.php",
        data : {id:kurid},
        contentType: "application/json",
        beforeSend:function(){
          //$("#yukleniyorModal").modal('show');
        },
        success: function(dataKur){
    spinkaldir(".kthss");
            if(dataKur.hata==false){
                //console.log(dataKur.kurumsalEnv);
                if(dataKur.kurumsalEnv.length>0){
                    var btngrup='<div class="col-md-12 mt-2 mb-2"><input type="checkbox" id="tumunusec" class="btntumsec ml-2"> Tümünü Seç <span class="lead"> | Toplam <b class="badge badge-primary">'+dataKur.kurumsalEnv.length+'</b> kayıt bulundu </span><span class="badge badge-info" id="spsecsilsay" style="font-size:16px"></span><button type="button" style="cursor:no-drop;" class="btnCokluEnvSil btn btn-outline-danger ml-2" disabled><i class="fas fa-trash"></i> Seçilileri Sil</button></div>';
                //$(".coklusil").append(btngrup);
                var cevap="";
            $.each(dataKur.kurumsalEnv,function(i,item){
               /* if(item.alim==0)
                    cevap+="<div class='col-md-4'><input type='checkbox' name='chDizi[]' data-id="+item.envanterid+" id='ch"+item.envanterid+"' value='"+item.ad+"'> <span title='sipariş verilemez' class='badge badge-danger'>"+item.ad+"</span></div>";
                else
                    cevap+="<div class='col-md-4'><input type='checkbox' name='chDizi[]' data-id="+item.envanterid+" id='ch"+item.envanterid+"' value='"+item.ad+"'> <span title='sipariş verilebilir' class='badge badge-success'>"+item.ad+"</span></div>";
                
                */
                cevap+="<div class='col-md-4'><input class='chteksec' type='checkbox' name='chEnv[]' data-id="+item.envanterid+" id='ch"+item.envanterid+"' value='"+item.ad+"'> <span id='"+item.envanterid+"'>"+item.ad+"</span></div>";
                $(".coklusil").html(btngrup+cevap);
                }); //each
            }
                else{
                $(".coklusil").html("veri yok");
                return false;
                }
            }//hata=!false ise hata var
            else{
                $(".coklusil").html("KURUMSAL BİLGİLERİ GETİRİLİRKEN HATA OLUŞTU");
                return false;
            }
            //$("#yukleniyorModal").modal('hide');
            
              //secme islemleri
             var secililer=[];var sayi=0;
             var yazisayac=0;
             $(".btntumsec").on("change",function(){
                 
                if(this.checked){
                  $(".chteksec").each(function(){
                    this.checked=true;
                  })
                  $('input[name="chEnv[]"]').next("span").addClass("badge badge-primary").css( "fontSize", "14px" );
                    $(".btnCokluEnvSil").removeAttr("disabled");
                    $(".btnCokluEnvSil").prop("style","cursor:pointer;");
                  //$("#spsecsilsay").html("seçili sayı: "+yazisayac);
                  $("#spsecsilsay").html("tüm kayıtlar seçildi");
                }else{
                  $(".chteksec").each(function(){
                    this.checked=false;
                    $('input[name="chEnv[]"]').next("span").removeClass("badge badge-primary").css( "fontSize", "14px" );
                  })
                  //alert(yazisayac);
                  $(".btnCokluEnvSil").prop("style","cursor:no-drop;");
                  $(".btnCokluEnvSil").attr("disabled","disabled");
                  $("#spsecsilsay").html("");
                }
            }); 
            
              $(".chteksec").on("click",function () {
                if ($(this).is(":checked")){
                  var isAllChecked = 0;
                  $(".chteksec").each(function(){
                    if(!this.checked)
                       isAllChecked = 1;
                  })              
                  if(isAllChecked == 0){ 
                      $(".btntumsec").prop("checked", true);
                      $("#spsecsilsay").html("tüm kayıtlar seçildi");
                  }
                }else {
                  $(".btntumsec").prop("checked", false);
                  $("#spsecsilsay").html("");
                }
             });
             
              var sayi=0;
             $('input[name="chEnv[]"]').on("change",function() {
                
                sayi=$('input[name="chEnv[]"]:checked').length;
                //console.log(sayi);
                if(sayi>0){
                    $(".btnCokluEnvSil").removeAttr("disabled");
                    $(".btnCokluEnvSil").prop("style","cursor:pointer;");
                }else{
                    $(".btnCokluEnvSil").prop("style","cursor:no-drop;");
                    $(".btnCokluEnvSil").attr("disabled","disabled");
                }
             if($(this).prop("checked")){
                 $(this).next("span").addClass("badge badge-primary").css( "fontSize", "14px");
                 }
             else{
                    $(this).next("span").removeClass();
                 }
                 //$("#spsecsilsay").html(yazisayac);
                });
              
              //secme islemleri son
            
            //SEÇİLİ SİL Click
            $(".btnCokluEnvSil").on("click",function(){
                  var silinecekYazi="";
                  var sayac=0;
                  var secililer=[];
                  $(".chteksec").each(function(){
                      if(this.checked){
                        silinecekYazi+="<b class='badge badge-danger'>"+$(this).val()+"</b><br>";
                        sayac++;
                        var jsonObj='{ "ad":"'+$(this).val()+'","envid":"'+$(this).attr("data-id")+'"}';
                            jsonObj=JSON.parse(jsonObj);
                            secililer.push(jsonObj);
                      }
                  });
                  //console.log(secililer);
                  if(sayac==0){
                      var diasecilienvyok=$.dialog("Seçili envanter yok");
                      mesajKapat(diasecilienvyok,1500);
                      return false;
                  }
                  var seciliSilinecekEnvDizi=[];
                 $.each(secililer,function(i,item){
                         seciliSilinecekEnvDizi.push(parseInt(item.envid));
                  });
                  //console.log(seciliSilinecekEnvDizi);
                $.confirm({
                type:'red',    
                title: '<b class="text-danger">Silmek istediğine emin misin?</b>',
                content:  "<b class='badge badge-primary'>"+sayac+"</b> adet envanter seçildi <br><b class='text-danger'> Silinecek Envanter Listesi </b><br>"+silinecekYazi,
                //content:JSON.stringify(secililer),
                buttons: {
                    evet: {
                        text: 'Evet, sil',
                        btnClass: 'btn-danger',
                        action: function () {
                                $.ajax({
                                method: 'DELETE',
                                url : "https://www.api.omurserdar.com/api/envanter/?silid="+seciliSilinecekEnvDizi,
                                success: function(apienvtoplusil){
                                    if(apienvtoplusil.mesaj=="silindi"){
                                        
                                        var diaTopEnvSil=$.dialog({
                                            type:'dark',    
                                            title: '<b class="text-info"          >Silindi</b>',
                                            content:  "<b class='badge badge-primary'>"+sayac+"</b> adet kayıt silinmiştir <br>"});
                                        mesajKapat(diaTopEnvSil);
                                        
                                        
                                        //sildikten sonra tab click tetiklen
                                        $('.nav-tabs a[href="#nav-sil"]').trigger("click");
                                        
                                    }
                                }
                                 });
                                 
                        }
                    },
                    cancel: {
                         text:'İptal et'
                    }
                }
            });
                    
                }); 
            //});
            
            /*
            $('input[name="chDizi[]"]').click(function(){
            if($(this).prop("checked") == true){
                //console.log("Checkbox is checked."+$(this).attr("value")+"");
            }
            else if($(this).prop("checked") == false){
                //console.log("Checkbox is unchecked.");
            }
        });
        */
            
              }
        });
}); //nav sil click
</script>
          <!-- son coklu sil js-->


          <!-- alınabilir htm-->
          <div class="tab-pane" id="nav-alinabilir" role="tabpanel" aria-labelledby="nav-alinabilir-tab">
              <div class="kthalinabilir"></div>
                <div class="container">
                   
                
                    <div class="row dcalinamazyap">
                    </div>

            </div>
          </div>
          <!-- son alınabilir htm-->
          
          <!-- alinabilir->alinamaz guncelle js -->
          <script>
              $('.nav-tabs a[href="#nav-alinabilir"]').on("click",function(){
                var kurid=$(".kid").data("id");
            
                spinekle(".kthalinabilir");
                
                 $.ajax({
                    method: 'GET',
                    url : "https://www.api.omurserdar.com/api/kurumsal",
                    data : {id:kurid},
                    contentType: "application/json",
                    success: function(dataKur){
                spinkaldir(".kthalinabilir");
                    if(dataKur.hata==false){
                //console.log(dataKur.kurumsalEnv);
                var alinabilirsayi=0;
                if(dataKur.kurumsalEnv.length>0){
                     $.each(dataKur.kurumsalEnv,function(i,item){
                        if(item.alim==1)
                            alinabilirsayi++
                     });
                    var btngrup='<div class="col-md-12 mt-2 mb-2"><span class="lead"> TOPLAM <b class="badge badge-info">'+alinabilirsayi+'</b> adet envanter alınabilir durumda. Seçili eleman sayısı: <b id="bsecelsay" class="badge badge-primary">0</b> <button type="button" style="cursor:no-drop;" class="btncealinamazyap btn btn-outline-danger ml-2" disabled><i class="fas fa-lock"></i> Seçilileri ALINAMAZ Yap</button></span></div>';
                //$(".coklusil").append(btngrup);
                var cevap="";
            $.each(dataKur.kurumsalEnv,function(i,item){
               /* if(item.alim==0)
                    cevap+="<div class='col-md-4'><input type='checkbox' name='chDizi[]' data-id="+item.envanterid+" id='ch"+item.envanterid+"' value='"+item.ad+"'> <span title='sipariş verilemez' class='badge badge-danger'>"+item.ad+"</span></div>";
                else
                    cevap+="<div class='col-md-4'><input type='checkbox' name='chDizi[]' data-id="+item.envanterid+" id='ch"+item.envanterid+"' value='"+item.ad+"'> <span title='sipariş verilebilir' class='badge badge-success'>"+item.ad+"</span></div>";
                
                */if(item.alim==1)
                cevap+="<div class='col-md-4'><input class='chenvalinamazyap' type='checkbox' name='chEnv[]' data-id="+item.envanterid+" id='ch"+item.envanterid+"' value='"+item.ad+"'> <span id='"+item.envanterid+"' style='font-size:14px' class='badge badge-success'>"+item.ad+"</div>";
                $(".dcalinamazyap").html(btngrup+cevap);
                }); //each
            }
                else{
                $(".dcalinamazyap").html("veri yok");
                return false;
                }
            }//hata=!false ise hata var
            else{
                $(".dcalinamazyap").html("KURUMSAL BİLGİLERİ GETİRİLİRKEN HATA OLUŞTU");
                return false;
            }
            //$("#yukleniyorModal").modal('hide');
            
              //secme islemleri
             var secililer=[];var sayi=0;
             var yazisayac=0;

              var sayi=0;
             $('input[name="chEnv[]"]').change(function() {
                
                sayi=$('input[name="chEnv[]"]:checked').length;
                //console.log(sayi);
                if(sayi>0){
                    $(".btncealinamazyap").removeAttr("disabled");
                    $(".btncealinamazyap").prop("style","cursor:pointer;");
                }else{
                    $(".btncealinamazyap").prop("style","cursor:no-drop;");
                    $(".btncealinamazyap").attr("disabled","disabled");
                }
             if($(this).prop("checked")){
                 yazisayac++;
                 $(this).next("span").removeClass().addClass("badge badge-primary").css( "fontSize", "16px");
                 }
             else{
                    yazisayac--;
                    $(this).next("span").removeClass().addClass("badge badge-success").css( "fontSize", "14px");
                 }
                 
                     $("#bsecelsay").html(yazisayac);
                 
                });
              
              //secme islemleri son
            
            //SEÇİLİ ALINAMAZ YAP Click
            $(".btncealinamazyap").on("click",function(){
                
                  var guncellencekYazi="";
                  var sayac=0;
                  var secililer=[];
                  $(".chenvalinamazyap").each(function(){
                      if(this.checked){
                        guncellencekYazi+="<b class='badge badge-danger'>"+$(this).val()+"</b><br>";
                        sayac++;
                        var jsonObj='{ "ad":"'+$(this).val()+'","envid":"'+$(this).attr("data-id")+'"}';
                            jsonObj=JSON.parse(jsonObj);
                            secililer.push(jsonObj);
                      }
                  });
                  //console.log(secililer);
                  if(sayac==0){
                      var diasecilienvyokcha1=$.dialog("Seçili envanter yok");
                      mesajKapat(diasecilienvyokcha1,1500);
                      return false;
                  }
                  var guncEnvDizi=[];
                 $.each(secililer,function(i,item){
                         guncEnvDizi.push(parseInt(item.envid));
                  });
                  //console.log(guncEnvDizi);
                var conf=$.confirm({
                type:'red',    
                title: '<b class="text-info"> Güncellemek istediğine emin misin?</b>',
                content:  "<b class='badge badge-primary'>"+sayac+"</b> adet kayıt seçildi <br><b class='text-danger'><i><u>'Sipariş Verilemez'</u></i> Yapılacak Envanter Listesi </b><br>"+guncellencekYazi,
                onOpenBefore: function () {
                          conf.showLoading();
                    },
                onContentReady: function () {
                          conf.hideLoading();
                    },
                buttons: {
                    evet: {
                        text: 'Evet, Güncelle',
                        btnClass: 'btn-danger',
                        action: function () {
                            
                             var dobje={"topguncid":guncEnvDizi};
                            dobje=JSON.stringify(dobje);
                            
                                $.ajax({
                                method: 'PUT',
                                url : "https://www.api.omurserdar.com/api/envanter/",
                                data:dobje,
                                success: function(apienvtoplualinamazresp){
                                    if(apienvtoplualinamazresp.mesaj=="güncellendi"){
                                        var diaTopEnvGuncellendi=$.dialog({
                                            type:'info',    
                                            title: '<b class="text-info"          >Güncellendi</b>',
                                            content:  "<b class='badge badge-primary'>"+sayac+"</b> adet kayıt başarıyla güncellenmiştir <br>"});
                                        mesajKapat(diaTopEnvGuncellendi);
                                        
                                        //guncellendikten sonra tab click tetiklen
                                        $('.nav-tabs a[href="#nav-alinabilir"]').trigger("click");
                                        
                                    }
                                }
                                 });
                                 
                        }
                    },
                    cancel: {
                         text:'İptal et'
                    }
                }
            });
            
                    
                }); 
            //});
            
            /*
            $('input[name="chDizi[]"]').click(function(){
            if($(this).prop("checked") == true){
                //console.log("Checkbox is checked."+$(this).attr("value")+"");
            }
            else if($(this).prop("checked") == false){
                //console.log("Checkbox is unchecked.");
            }
        });
        */
            
              }
        });
}); 
          </script>
          <!-- alinabilir->alinamaz guncelle js son -->
          
          <!-- alınamaz htm-->
          <div class="tab-pane" id="nav-alinamaz" role="tabpanel" aria-labelledby="nav-alinamaz-tab">
              <div class="kthalinamazs"></div>
                <div class="container">
                   
                    <div class="row dcalinabiliryap">
                    </div>

            </div>
          </div>
          <!-- son alınamaz htm-->
            
            
             <!-- alinamaz->alinabilir guncelle js -->
          <script>
              $('.nav-tabs a[href="#nav-alinamaz"]').on("click",function(){
                var kurid=$(".kid").data("id");
                spinekle(".kthalinamazs");
                 $.ajax({
                    method: 'GET',
                    url : "https://www.api.omurserdar.com/api/kurumsal",
                    data : {id:kurid},
                    contentType: "application/json",
                    beforeSend:function(){
          //$("#yukleniyorModal").modal('show');
                    },
                    success: function(dataKur){
                        spinkaldir(".kthalinamazs");
    if(dataKur.hata==false){
                //console.log(dataKur.kurumsalEnv);
                var alinamazsayi=0;
        if(dataKur.kurumsalEnv.length>0){
            $.each(dataKur.kurumsalEnv,function(i,item){
                if(item.alim==0)
                            alinamazsayi++
            });
                     
            var btngrup='<div class="col-md-12 mt-2 mb-2"><span class="lead"> TOPLAM <b class="badge badge-info">'+alinamazsayi+'</b> adet envanter alınamaz durumda. Seçili eleman sayısı: <b id="bsecalimyokvarsay" class="badge badge-primary">0</b> <button type="button" style="cursor:no-drop;" class="btncealinabiliryap btn btn-outline-danger ml-2" disabled><i class="fas fa-lock-open"></i> Seçilileri ALINABİLİR Yap</button></span></div>';
                //$(".coklusil").append(btngrup);
            var cevap="";
            $.each(dataKur.kurumsalEnv,function(i,item){
               if(item.alim==0){
                   cevap+="<div class='col-md-4'><input class='chenvalinabiliryap' type='checkbox' name='chEnv[]' data-id="+item.envanterid+" id='ch"+item.envanterid+"' value='"+item.ad+"'> <span id='"+item.envanterid+"' style='font-size:14px' class='badge badge-danger'>"+item.ad+"</div>";
               }
            }); //each
            $(".dcalinabiliryap").html(btngrup+cevap);
            }//env.len>0 degilse
        else{
            $(".dcalinabiliryap").html("veri yok");
            return false;
            }
    }//hata=!false ise hata var
    else{
        $(".dcalinabiliryap").html("KURUMSAL BİLGİLERİ GETİRİLİRKEN HATA OLUŞTU");
        return false;
        }
            //$("#yukleniyorModal").modal('hide');
            
              //secme islemleri
             var secililer=[];var sayi,yazisayac=0;
             $('input[name="chEnv[]"]').change(function() {
                
                sayi=$('input[name="chEnv[]"]:checked').length;
                //console.log(sayi);
                if(sayi>0){
                    $(".btncealinabiliryap").removeAttr("disabled");
                    $(".btncealinabiliryap").prop("style","cursor:pointer;");
                }else{
                    $(".btncealinabiliryap").prop("style","cursor:no-drop;");
                    $(".btncealinabiliryap").attr("disabled","disabled");
                }
             if($(this).prop("checked")){
                 yazisayac++;
                 $(this).next("span").removeClass().addClass("badge badge-primary").css( "fontSize", "16px");
                 }
             else{
                    yazisayac--;
                    $(this).next("span").removeClass().addClass("badge badge-danger").css( "fontSize", "14px");
                 }
                 
                     $("#bsecalimyokvarsay").html(yazisayac);
                 
                });
              
              //secme islemleri son
            
            //SEÇİLİ ALINABİLİR YAP Click
            $(".btncealinabiliryap").on("click",function(){
                
                  var guncellencekYazi="";
                  //var sayac=0;
                  var secililer=[];
                  $(".chenvalinabiliryap").each(function(){
                      if(this.checked){
                        guncellencekYazi+="<b class='badge badge-danger'>"+$(this).val()+"</b><br>";
                        //sayac++;
                        var jsonObj='{ "ad":"'+$(this).val()+'","envid":"'+$(this).attr("data-id")+'"}';
                            jsonObj=JSON.parse(jsonObj);
                            secililer.push(jsonObj);
                      }
                  });
                  //console.log(secililer);
                  if(yazisayac==0){
                      var diasecilienvyokcha2=$.dialog("Seçili envanter yok");
                      mesajKapat(diasecilienvyokcha2,1500);
                      return false;
                  }
                  var guncEnvDizi=[];
                 $.each(secililer,function(i,item){
                         guncEnvDizi.push(parseInt(item.envid));
                  });
                  //console.log(guncEnvDizi);
               var confAlinabilirYap=$.confirm({
                type:'info',    
                title: '<b class="text-info"> Güncellemek istediğine emin misin?</b>',
                content:  "<b class='badge badge-primary'>"+yazisayac+"</b> adet kayıt seçildi <br><b class='text-danger'> <i><u>'Sipariş Verilebilir'</u></i> Yapılacak Envanter Listesi </b><br>"+guncellencekYazi,
                
                onOpenBefore: function () {
                          confAlinabilirYap.showLoading();
                    },
                onContentReady: function () {
                          confAlinabilirYap.hideLoading();
                    },
                buttons: {
                    evet: {
                        text: 'Evet, Güncelle',
                        btnClass: 'btn-danger',
                        action: function () {
                            
                             var dobje={"topguncid":guncEnvDizi,"secim":"alinabiliryap"};
                            dobje=JSON.stringify(dobje);
                            
                                $.ajax({
                                method: 'PUT',
                                url : "https://www.api.omurserdar.com/api/envanter/",
                                data:dobje,
                                success: function(apienvtoplualinamazresp){
                                    if(apienvtoplualinamazresp.mesaj=="güncellendi"){
                                        var diaTopEnvGuncellendi=$.dialog({
                                            closeIcon:false,
                                            type:'info',    
                                            title: '<b class="text-info"          >Güncellendi</b>',
                                            content:  "<b class='badge badge-primary'>"+yazisayac+"</b> adet kayıt güncellenmiştir <br>",
                                            onClose: function () {
                                                //guncellendikten sonra tab click tetiklen
                                        $('.nav-tabs a[href="#nav-alinamaz"]').trigger("click");
                                            }
                                        });
                                        mesajKapat(diaTopEnvGuncellendi);
                                        
                                    }
                                }
                                 });
                                 
                        }
                    },
                    cancel: {
                         text:'İptal et'
                    }
                }
            });
            
                    
                }); 
            //});
            
            /*
            $('input[name="chDizi[]"]').click(function(){
            if($(this).prop("checked") == true){
                //console.log("Checkbox is checked."+$(this).attr("value")+"");
            }
            else if($(this).prop("checked") == false){
                //console.log("Checkbox is unchecked.");
            }
        });
        */
            
              }
        });
}); 
          </script>
          <!-- alinamaz->alinabilir guncelle js son -->
     
    </div>
<!-- SON HIZLI İŞLEMLER -->
            
            
             <!--         *********************************************  SON KUR HTML      *********************************************            -->
            
            <!--         *********************************************    KUR TABS JS     *********************************************            -->
            <script type="text/javascript">
            $('[data-toggle="tooltip"]').tooltip();
         
            //kurEnvanter click
       $(document).ready(function(){
           var renkler=["success","primary","info","danger","warning"];
        $('.nav-pills a[href="#pills-kurEnvanter"]').on("click",function(){
               var kurid=$(".kid").data("id");
              spinekle(".ktes");
          $.ajax({
            method: 'GET',
            url : "https://www.api.omurserdar.com/api/kurumsal/index.php",
            data : {id:kurid},
            contentType: "application/json",
            success: function(dataTabKurEnv){
                //var dataSipEnv = JSON.stringify(dataSipEnv);
                spinkaldir(".ktes");
                
                if(dataTabKurEnv.hata==false){
                    if(dataTabKurEnv.kurumsalEnv.length>0){
                    $(".sipenvyazi").html("<span class='lead'>Toplam <b class='badge badge-pill badge-primary'>"+dataTabKurEnv.kurumsalEnv.length+" </b> kayıt bulundu</span>");    
                    }
                    else{
                    $(".sipenvyazi").html("<span class='lead'> kayıt yok </span>");
                    }
                    var metin="<div class='row'>";
                    
                    for (i in dataTabKurEnv.kurumsalEnv){
            metin+='<div id="cardenv'+dataTabKurEnv.kurumsalEnv[i].envanterid+'" class="card border-'+random_item(renkler)+' mb-2 ml-2 col-md-4" style="max-width: 20rem;"><div class="card-body">';
            metin+='<h2 class="card-title text-'+random_item(renkler)+'"><span id="keid'+dataTabKurEnv.kurumsalEnv[i].envanterid+'" class="font-weight-bold">'+dataTabKurEnv.kurumsalEnv[i].ad+'<span></h2>';
            if(dataTabKurEnv.kurumsalEnv[i].tanim!=" ")
                 metin+='<hr/><h4 id="envtanim'+dataTabKurEnv.kurumsalEnv[i].envanterid+'">Tanım: <span class="text-info">'+dataTabKurEnv.kurumsalEnv[i].tanim+'</span></h4>';
            else
                 metin+='<hr/><h4 id="envtanim'+dataTabKurEnv.kurumsalEnv[i].envanterid+'">Tanım: <span class="badge badge-info"> Tanımlanmadı </span></h4>';
                 
            metin+='<hr/><p class="lead">Fiyat: <span class="fiyat'+dataTabKurEnv.kurumsalEnv[i].envanterid+' badge badge-pill badge-info">'+dataTabKurEnv.kurumsalEnv[i].fiyat+' &#8378;</span></p>';
            
            metin+='<hr/><p class="lead">Bulunduğu Menü: <span class="envtabad'+dataTabKurEnv.kurumsalEnv[i].envanterid+' badge badge-pill badge-info" id="envtabid'+dataTabKurEnv.kurumsalEnv[i].tabid+'" data-id="'+dataTabKurEnv.kurumsalEnv[i].tabid+'">'+dataTabKurEnv.kurumsalEnv[i].tabad+'</span></p>';
            
            if(dataTabKurEnv.kurumsalEnv[i].alim=="0")
                metin+='<h3><span class="badge badge-danger envalim'+dataTabKurEnv.kurumsalEnv[i].envanterid+'" data-id="0"><i class="fas fa-lock"></i> KAPALI - Sipariş Verilemez</span></h3>';
            else
             metin+='<h3><span class="badge badge-success envalim'+dataTabKurEnv.kurumsalEnv[i].envanterid+'" data-id="1"><i class="fas fa-lock-open"></i> AÇIK - Sipariş Verilebilir</span></h3>';
            
            metin+='<button type="button" id="'+dataTabKurEnv.kurumsalEnv[i].ad+'" data-id="'+dataTabKurEnv.kurumsalEnv[i].envanterid+'" class="btnenvduzenle btnedid'+dataTabKurEnv.kurumsalEnv[i].envanterid+' btn btn-outline-primary pr-2 mr-2"><i class="fas fa-edit"></i> Düzenle</button>';
            
            metin+='<button type="button" id="'+dataTabKurEnv.kurumsalEnv[i].ad+'" data-id="'+dataTabKurEnv.kurumsalEnv[i].envanterid+'" class="btnenvsil btnesid'+dataTabKurEnv.kurumsalEnv[i].envanterid+' btn btn-outline-danger pr-2 mr-2"><i class="fas fa-trash-alt"></i> Sil</button> </div></div>';
                            }//for
                            $(".sipenvDiv").html(metin+"</div>");

                             } //if false
                             else{
                                 $(".sipenvDiv").html("hata");
                                 }
            }//tab menu click success
                    
                });
 
           });
           //son kurEnvanter click 
           
           //envanterekle
           
           var kurid=$(".kid").attr("data-id");
            $('.btnenvekle').on("click",function(){
               
                $.ajax({
                    method: 'GET',
                    url : "https://www.api.omurserdar.com/api/kurumsal?",
                    data : {id:kurid},
                    dataType : "json",
                    contentType: "application/json",
                    success: function(apikurtab){
                        if(apikurtab.hata==false){
                            var metx="";
                            if(apikurtab.kurumsalTumTab.length==0){
                                 var diaKurMenuYokEnv=$.dialog({
                                     type:'purple',title:'Bilgilendirme',
                                     content:"<b class='text-danger'> Envanter eklenebilmesi için en az 1 tane menü olması gerekmektedir  </b>"
                                     });
                                 mesajKapat(diaKurMenuYokEnv,2500);
                                 return false;
                             }
                        $.each(apikurtab.kurumsalTumTab, function (i, item) {
                            metx+='<option value="'+item.id+'">'+item.ad+'</option>';
                        });
                        $('#ekletabselect').html(metx);
                        $('#modalenvekle').modal('show');
                        }
                        else{
                            $.alert("Hata oluştu");
                        }
                    }
                });
            });
                                
                        //btnenveklegonder click
                        $('.btnenveklegonder').on('click', function(){
                            var dad=$("#ekleenvad").val().trim();
                            var dtanim=$("#ekleenvtanim").val().trim();
                            var dfiyat=$("#ekleenvfiyat").val().trim();
                            var dtabmenuid=$("#ekletabselect option:selected" ).val();
                            var dtabmenutext=$("#ekletabselect option:selected" ).text();
                            //alert(dad+" "+dtanim+" "+dfiyat+" "+dtabmenuid);
                               
                            if(dad=="" || dfiyat==""){
                                //$("#ekleenvad").addClass("is-invalid");
                                var diEnvFiyAd=$.dialog('<b class="text-danger">Ad ile fiyat bilgisi boş bırakılamaz </b>');
                                mesajKapat(diEnvFiyAd);
                                return false;
                            }
                               
                               //else{
                                  //  $("#ekleenvfiyat").removeClass("is-invalid");
                                  // $("#ekleenvfiyat").addClass("is-valid");
                               //}
                                
            
                            $.ajax({
                                method: 'POST',
                                url : "https://www.api.omurserdar.com/api/envanter/index.php",
                                data : {kid:kurid,tid:dtabmenuid,ad:dad,tanim:dtanim,fiyat:dfiyat},
                                //contentType: "application/json",
                                success: function(apienvekle){
                                    if(apienvekle.mesaj=="eklendi"){
                                        var eklenenenvid=apienvekle.eklenen_env_id;
                                        resetForm($('#envekleform')); 
                                        $("#ekleenvfiyat").val("");
                                        $('#modalenvekle').modal('hide');
                                        
                                        var diEnvEklendi=$.dialog({
                                        type:'green',
                                        title: '<b class="text-success">Eklendi</b>',
                                        content: dad+' isimli envanter '+dtabmenutext+' menüsüne eklendi'
                                        });
                                        mesajKapat(diEnvEklendi);
                                        
                                        /* burayı iptal ettim çünkü eklendikten sonra envanter click tetikleniyor
                                        var eklenenenvhtml="";
                 
                                        eklenenenvhtml+='<div id="cardenv'+eklenenenvid+'" class="card border-'+random_item(renkler)+' mb-2 ml-2 col-md-4" style="max-width: 20rem;"><div class="card-body">';
                                        eklenenenvhtml+='<h2 class="card-title text-'+random_item(renkler)+'"><span id="keid'+eklenenenvid+'" class="font-weight-bold">'+dad+'</span></h2>';
                                        
                                        if(dtanim=="")
                                                eklenenenvhtml+='<hr/><h4 id="envtanim'+eklenenenvid+'">Tanım: <span class="badge badge-info"> Tanımlanmadı </span></h4>';
                                        else
                                                eklenenenvhtml+='<hr/><h4 id="envtanim'+eklenenenvid+'">Tanım: <span class="text-info">'+dtanim+'</span></h4>';
            
            eklenenenvhtml+='<hr/><p class="lead">Fiyat: <span class="fiyat'+eklenenenvid+' badge badge-pill badge-info">'+dfiyat+' &#8378;</span> </p>';
            eklenenenvhtml+='<hr/><p class="lead">Bulunduğu Menü: <span class="envtabad'+eklenenenvid+' badge badge-pill badge-info" id="envtabid'+dtabmenuid+'" data-id="'+dtabmenuid+'">'+dtabmenutext+'</span></p>';
            eklenenenvhtml+='<h3><span class="badge badge-danger envalim'+eklenenenvid+'" data-id="0"><i class="fas fa-lock"></i> KAPALI - Sipariş Verilemez</span></h3>';
                  
            eklenenenvhtml+='<button type="button" id="'+dad+'" data-id="'+eklenenenvid+'" class="btnenvduzenle btn btnedid'+eklenenenvid+' btn-outline-primary pr-2 mr-2"><i class="fas fa-edit"></i> Düzenle</button>';
            eklenenenvhtml+='<button type="button" id="'+dad+'" data-id="'+eklenenenvid+'" class="btnenvsil btn btnesid'+eklenenenvid+' btn-outline-danger pr-2 mr-2"><i class="fas fa-trash-alt"></i> Sil</button> </div></div>';
                           
                            $(".sipenvSonraDiv").append(eklenenenvhtml);
                            */
                                  //$('.btnenveklegonder').off('click');
                                  $('.nav-pills a[href="#pills-kurEnvanter"]').trigger('click');
                                    }
                                    else{
                                        $.alert(apienvekle.mesaj);
                                    }
                                }
                                 })
                                 
                                 
                                 
                               });
                        //son btnenveklegonder
                             
           //envanterekle
           
           //ENVANTER DÜZENLEME 
            $(document).on('click', '.btnenvduzenle', function(){
                var envid=$(this).attr("data-id");
                var ead=$("#keid"+envid).text();
                var etan=$("#envtanim"+envid).children("span").text();
                var uzunluk=$(".fiyat"+envid).val().length;
                var efiy=$(".fiyat"+envid).text().slice(0,uzunluk-2);
                var emad=$(".envtabad"+envid).text();
                var ealim=$(".envalim"+envid).attr("data-id");
                
                if(etan==" Tanımlanmadı ")
                   etan=" ";
                var metoptAlim="";
                if(ealim==0)
                    metoptAlim='<option value="0" selected>KAPALI - Sipariş Verilemez</option><option value="1">AÇIK - Sipariş Verilebilir</option>';
                else
                    metoptAlim='<option value="1" selected>AÇIK - Sipariş Verilebilir</option><option value="0">KAPALI - Sipariş Verilemez</option>';
               
                var kurid=$(".kid").attr("data-id");
                var metx="";
                
                  $.ajax({
                                method: 'GET',
                                url : "https://www.api.omurserdar.com/api/kurumsal/index.php",
                                data : {id:kurid},
                                dataType : "json",
                                contentType: "application/json",
                                success: function(apikurtab){
                                    if(apikurtab.hata==false){
                                $.each(apikurtab.kurumsalTumTab, function (i, item) {
                                    if(emad==item.ad)
                                        {
                                          metx+='<option value="'+item.id+'" selected>'+item.ad+'</option>';
                                        }
                                     else
                                    metx+='<option value="'+item.id+'">'+item.ad+'</option>';
                                    $
                                    });//each
                                    
                                   // var jcSpin=$.confirm('<i class="fas fa-spinner fa-pulse"></i> Yükleniyor');
                                    
                                    //confirm envdüzenle  
                                 $.confirm({
                type:'purple',
                title: '<b class="text-info">Envanter Düzenleme</b>',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Envanter Ad: </label>' +
                '<input type="text" value="'+ead+'" class="form-control duzenleenvad" required />' +
                '</div>' +
                '<div class="form-group">' +
                '<label>Envanter Tanım: </label>' +
                '<input type="text" value="'+etan+'" class="form-control duzenleenvtanim" required />' +
                '</div>' +
                '<div class="form-group">' +
                '<label>Envanter Fiyat: </label>' +
                '<input type="number" step="any" min="0.1" max="100" value="'+efiy+'" class="form-control duzenleenvfiyat" required />' +
                '</div>' +
                '<div class="form-group">' +
                 '<label>Envanter Menü: </label>' +
                 '<select class="form-control" id="duzenletabselect">'+metx+'</select>' +
                 '</div>'+
                 '<div class="form-group">' +
                 '<label>Alım Durumu: </label>' +
                 '<select class="form-control" id="envalimselect">'+metoptAlim+'</select>' +
                 '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Gönder',
                        btnClass: 'btn-blue btnduzgonder',
                        action: function () {
                            
                              var pead=$(".duzenleenvad").val().trim();
                              var petan=$(".duzenleenvtanim").val();
                              var pefi=$(".duzenleenvfiyat").val().trim();
                              var petmid=$("#duzenletabselect option:selected" ).val();
                              var petmad=$("#duzenletabselect option:selected" ).text();
                              var pealim=$("#envalimselect option:selected" ).val();
            
            if(pead==etan && petan==etan && pefi==efiy && petmad==emad && pealim==ealim){
               var diDegYok=$.dialog('<b class="text-danger">Değişiklik Yapılmadı !</b>');
               mesajKapat(diDegYok,3000);
                return false;
            }
            else{//değişiklik varsa
            
                $(".btnedid"+envid).attr("id",pead);
                $(".btnesid"+envid).attr("id",pead);
                $("#keid"+envid).text(pead);
                if(petan==" " || petan=="" || petan=="  ")
                   petan=" Tanımlanmadı ";
                $("#envtanim"+envid).children("span").text(petan);
                //$(".fiyat"+envid).text(pefi);
                $(".fiyat"+envid).html(pefi+" &#8378;");
               
                $(".envtabad"+envid).attr("id","envtabid"+petmid);
                $(".envtabad"+envid).attr("data-id",petmid);
                $(".envtabad"+envid).text(petmad)
                
                if(pealim!=ealim){
                    $(".envalim"+envid).attr("data-id",pealim);
                     if(pealim==0){
                        $(".envalim"+envid).removeClass("badge-success");
                        $(".envalim"+envid).addClass("badge-danger");
                        $(".envalim"+envid).html('<i class="fas fa-lock"></i> KAPALI - Sipariş Verilemez</span>');
                     }else{
                        $(".envalim"+envid).removeClass("badge-danger");
                        $(".envalim"+envid).addClass("badge-success");
                        $(".envalim"+envid).html('<i class="fas fa-lock"></i> AÇIK - Sipariş Verilebilir</span>');
                }
            }
                    
                             var dobje={"id":envid,"tid":petmid,"ad":pead,"tanim":petan,"fiyat":pefi,"alim":pealim};
                            dobje=JSON.stringify(dobje);
                            //alert(dobje);
                                 $.ajax({
                                method: 'PUT',
                                url : "https://www.api.omurserdar.com/api/envanter",
                                //dataType: "json",
                                //contentType: "application/json"
                                data : dobje,
                                success: function(apienvduz){
                                    if(apienvduz.mesaj=="güncellendi"){
                                        //$("#ma"+tid).html("<b>"+xad+"</b>");
                                        //$('#'+tad).attr("id",xad);
                                        //$('.btn'+tid).attr("id",xad);

                                var diEnvGuncel=$.dialog({
                                    type:'green',
                                    title: '<b class="text-success">Güncellendi</b>',
                                    content: 'Envanter Başarıyla Güncellendi'
                                });
                                mesajKapat(diEnvGuncel);
                                    }
                                    else{
                                var diEnvBasarisiz=$.dialog({
                                    type:'red',
                                    title: '<b class="text-danger">Başarısız İşlem</b>',
                                    content: 'Güncelleme YAPILAMADI'
                                });
                                mesajKapat(diEnvBasarisiz);
                                    }
                                },dataType: "json",
                                  contentType: "application/json"
                                 })
                                 
            }  //değişiklik varsa son   
                        }
                    },
                    cancel: {
                         text:'İptal et'
                    },
                },onContentReady: function () {
                      
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                },
                onOpenBefore: function () {
                  //  jcSpin.close();
                }
            });
                                    //son confirm
                                    
                                     }//if false
                                }//success
                           });
               
            });
           //ENVANTER DÜZENLEME SON 
            
            //ENV SİL
            $(document).on('click', '.btnenvsil', function(){
                                      var envadi=$(this).attr("id");
                                      var envid=$(this).attr("data-id");
                                     
                  $.confirm({
                type:'red',    
                title: '<b class="text-danger">Silmek istediğine emin misin?</b>',
                content: envadi+' isimli envanter silinecek...',
                buttons: {
                    evet: {
                        text: 'Evet, sil',
                        btnClass: 'btn-danger',
                        action: function () {
                      
                                 $.ajax({
                                method: 'DELETE',
                                url : "https://www.api.omurserdar.com/api/envanter/index.php?id="+envid,
                                //dataType: 'json',
                               // data : {id:envid},
                                success: function(apienvsil){
                                    if(apienvsil.mesaj=="silindi"){
                                        
                                        /*trigger tetiklendiğinden bir önemi kalmadı silme animasyonu
                                        $("#cardenv"+envid).slideUp(1000, function() {
                                            $(this).remove();
                                        });
                                        */
                                        var diEnvSil=$.dialog({
                                            type:'dark', 
                                            title: '<b class="text-danger">Silindi</b>',
                                            content: envadi+' isimli envanter silindi'
                                        });
                                        mesajKapat(diEnvSil);
                                        //sildikten sonra envanter click tetiklensin
                                        $('.nav-pills a[href="#pills-kurEnvanter"]').trigger('click');
                                        
                                    }
                                }
                                 })
                        }
                    },
                    cancel: {
                         text:'İptal Et'
                    },
                }
            });
             })
            //ENV SİL SON
       });
          </script>  
         
            <script type="text/javascript">
             $(document).ready(function(){
            //tabmenü ekle js
             $('.btnmenuekle').click(function(){
                  $.confirm({
                type:'blue',
                title: '<b class="text-info">Menü Ekle</b>',
                content: '<form action="" class="formName">' +
                '<div class="form-group"><label>Menü Adı: </label>' +
                '<input type="text" placeholder="Menü adı girin" class="ekletabad form-control" required />' +
                '</div></form>',
                buttons: {
                    formSubmit: {
                        text: 'Gönder',
                        btnClass: 'btn-blue',
                        action: function () {
                            var kurid=$(".kid").attr("data-id");
                            var ekletad=$('.ekletabad').val().trim();
                             if(!ekletad){
                                var diMenuAdBos=$.dialog('<b class="text-danger">Menü adı boş bırakılamaz! </b>');
                                mesajKapat(diMenuAdBos);
                                return false;
                            }
                           
                           //var dobje={"kid":kurid,"ad":ekletad};
                            //dobje=JSON.stringify(dobje);
                            
                                 $.ajax({
                                method: 'POST',
                                url : "https://www.api.omurserdar.com/api/tabmenu/",
                                data : {kid:kurid,ad:ekletad},
                                //contentType: "application/json",
                                
                                success: function(apitabekle){
                                    if(apitabekle.mesaj=="eklendi"){
                                       var eklenenid=apitabekle.eklenen_tab_id;
                                       /* tetikleyici geldi, önemsiz ekleme animasyonu
                                       var ekletabMenuBos="";
                    ekletabMenuBos+='<div id="cardtab'+eklenenid+'" class="card border-info mb-2 ml-2 col-md-4" style="max-width: 20rem;"><div class="card-body">';
                    ekletabMenuBos+='<h2 class="card-title text-info" id="ma'+eklenenid+'"><b>'+ekletad+'</b></h2>';
                    ekletabMenuBos+='<button type="button" id="'+ekletad+'" data-id="'+eklenenid+'" class="btntabduzenle btn btn-outline-primary pr-2 mr-2"><i class="fas fa-edit"></i> Düzenle</button>';
                    ekletabMenuBos+='<button type="button" id="'+ekletad+'" data-id="'+eklenenid+'" class="btntabsil btn btn-outline-danger btn'+eklenenid+' pr-2 mr-2"><i class="fas fa-trash-alt"></i> Sil</button> </div></div>';
                    $(".sipmenu2Div").append(ekletabMenuBos).fadeIn("normal");
                    */
                            
                                var diMenuEkle=$.dialog({
                                    type:'green',
                                    title: '<b class="text-success">Eklendi</b>',
                                    content: ekletad+' isimli menü eklendi.'
                                });
                                mesajKapat(diMenuEkle);
                                $('.nav-pills a[href="#pills-kurtabMenuler"]').trigger('click');
                                    }
                                    else{ 
                                        var diMenuEkleHata=$.dialog("eklenemedi"); 
                                        mesajKapat(diMenuEkleHata);
                                    }
                                    
                                }
                                 })
                        }
                    },
                    cancel: {
                         text:'Kapat'
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });
             })
            //son tabmenü ekle
            
            //tabmenü sil
                $(document).on('click', '.btntabsil', function(){
                  var tabadi=$(this).attr("id");
                  var tabid=$(this).attr("data-id");
                  $.confirm({
                type:'red',      
                title: '<b class="text-danger">Silmek istediğine emin misin?</b>',
                content: tabadi+' isimli menü silinecek...',
                buttons: {
                    evet: {
                        text: 'Evet, sil',
                        btnClass: 'btn-danger',
                        action: function () {
                                 $.ajax({
                                method: 'DELETE',
                                url : "https://www.api.omurserdar.com/api/tabmenu?id="+tabid,
                                //dataType: 'json',
                               // data : {id:tabid},
                            success: function(apitabsil){
                                
                                if(apitabsil.mesaj=="silindi"){
                                    /* tetikleyici cagrıldıgından silme animasyon önemsiz
                                  $("#cardtab"+tabid).slideUp(1000, function() {
                                     $(this).remove();
                                    });
                                    */
                                    
                                  var diMenuSil=$.dialog({ type:'dark', title: '<b class="text-danger">Silindi</b>', content: tabadi+' isimli menü silindi'});
                                    mesajKapat(diMenuSil);
                                    $('.nav-pills a[href="#pills-kurtabMenuler"]').trigger('click');
                                }
                                }
                                
                                 })
                        }
                    },
                    cancel: {
                         text:'İptal Et'
                    },
                }
            });
             })
            //son tabmenu sil
                                 
            //tabuzenle click
                      $(document).on('click', '.btntabduzenle', function(){
                        var tad=$(this).attr("id");
                        var tid=$(this).attr("data-id") 
                        $.confirm({
                        type:'purple',
                        title: '<b class="text-info">Menü Düzenleme</b>',
                        content: '' +
                        '<form action="" class="formName">' +
                        '<div class="form-group"><label>Menü Adı: </label>' +
                        '<input type="text" value="'+tad+'" class="xtabad form-control" required />' +
                        '</div></form>',
                        buttons: {
                            formSubmit: {
                                text: 'Gönder',
                                btnClass: 'btn-blue',
                                action: function () {
                                    
                                    var xad=$('.xtabad').val().trim();
                                     if(!xad){
                                        var diMenuAdBos=$.dialog('<b class="text-danger">Menü adı boş geçilemez</b>');
                                        mesajKapat(diMenuAdBos);
                                        return false;
                                    }
                                    if(xad==tad){
                                        var diMenuDegYok=$.dialog('<b class="text-info">Herhangi bir değişiklik yapılmadı</b>');
                                        mesajKapat(diMenuDegYok);
                                        return false;
                                    }
                            var dobje={"id":tid,"ad":xad};
                            dobje=JSON.stringify(dobje);
                            
                                 $.ajax({
                                method: 'PUT',
                                url : "https://www.api.omurserdar.com/api/tabmenu/",
                                data : dobje,
                                success: function(apitabduz){
                                    if(apitabduz.mesaj=="güncellendi"){
                                        //$("#ma"+tid).html("<b>"+xad+"</b>");
                                        //$('#'+tad).attr("id",xad);
                                        //$('.btn'+tid).attr("id",xad);
                                var diMenuGuncel=$.dialog({
                                    type:'green',
                                    title: '<b class="text-success">Güncellendi</b>',
                                    content: 'Menü Adı Başarıyla Güncellendi'
                                });
                                mesajKapat(diMenuGuncel);
                                    }
                                    $('.nav-pills a[href="#pills-kurtabMenuler"]').trigger("click");
                                }
                                 })
                                 
                        }
                    },
                    cancel: {
                         text:'Kapat'
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });
                              
                          });  
            //son tabuzenle click
            
             });
            </script>
            
            
            <script type="text/javascript">
           //kurtabmenü click
            
$(document).ready(function(){
  $('.nav-pills a[href="#pills-kurtabMenuler"]').on("click",function(){
    var kurid=$(".kid").data("id");
    spinekle(".ktms");
    $.ajax({
        method: 'GET',
        url : "https://www.api.omurserdar.com/api/kurumsal",
        data : {id:kurid},
        contentType: "application/json",
        success: function(dataTabMenu){
            spinkaldir(".ktms");
            var renkler=["success","primary","info","danger","warning"];
            if(dataTabMenu.hata==false){
                
                if(dataTabMenu.kurumsalTumTab.length>0){
                    $(".menusayiyazi").html("<span class='lead'>Toplam <b class='badge badge-pill badge-primary'>"+dataTabMenu.kurumsalTumTab.length+" </b> kayıt bulundu</span>");
                    $(".menusayiyazi").append('<div class="alert alert-dismissible alert-info"><strong>Envanter içeren menü silinemez</strong></div>');
                }
                else{
                    $(".menusayiyazi").html("<span class='lead'> kayıt yok </span>");
                }
                
                var metin="<div class='row'>";
               
                for (i in dataTabMenu.kurumsalTumTab){
                    var vdizi=dataTabMenu.kurumsalTumTab;
                    if(vdizi[i].sayi>0){
                    metin+='<div class="card border-'+random_item(renkler)+' mb-2 ml-2 col-md-4" style="max-width: 20rem;"><div class="card-body">';
                    metin+='<h2 class="card-title text-'+random_item(renkler)+'" id="ma'+vdizi[i].id+'"><b>'+vdizi[i].ad+'</b></h2>';
                    metin+='<h2> <span class="badge badge-pill badge-'+random_item(renkler)+'">'+vdizi[i].sayi+' adet envanter</span></h2><hr/>';
                        for(j in dataTabMenu.kurumsalEnv){
                               if(vdizi[i].id==dataTabMenu.kurumsalEnv[j].tabid)
                                        metin+='<span title="Envantere ('+dataTabMenu.kurumsalEnv[j].ad+') ait bilgi için tıkla" id="'+dataTabMenu.kurumsalEnv[j].envanterid+'" style="font-size: 14px;cursor:pointer;" class="spenvbilgi mb-1 mr-1 badge badge-pill badge-'+random_item(renkler)+'">'+dataTabMenu.kurumsalEnv[j].ad+'</span>';
                           }//for j
                    metin+='<hr/><button type="button" id="'+vdizi[i].ad+'" data-id="'+vdizi[i].id+'" class="btntabduzenle btn btn-outline-primary pr-2 mr-2 btn-block"><i class="fas fa-edit"></i> Düzenle</button></div></div>';
                    }//for i
                    else{
                    //bos menuler 
                    metin+='<div class="card border-'+random_item(renkler)+' mb-2 ml-2 col-md-4" style="max-width: 20rem;"><div class="card-body">';
                    metin+='<h2 class="card-title text-'+random_item(renkler)+'" id="ma'+vdizi[i].id+'"><b>'+vdizi[i].ad+'</b></h2>';
                    metin+='<h2> <span class="badge badge-pill badge-'+random_item(renkler)+'">envanter bulunmuyor</span></h2><hr/>';
                    metin+='<button type="button" id="'+vdizi[i].ad+'" data-id="'+vdizi[i].id+'" class="btntabduzenle btn btn-outline-primary pr-2 mr-2"><i class="fas fa-edit"></i> Düzenle</button><button type="button" id="'+vdizi[i].ad+'" data-id="'+vdizi[i].id+'" class="btntabsil btn btn-outline-danger btn'+vdizi[i].id+' pr-2 mr-2"><i class="fas fa-trash-alt"></i> Sil</button></div></div>';
                    //bos menuler son
                    }
                }
                   
                    
               $(".sipmenuDiv").html(metin+"</div>");
                            
                            /*
                            //boş menüleri ekle
                        var metin="";
                            for (i in dataTabMenu.kurumsalBosTab){
                    metin+='<div id="cardtab'+dataTabMenu.kurumsalBosTab[i].id+'" class="card border-'+random_item(renkler)+' mb-2 ml-2 col-md-4" style="max-width: 20rem;"><div class="card-body">';
                    metin+='<h2 class="card-title text-'+random_item(renkler)+'" id="ma'+dataTabMenu.kurumsalBosTab[i].id+'"><b>'+dataTabMenu.kurumsalBosTab[i].ad+'</b></h2>';
                    metin+='<button type="button" id="'+dataTabMenu.kurumsalBosTab[i].ad+'" data-id="'+dataTabMenu.kurumsalBosTab[i].id+'" class="btntabduzenle btn btn-outline-primary pr-2 mr-2"><i class="fas fa-edit"></i> Düzenle</button>';
                    metin+='<button type="button" id="'+dataTabMenu.kurumsalBosTab[i].ad+'" data-id="'+dataTabMenu.kurumsalBosTab[i].id+'" class="btntabsil btn btn-outline-danger btn'+dataTabMenu.kurumsalBosTab[i].id+' pr-2 mr-2"><i class="fas fa-trash-alt"></i> Sil</button> </div></div>';
                             }//for
                            $(".sipmenu2Div").html(metin);
                            //boş menü son
                            */
                             } //if false
                             else{
                                 $(".sipmenuDiv").html("hata");
                                 }
                                 
                  //env bilgi               
               $(".spenvbilgi").on("click",function(){
                   var pid=$(this).attr("id");
                  $.ajax({
        method: 'GET',
        url : "https://www.api.omurserdar.com/api/envanter",
        data : {id:pid},
        dataType:'json',
        contentType: "application/json",
        success: function(tabMenuyeaitEnvCevap){
            
            if(tabMenuyeaitEnvCevap.hata==false){
                var veridepo=tabMenuyeaitEnvCevap.envanterBilgi;
                let aYazi=veridepo.alinabilirMi>0 ? "<span class='badge badge-success'><i class='fa fa-unlock'></i> ALINABİLİR</span>":"<span class='badge badge-danger'><i class='fa fa-lock'></i> ALINAMAZ</span>";
                
         var qryazi="https://www.api.omurserdar.com/api/envanter?id="+pid;
         
                var diaBilgi=$.dialog({
                   type:'purple',
                   title:"<b class='lead'>Envanter: <span class='badge badge-info'>"+veridepo.ad+"</span>",
                   content:'<b class="lead">Fiyat: <span class="badge badge-info">'+veridepo.fiyat+' ₺ </span><br> Alınabilir Mi: '+aYazi+'</b><div align="center" class="mt-2" id="qrcode'+pid+'"></div>',
                      onOpenBefore: function () {
                          diaBilgi.showLoading();
                        // before the modal is displayed.
                        $('#qrcode'+pid).qrcode({ 
                                   width: 200,
                                   height: 200,
                                   render : "table", 
                                   text : qryazi
                                });
                    },onContentReady: function () {
                          diaBilgi.hideLoading();
                    }
                });
                
                mesajKapat(diaBilgi,9000);
            }
            else{
                alert("env bilgi hatası"); 
            }
        }
        });
                  
               });
               //env bilgi
                                 
                      },
                      //success
                      error: function(e){
                        console.log(e);
                      },
                      dataType: "json",
                      contentType: "application/json"
                });
 
           });//click pillskurtabmenuler
    });//ready
          
           </script>
           
     
               
           
            <!--SON TABMENU JS -->
               
                <!--KURUMSAL SİPARİSLERİM JS -->
  <script type="text/javascript">
       $(document).ready(function(){
           
           $('.nav-pills a[href="#pills-kursiparislerim"]').click(function(){
           var kurid=$(".kid").data("id");
           spinekle(".ktss");
           
            $.ajax({
     method: 'GET',
     url : "https://www.api.omurserdar.com/api/siparislerim",
     data : {kid:kurid},
     success: function(dataSipKur){
         spinkaldir(".ktss");
         
         if(dataSipKur.sayi==0){
             $(".sipkurDiv").html("<h2 class='text-info'> Sipariş Yok </h2>");
         }
         if(dataSipKur.hata==false && dataSipKur.sayi>0){  
     var veri='<table class="table table-striped"><thead class="thead-dark"><tr><th scope="col"><i class="fas fa-key"></i> Sipariş Kodu</th><th scope="col"><i class="fas fa-user"></i> Sipariş Sahibi</th><th scope="col"><i class="fas fa-dolly"></i> Sipariş Durumu</th><th scope="col"><i class="fas fa-calendar-alt"></i> Sipariş Tarihi </th><th scope="col"> <i class="fas fa-lira-sign"></i> Toplam Tutar</th><th scope="col"><i class="fas fa-cogs"></i> İşlem</th> </tr></thead><tbody><tr>';
      var metin="";
      
    for (i in dataSipKur.siparislerKume) {
          metin+='<td><a style="font-size: 15px;" class="atablosipKod btn btn-outline-primary btn-sm" title="sipariş özeti için tıklayın" data-id="'+dataSipKur.siparislerKume[i].siparisKod+'" href="javascript:void(0);" data-toggle="modal" data-target="#siparisDetayModal">'+dataSipKur.siparislerKume[i].siparisKod+'</a></td>';
          
          metin+='<td>'+dataSipKur.siparislerKume[i].ad+'</td>';
          metin+='<td class="sipDurum'+dataSipKur.siparislerKume[i].siparisKod+'">'+dataSipKur.siparislerKume[i].tanim+durumagoreyaz(dataSipKur.siparislerKume[i].tanim);
          if(dataSipKur.siparislerKume[i].tanim=="Tamamlandı"){
              
             $.ajax({
             method: 'GET',
             async:false,
             url : "https://www.api.omurserdar.com/api/degerlendirme",
             data : {sipariskod:dataSipKur.siparislerKume[i].siparisKod},
             success: function(datasipdeger){
                if(datasipdeger.degsay==1)
                    metin+="<button type='button' id='"+dataSipKur.siparislerKume[i].siparisKod+"' class='btnkurdeggor btn btn-info btn-block btn-sm mt-1 ml-1 text-white'><i class='fas fa-eye'></i> değerlendirmeyi gör</button>";
   
             }
             });
          }
          
          metin+='</td>';
          
          //metin+='<td class="sipTarih'+dataSipKur.siparislerKume[i].siparisKod+'" data-id="'+dataSipKur.siparislerKume[i].sipTarih+'">'+dataSipKur.siparislerKume[i].sipTarih+'</td>'; 
          
          metin+='<td class="sipTarih'+dataSipKur.siparislerKume[i].siparisKod+'" data-id="'+dataSipKur.siparislerKume[i].sipTarih+'">'+tr_tarih(dataSipKur.siparislerKume[i].sipTarih)+'</td>'; 
          
          metin+='<td class="sipTutar'+dataSipKur.siparislerKume[i].siparisKod+'" data-id="'+dataSipKur.siparislerKume[i].toplamTutar+'">'+dataSipKur.siparislerKume[i].toplamTutar+' &#8378; </td>';
          
          var statu="";
          if(dataSipKur.siparislerKume[i].tanim=="Tamamlandı"){
            
            metin+='<td><button disabled type="button" class="btn btn-outline-primary btnksipduzenle btnks'+dataSipKur.siparislerKume[i].siparisKod+'" sipdur="'+dataSipKur.siparislerKume[i].sipdurumid+'" data-id="'+dataSipKur.siparislerKume[i].siparisKod+'"> <i class="fas fa-edit"></i> güncelle </button>';
            
           
            
             metin+="</td></tr>"
            
          }
          else{
              metin+='<td><button type="button" class="btn btn-outline-primary btnksipduzenle btnks'+dataSipKur.siparislerKume[i].siparisKod+'" sipdur="'+dataSipKur.siparislerKume[i].sipdurumid+'" data-id="'+dataSipKur.siparislerKume[i].siparisKod+'"> <i class="fas fa-edit"></i> güncelle </button></td></tr>';
          }
          
           
          
        }
    metin+='</tbody></table>';
    $(".sipkurDiv").html(veri+metin);
    
    
    //btnkurdeggor click
    $('.btnkurdeggor').on('click', function(){
    
    var sipkod=$(this).attr("id");
    
    $.ajax({
        method: 'GET',
        url : "https://www.api.omurserdar.com/api/degerlendirme",
        data : {sipariskod:sipkod},
        success: function(datasipdeger){
  
            var hizyildiz="";
            for(j=0;j<datasipdeger.degerlendirme.hiz;j++)
                    hizyildiz+='<span><i class="fas fa-star" style="color:#FFA500"></i></span>';
			for(k=0; k<(10-datasipdeger.degerlendirme.hiz); k++)
					hizyildiz+='<span><i class="far fa-star"></i></span>';
					
			var lezzetyildiz="";
            for(j=0;j<datasipdeger.degerlendirme.lezzet;j++)
                    lezzetyildiz+='<span><i class="fas fa-star" style="color:#FFA500"></i></span>';
			for(k=0; k<(10-datasipdeger.degerlendirme.lezzet); k++)
					lezzetyildiz+='<span><i class="far fa-star"></i></span>';		
					
  
        var dikurdeggor=$.dialog({
            type:'purple',
            title: '<b class="text-info">Değerlendirme Bilgileri</b>',
            content: 'HIZ: '+hizyildiz+' <br> LEZZET: '+lezzetyildiz+' <br> YORUM: '+datasipdeger.degerlendirme.yorum
         });
         
         mesajKapat(dikurdeggor,"7000");
   
             }
             });
    
         
         
                
                
    });
    //son btnkurdeggor
    
    //btnksipduzenle click
      $('.btnksipduzenle').on('click', function(){
            var eskidurum=$(this).attr("sipdur");
            var sipkod=$(this).attr("data-id");
            var sipDurumlar=[ {id:"1", ad:"Yeni Sipariş"}, {id:"2", ad:"Sipariş Hazırlanıyor"}, {id:"3",ad:"Gönderimde"}, {id:"4",ad:"Tamamlandı"}];
       
            var metOptDurum=""; var etkinolma="";
              
             $.each(sipDurumlar, function (i, item) {
                 if(item.id<eskidurum)
                   etkinolma=" disabled";
                 else
                  etkinolma="enabled";
                 
                        if(eskidurum==item.id)
                            metOptDurum+='<option value="'+item.id+'" selected '+etkinolma+'>'+item.ad+'</option>'; 
                        else
                            metOptDurum+='<option value="'+item.id+'" '+etkinolma+'>'+item.ad+'</option>';
                });//each
                
            //btnksipduzenle düzenleme form dialog
             $.confirm({
                type:'purple',
                title: '<b class="text-info">'+sipkod+' kodlu Sipariş Durum Düzenleme</b>',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Sipariş Durumu Seçimi: </label>' +
                '<select class="form-control" id="duzenledurumselect">'+metOptDurum+'</select>' +
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Gönder',
                        btnClass: 'btn-blue',
                        action: function () { //gönder action
                            
                            var pdid=$("#duzenledurumselect option:selected" ).val();
                            var pdad=$("#duzenledurumselect option:selected" ).text();
                            //$.dialog("seçilen durum id: "+pdid+" // Seçilen durum ad: "+pdad);
                          
                            if(pdid==eskidurum){
                                var diMenuDuzDegYok=$.dialog('<b class="text-info">Herhangi bir değişiklik yapılmadı</b>');
                                mesajKapat(diMenuDuzDegYok);
                                return false;
                            }
                            //durumda değişiklik varsa
                            else{
                              
                            var dobje={"durumid":pdid,"sipkod":sipkod};
                            dobje=JSON.stringify(dobje);
                                 $.ajax({
                                method: 'PUT',
                                url : "https://www.api.omurserdar.com/api/siparislerim/",
                                //dataType: "json",
                                //contentType: "application/json"
                                data : dobje,
                                success: function(apikursipduz){
                                    if(apikursipduz.mesaj=="güncellendi"){
                                       
                                        $(".sipDurum"+sipkod).html(pdad+durumagoreyaz(pdad));
                                        $(".btnks"+sipkod).attr("sipdur",pdid);

                                var diSipDurumGuncellendi=$.dialog({
                                    type:'green',
                                    title: '<b class="text-success"> Güncellendi <b>',
                                    content: 'Sipariş Durum Güncellendi',
                                    onClose: function () {
                                      $('.nav-pills a[href="#pills-kursiparislerim"]').trigger('click');
                                                 }
                                            });
                                    
                                                mesajKapat(diSipDurumGuncellendi);
                          
                                
                                    }
                                }
                                 })
                        
                        }
                        //durumda değişiklik varsa son
                    } //gönder action
                        
                    },
                    cancel: {
                         text:'Kapat'
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });
            //btnksipduzenle düzenleme form dialog
            
      });
      //btnksipduzenle son
    
    //siparis detay modal sipkod click
     $('.atablosipKod').click(function(){
         var kod=$(this).data("id");
         var tarih=$('.sipTarih'+kod).data("id");
         var tutar=$('.sipTutar'+kod).data("id");
         
         $(".siparisDetayModTit").html("<b class='text-danger'>"+kod+"</b> sipariş koduna ait bilgiler");
          $.ajax({
            method: 'GET',
            url : "https://www.api.omurserdar.com/api/siparislerim/siparisEnvanterler.php",
            data : {siparisKod:kod},
            success: function(dataSipEnv){
                //var dataSipEnv = JSON.stringify(dataSipEnv);
                if(dataSipEnv.hata==false){ 
                    var metin="<center>";
                    for (i in dataSipEnv.EnvnaterlerVeSiparisler){
                        metin+='<div class="card border-info mb-3" style="max-width: 20rem;"><div class="card-body">';
                        metin+='<span class="ml-2 badge badge-success">Envanter: '+dataSipEnv.EnvnaterlerVeSiparisler[i].envanter_ad+'</span><hr>';
                        //metin+='<span class="ml-2 badge badge-info">Bireysel:</span> <span class="ml-2 badge badge-primary">'+dataSipEnv.EnvnaterlerVeSiparisler[i].bireysel_ad+'</span>';
                            metin+='<span class="ml-2 badge badge-warning">Adet: '+dataSipEnv.EnvnaterlerVeSiparisler[i].adet+'</span>';
                            metin+='<span class="ml-2 badge badge-danger">Tutar: '+dataSipEnv.EnvnaterlerVeSiparisler[i].tutar+' &#8378 </span></div></div>';
                            }//for
                            
                      $(".siparisDetayModBody").html(metin+"</center>");
                        $(".siparisDetayModFooter").html("Sipariş Sahibi: "+dataSipEnv.durum.bad+" | SİPARİŞ TARİH: "+tr_tarih(tarih)+' | TUTAR: '+tutar+" &#8378");
                     
                      $('#siparisDetayModal').modal("show");
                             } //if false
                             else{
                                 $(".siparisDetayModBody").html("hata");
                                 }
                      }, //success
                      error: function(e){
                        console.log(e);
                      },
                      dataType: "json",
                      contentType: "application/json"
                });
     }); //tablosipkod click event son
     
            }
              else{
                   $(".siparisDetayModTit").html("<b class='text-danger'> HATA = TRUE </b>");
                  }
             },
             error: function(e){
                        //console.log(e);
                      },
                      dataType: "json",
                      contentType: "application/json"
             });
             
             
       });//link kursiparistab click son
             
    })
    
   
       
  </script>
  <!-- SON KURUMSALSİPARİSLERİM JS -->

        <?
      }
     
 } //isset sessi kultip son
 
 //giris YAPMAMIŞ icin event
 else { ?>
 
 <div class="jumbotron">
  <h1 class="display-3">Merhaba, AP!</h1>
  <p class="lead"> Bu projede yiyecek/içecek siparişi verebilmek ya da satışa sunabilmek için <b>API</b> geliştirilmiş ve kullanılmıştır.</p><small>Örnek giriş bilgileri Kayıt&Giriş kısmındaki "Giriş Yap" linkine tıklanınca açılan modal da verilmiştir.</small>

</div>

    <!-- START THE FEATURETTES -->

<div class="container marketing">

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7">
          
          <?php 
           $url = "https://api.omurserdar.com/api/bireysel?id=1";
           $json = file_get_contents($url);
           $jsonverilerim = json_decode($json, true);   
           $bireyselKume=$jsonverilerim["bireyselbilgileri"];
          ?>
          
        <h2 class="featurette-heading text-primary"><b><?=$bireyselKume["ad"]?> <?=$bireyselKume["soyad"]?></b> - <span class="text-muted"><?=$bireyselKume["kullaniciadi"]?></span></h2>
        <p class="lead p-5"><b>Uygulama Geliştirme Arayüzü</b> anlamına gelen <b>API</b>, sahip olduğumuz servis veya verileri dış dünyaya açıp başka uygulamaların-platformların kullanımına sunmak için belli kurallar çerçevesinde tanımlamalar yaptığımız arayüzdür.</p>
      </div>
      <div class="col-md-5">
        <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" src="/assets/images/1.gif"/>
        
       
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7 order-md-2">
          
           <?php 
           $url = "https://api.omurserdar.com/api/kurumsal?id=1";
           $json = file_get_contents($url);
           $jsonverilerim = json_decode($json, true);   
           $kurumsalKume=$jsonverilerim["kurumsalbilgileri"];
           $kurumsalTabKume=$jsonverilerim["kurumsalTab"];
           $kurumsalEnvKume=$jsonverilerim["kurumsalEnv"];
          ?>
          
        <h2 class="featurette-heading text-primary"><b><?=$kurumsalKume["ad"]?> - <?=$kurumsalKume["kullaniciadi"]?></b> 
        
        <?if($kurumsalKume["acikMi"]==0){
            echo '<span class="badge badge-danger"><i class="fas fa-lock"></i> KAPALI - Sipariş Verilemez</span>';
        }
        else{
            echo '<span class="badge badge-success"><i class="fas fa-lock-open"></i> AÇIK - Sipariş Verilebilir</span>';
        }
        ?>
     </h2>
        <p class="lead p-5">İstek sonucunda JSON veri döndürülür, dönen cevap işlenir. Örnek olarak <b><a href="https://api.omurserdar.com/api/kurumsal?id=1" target="_blank">https://api.omurserdar.com/api/kurumsal?id=1</a></b> adresindeki "1" id değerine sahip satır bilgileri JSON olarak döndürülüp işlendikten sonra bilgi gösterimi yapılmıştır. </p>
      </div>
      <div class="col-md-5 order-md-1">
        <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" src="/assets/images/2.gif" />
      </div>
    </div>
    
    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7">
          
           <?php 
           $url = "https://api.omurserdar.com/api/envanter?id=1554";
           $json = file_get_contents($url);
           $jsonverilerim = json_decode($json, true);   
           $envKume=$jsonverilerim["envanterBilgi"];
          ?>
          
        <h2 class="featurette-heading text-primary"><b><?=$envKume["ad"]?></b>
        <?if($envKume["alinabilirMi"]==0){
            echo '<span class="badge badge-danger"><i class="fas fa-lock"></i> KAPALI - Sipariş Verilemez</span>';
        }
        else{
            echo '<span class="badge badge-success"><i class="fas fa-lock-open"></i> AÇIK - Sipariş Verilebilir</span>';
        }
        ?>
        </h2>
        <p class="lead p-5"><?=$envKume["tanim"]?> ( <b class="text-info"><?=$envKume["fiyat"]?> &#8378;</b> )</p>
      </div>
      <div class="col-md-5 order-md-1">
        <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" 
        src="/assets/images/3.webp" />
      </div>
    </div>
    


</div>
     
<? } ?>

    <?include "footer.php";?>

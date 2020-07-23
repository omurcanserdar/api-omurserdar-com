<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?=$desc?>">
    <meta name="author" content="ömürcan">
    <!--<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">-->
    <title><?=$title?></title>


<!-- Custom styles for this template -->

    <!-- CSS -->

    <link rel="stylesheet" href="/assets/css/bootstrap.css"> 
    <link rel="stylesheet" href="/assets/css/jqueryconfirm.min.css">
    <link rel="stylesheet" href="/assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="/assets/css/stilim.css">
    
    
    <!-- Favicons -->
     <link rel="icon" href="/assets/images/favicon.ico">
<!--     
<link rel="apple-touch-icon" href="https://getbootstrap.com//docs/4.4/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="https://getbootstrap.com/docs/4.4/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="https://getbootstrap.com/docs/4.4/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
<link rel="manifest" href="https://getbootstrap.com//docs/4.4/assets/img/favicons/manifest.json">
<link rel="mask-icon" href="https://getbootstrap.com/docs/4.4/assets/img/favicons/safari-pinned-tab.svg" color="#563d7c">
<link rel="icon" href="https://getbootstrap.com/docs/4.4/assets/img/favicons/favicon.ico">
<meta name="msapplication-config" content="https://getbootstrap.com/docs/4.4/assets/img/favicons/browserconfig.xml">
<meta name="theme-color" content="#563d7c">
-->
    
    <!-- JS -->
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/bootstrap.bundle.min.js"></script>

<script src="/assets/js/fontawesome.all.min.js" type="text/javascript"></script>
<script src="/assets/js/jqueryconfirm.min.js"></script> <!-- confirm -->
<script src="/assets/js/jsfonksiyonlar.js" type="text/javascript"></script> 
<script src="/assets/js/qr1.js" type="text/javascript"></script>
<script src="/assets/js/qr2.js" type="text/javascript"></script>
   
    
  </head>
  <body>
      
    <header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="/">AP!</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="true" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-collapse collapse show" id="navbarColor01" style="">
    <ul class="navbar-nav mr-auto">
      
      <!-- 
      <li class="nav-item">
       <!--  <a class="nav-link" href="#">About</a> 
         <a class="nav-link" style="color:white" href="/raporlar"><i class="fas fa-file"></i> Raporlar</a> 
      </li>
      <li class="nav-item">
          <a class="nav-link" style="color:white" target="_blank" href="https://github.com/omurserdarr"><i class="fab fa-github"></i> omurserdarr</a>
      </li>  
      -->
      
      <?php
      require_once "fonksiyonlar.php";
      session_start();
      if(isset($_SESSION["kullanici_mail"])==false){
?>
<div class="dropdown2">
  <li class="nav-item">
     <a class="nav-link" style="color:white"><i class="fas fa-user-circle"></i> Kayıt&Giriş</a>
     
  </li>
  <div class="dropdown-content">
    <a href="javascript:void(0);" data-toggle="modal" data-target="#loginModal2"><i class="fas fa-user-plus"></i> Kayıt Ol</a>
    <a href="javascript:void(0);" data-toggle="modal" data-target="#loginModal"><i class="fas fa-sign-in-alt"></i> Giriş Yap</a>  
  </div>
</div>
<?php } ?>


    </ul>
    
      <!-- giriş yapan kullanıcı dropdown -->
            <?php if(isset($_SESSION["kullanici_tip"])){
            ?>
            
            <?php if($_SESSION["kullanici_tip"]=="bireysel"){?>
            <div class="bid" data-id="<?=$_SESSION["kullanici_id"]?>"></div>
        <ul class="navbar-nav mx-auto">
        <li class="nav-item">
            <a class="btnSepetim text-white btn nav-link" style="font-size:20px"><i class="fas fa-shopping-cart"></i> SEPETİM 
                <span class="badge badge-danger sepetEnvSayi"></span>
            </a>
        </li>
        </ul>
        <script>
        
        //1,5 saniyede bir sepet sayiya eris ve güncelle
        var pbid=$(".bid").attr("data-id");
            function sepetSayiEris(){
            $.ajax({
            method: 'GET',
            url : "https://www.api.omurserdar.com/api/sepet",
            data : {bid:pbid}, 
            dataType:'json',
            contentType: "application/json",
            success: function(sepetsayicek){
                $(".sepetEnvSayi").html(sepetsayicek.sayi);        
                }
            });
            }
            setInterval(sepetSayiEris, 1500);
        //son 1,5 saniyede bir sepet sayiya eris ve güncelle
        </script>
    <? } ?>
    
    <ul class="navbar-nav mx-auto">
<div class="dropdown2">
     <li class="nav-item">
  <a class="nav-link text-white"><small><?=$_SESSION["kullanici_tip"]?></small> | <?=$_SESSION["kullanici_mail"]?></a>
</li>
  <div class="dropdown-content text-center">
       <!--<a class="dropdown-item" style="color:black" href="/profilim"><i class="fas fa-user-circle"></i> Profilim</a>
        <div class="dropdown-divider"></div>-->
    <a href="/cikis"><i class="fas fa-power-off"></i> Çıkış Yap</a>
  </div>
</div></ul>
            <?php }  ?>
            <!-- giriş yapan kullanıcı dropdown bitiş -->
    
    
    <!-- 
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text" placeholder="Search">
      <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
    </form>
    -->
  </div>
</nav>
</header>




	
<!-- KAYIT MODAL -->

<div class="modal fade" id="loginModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-title text-center">
          <h4> KAYIT OL </h4>
        </div>
 
<form id="form1" method="post">
                
                <h3> Hesap Bilgileri </h3>
                <hr />
                <section>
                    
           <div class="row">
               
              <div class="col-md-4 mb-2 custom-control custom-radio">
                Bireysel <input type="radio" name="radiokayitTip" id="radioKTipBir" value="bireysel" checked />
              </div>
             <div class="col-md-4 mb-2 custom-control custom-radio">
            Kurumsal <input type="radio" name="radiokayitTip" id="radioKTipKur" value="kurumsal"  /> 
              </div>
      
                <div class="col-md-6 mb-3">
                <input placeholder="ad girin" id="ad" name="ad" type="text" class="required form-control">
                  </div>
                <div class="col-md-6 mb-3">
             <input placeholder="soyad girin" id="soyad" name="soyad" type="text" class="required form-control">
            </div>
            
                <div class="row">
               <div class="col-md-6 mb-3 ml-2">
              <input placeholder="Email adresi girin" id="mail" name="mail" type="text" class="required form-control">
               </div>
               
                <div class="col-md-4"> <input placeholder="Şifre girin" id="password" name="password" type="password" class="required form-control">
                </div>
                <div class="col-md-1 btnsifregor"><i class="fas fa-eye-slash"></i></div>
               
               </div>
               
              
              <div class="col-md-12 mb-3">
               <input placeholder="Kullanıcı adı girin" id="userName" name="userName" type="text" class="form-control">
              </div>
              
              
               <div class="col-md-12 mb-3">
               <input placeholder="Kurum adını girin" id="kurumad" name="kurumad" type="text" class="required form-control">
               </div>
               
                
          

              </section>

             <h3> Erişim Bilgileri </h3>
             <hr />
              <section>
                       <!-- İl ilçe HTML-->
<div class="row">
    
    <div class="form-group col-md-6" id="iller">
    <label for="select" class="control-label">İl</label>
<select class="form-control" id="il" name="il">
<option value="0">İl Seçiniz</option>
<?php
include "db.php";
$query=$db->query("SELECT id,il_adi FROM il ORDER BY id ASC");
while($row=$query->fetch()){
 echo '<option value="'.$row['id'].'">'.$row['il_adi'].'</option>';
}
?>
</select>
</div>
    
<div class="form-group col-md-6" id="ilceler">
<label for="select" class="control-label">İlçe</label>
<select class="form-control"  name="ilce" id="ilce">
<option value="0">İlçe Seçiniz</option>
</select>
</div>

</div><!-- row !-->
<!-- İl ilçe HTML SON-->

  <input placeholder="Adres girin" id="adres" name="adres" type="text" class="required form-control">
  <input placeholder="Telefon numarası girin" id="icep" name="icep" type="text" class="mt-1 required form-control">
  
  <input id="kosul" name="kosul" type="checkbox" checked required><label for="kosul"><a id="btnsart" href="javascript:void(0);">Kullanım koşullarını okudum ve kabul ediyorum</a></label>
  
  <!-- tel js-->
  <script>
      const isNumericInput = (event) => {
	const key = event.keyCode;
	return ((key >= 48 && key <= 57) || // Allow number line
		(key >= 96 && key <= 105) // Allow number pad
	);
};

const isModifierKey = (event) => {
	const key = event.keyCode;
	return (event.shiftKey === true || key === 35 || key === 36) || // Allow Shift, Home, End
		(key === 8 || key === 9 || key === 13 || key === 46) || // Allow Backspace, Tab, Enter, Delete
		(key > 36 && key < 41) || // Allow left, up, right, down
		(
			// Allow Ctrl/Command + A,C,V,X,Z
			(event.ctrlKey === true || event.metaKey === true) &&
			(key === 65 || key === 67 || key === 86 || key === 88 || key === 90)
		)
};

const enforceFormat = (event) => {
	// Input must be of a valid number format or a modifier key, and not longer than ten digits
	if(!isNumericInput(event) && !isModifierKey(event)){
		event.preventDefault();
	}
};

const formatToPhone = (event) => {
	if(isModifierKey(event)) {return;}

	// I am lazy and don't like to type things more than once
	const target = event.target;
	const input = event.target.value.replace(/\D/g,'').substring(0,10); // First ten digits of input only
	const zip = input.substring(0,3);
	const middle = input.substring(3,6);
	const last = input.substring(6,10);

	if(input.length > 6){target.value = `(${zip}) ${middle} - ${last}`;}
	else if(input.length > 3){target.value = `(${zip}) ${middle}`;}
	else if(input.length > 0){target.value = `(${zip}`;}
};

const inputElement = document.getElementById('icep');
inputElement.addEventListener('keydown',enforceFormat);
inputElement.addEventListener('keyup',formatToPhone);
  </script>
  <!-- SON TEL JS -->


   <!-- İl ilçe AJAX-->
 <script type="text/javascript">
 $(function(){
 $("#iller select").change(function(){
 var deger = $(this).val();
 var degerler = $("#form1").serialize();
  //alert(degerler);
 $.ajax({
 type:"POST",
 url:"ilce_kontrol.php",
 data:degerler,
 success:function(x){
$('#ilceler').find('option').remove();
 $("#ilceler select").prepend(x);
 }
 });
 });
});
 </script>
  <!-- İl ilçe AJAX SON-->
 </section>
        
      
         
 <p class="pdurum"></p>
<button type="button" id="kb" class="mt-1 btn btn-info btn-block" > Kaydımı Tamamla</button> 
             <center><div class="loader"></div></center>
        
      </form> <!-- form1 -->
           
                    <script type="text/javascript">
                    $(function() {
                        //var durummetin="";
                        // şifre göster gizle js 
            $('.btnsifregor').on("mouseenter", function(){
                     $('#password').attr('type', 'text');
                     $('.btnsifregor').html('<i class="fas fa-eye"></i>');
            }).on("mouseleave",function(){
                     $('#password').attr('type', 'password');
                     $('.btnsifregor').html('<i class="fas fa-eye-slash"></i>');
            });    
                    //son şifre göster gizle js
    
                            $('#icep').prop('hidden', true); //ceptel görünebilir
                            $('#kurumad').prop('hidden', true); //kurumad görünebilir


        $("#ilceler select").prop("disabled","true"); //ilçe disabled olsun sayfa yüklendiğinde
        $("#iller select").change(function(){ //il secimi yapıldıgında 
           // console.log($("#iller select").val()); secili il value
               if($("#iller select").val()==0) //eğer secili il value 0("il seçiniz yazısı") na eşitse
            $("#ilceler select").prop("disabled","true"); //ilceler select disabled
        else//value 0 dan farklı ise
            $("#ilceler select").removeAttr('disabled'); //ilceler select yapılabilir. disabled kaldır
        });
       
        
    $('input:radio[name="radiokayitTip"]').change(function() { //bireysel-kurumsal radio değişimi olduğunda
        $(".pdurum").html("");
    
        if ($(this).val() == 'bireysel') { //bireysel secili ise
            $('#kurumad').prop('hidden', true); //kurumsal adı inputu gizle
            $('#icep').prop('hidden', true); //kurumsal ceptel gizle
            $('#ad').prop('hidden', false); //ad etkin
            $('#soyad').prop('hidden', false);//soyad etkin
        }
        else{ //kurumsal secili ise
            $('#kurumad').prop('hidden', false); //kurumsalad etkin
            $('#icep').prop('hidden', false); //ceptel etkin
            $('#ad').prop('hidden', true); //bir ad gizle
            $('#soyad').prop('hidden', true); //bir soyad gizle
        }
    });
});
        </script>
                
                <!-- KAYIT INPUT JS SON-->
                
                  <!-- KAYIT API JS -->
         <script type="text/javascript">
   $(document).ready(function(){
        $(".loader").hide();
        
        //kullanımsartları click
        $("#btnsart").click(function(){
        $.alert({
        type:'blue',
        columnClass: 'col-md-6 col-md-offset-3',
        title: 'Kullanım Şartları ',
        content: '<div>SİTE KULLANIM ŞARTLARI</div>'+
	'<div>Lütfen sitemizi kullanmadan evvel bu ‘site kullanım şartları’nı'+ 'dikkatlice okuyunuz.&nbsp;</div>'+
	'<div>Bu alışveriş sitesini kullanan ve alışveriş yapan müşterilerimiz'+ 'aşağıdaki şartları kabul etmiş varsayılmaktadır:</div>'+
	'<div>Sitemizdeki web sayfaları ve ona bağlı tüm sayfalar (‘site’) ………'+ 'adresindeki ……………………………….firmasının (Firma) malıdır ve onun tarafından '+ 'işletilir. Sizler (‘Kullanıcı’) sitede sunulan tüm hizmetleri'+ 'kullanırken aşağıdaki şartlara tabi olduğunuzu, sitedeki hizmetten'+ 'yararlanmakla ve kullanmaya devam etmekle; Bağlı olduğunuz yasalara'+ 'göre sözleşme imzalama hakkına, yetkisine ve hukuki ehliyetine sahip ve'+ '18 yaşın üzerinde olduğunuzu, bu sözleşmeyi okuduğunuzu, anladığınızı ve sözleşmede yazan şartlarla bağlı olduğunuzu kabul etmiş sayılırsınız .&nbsp;</div><br>'
    });
        });
        //SON kullanımsart click
        
    $('#kb').click(function(){
    //var dkilan_id = $(this).attr('id');
     //var dsecim='btngirisyap';
     //$("#form1").serialize();
     var iMail=$('#mail').val().trim();
     var iSifre=$('#password').val().trim();
     var iKullaniciAdi=$('#userName').val().trim();
     var iAd=$('#ad').val().trim();
     var iSoyad=$('#soyad').val().trim();
     var ikurumAd=$('#kurumad').val().trim();
     var iRadio=$("input[name='radiokayitTip']:checked").val();
     var cmbil=$("#iller select").val();
     var cmbilce=$("#ilceler select").val();
     var iAdres=$('#adres').val().trim();
     var icepTel=$('#icep').val().trim();
     
     
     
  //var degerler=[iRadio,cmbil,cmbilce,iMail,iSifre,iKullaniciAdi,ikurumAd,iAdres,icepTel];
 // alert(degerler);
 
 function validateEmail(pmail) 
{
 var re = /\S+@\S+\.\S+/;
 return re.test(pmail);
}

//sözleşme kabul edilmemişse 
if($('#kosul').is(':checked')==false){
         $.alert({
         type:'red',                            
         title: 'UYARI',
         content: 'Kullanıcı sözleşmesi kabul edilmeli !',
        });
return false;
}
// SON sözleşme kabul edilmemişse 
      
  if(iRadio=="bireysel"){
        $.ajax({
     method: 'POST',
     url : "https://www.api.omurserdar.com/api/bireysel/",
     data : {il:cmbil,ilce:cmbilce,ad:iAd,soyad:iSoyad,kullaniciadi:iKullaniciAdi,email:iMail,sifre:iSifre,adres:iAdres},
    beforeSend:function(){
       // $(".loader").show();
        //bireysel secili - beforeSEND
        var deger=validateEmail(iMail);
        
       
        
if(iAd=="" || iSoyad=="" || iMail=="" || iSifre=="" || iAdres=="" || cmbilce==0){
    $(".pdurum").html('<div class="alert alert-dismissible alert-danger"> Eksik ya da hatalı bilgi girdiniz, lütfen bilgileri kontrol edin ...</div>');
            return false;
        }
        else{
            
    if(deger==false){
            $(".pdurum").html('<div class="alert alert-dismissible alert-danger"> Email bilgisini kontrol edin...</div>');
        return false;
     }
     resetForm($('#form1')); //formu temizle
     $(".pdurum").html(""); //durum temizle
     $('input[name="radiokayitTip"][value="bireysel"]').prop('checked',true); //bireysel sec
    $("#iller select").val('0').prop('selected', true);//il sec
    $("#ilceler select").val('0').prop('selected', true);//ilce sec
    $("#kosul").prop('checked',true);//sart sec
         
        }
        
    },
     success: function(eklendiMi){
        // alert(eklendiMi.mesaj); json olarak dönen verilerden mesaj verisine ulasıyorum
         if(eklendiMi.mesaj=="eklendi"){  
        
    var diaKayitOK=$.dialog({
    type:'green',                            
    title: '<b class="text-success">Harika !</b>',
    content: 'Kayıt oluşturuldu'
});
mesajKapat(diaKayitOK,2500);
 
 $('#loginModal2').modal('hide');
  $("#loginModal").modal("show");
        //window.location.replace("http://api.omurserdar.com");
                        }
                        else if(eklendiMi.mesaj=="kullanicizatenmevcut"){
    var diaKullaniciVar=$.dialog({
    type:'orange',                            
    title: 'Bilgi !',
    content: 'Böyle bir kullanıcı zaten sistemde var!'
});
mesajKapat(diaKullaniciVar,2500);
                        }
                        else{
var diaKayitNo=$.dialog({
    type:'red',                            
    title: 'Kayıt Yapılamadı :( ',
    content: 'Bilgileri Kontrol Et !'
});
    mesajKapat(diaKayitNo,2500);
                        }
             },//success
               complete:function(data){
   $(".loader").hide();
   }
             });
  }
  else{
     $.ajax({
     method: 'POST',
     url : "https://www.api.omurserdar.com/api/kurumsal/",
     data : {il:cmbil,ilce:cmbilce,ad:ikurumAd,kullaniciadi:iKullaniciAdi,email:iMail,sifre:iSifre,adres:iAdres,ceptel:icepTel},
      beforeSend: function() {
       // $(".loader").show();
        
         var deger=validateEmail(iMail); //email doğru mu sorgula

if(iMail=="" || iSifre=="" || ikurumAd==""|| iKullaniciAdi=="" || iAdres=="" || icepTel==""  || cmbil==0 || cmbilce==0){
    $(".pdurum").html('<div class="alert alert-dismissible alert-danger"> Eksik ya da hatalı bilgi girdiniz, lütfen bilgileri kontrol edin ...</div>');
            return false;
        }
        else{
            
    if(deger==false){
            $(".pdurum").html('<div class="alert alert-dismissible alert-danger"> Email bilgisini kontrol edin...</div>');
        return false;
     }
     resetForm($('#form1')); //formu temizle
     $(".pdurum").html(""); //durum temizle
     $('input[name="radiokayitTip"][value="bireysel"]').prop('checked',true); //bireysel sec
    $("#iller select").val('0').prop('selected', true);//il sec
    $("#ilceler select").val('0').prop('selected', true);//ilce sec
    $("#kosul").prop('checked',true);//sart sec
        
    }
},
     success: function(eklendiMi){
        // alert(eklendiMi.mesaj); json olarak dönen verilerden mesaj verisine ulasıyorum
         if(eklendiMi.mesaj=="eklendi"){  
        
            var diaKurKayitOK=$.dialog({
    type:'green',
    title: '<b class="text-success">Harika !</b>',
    content: 'Kayıt oluşturuldu',
});
mesajKapat(diaKurKayitOK,2500);
 
 $('#loginModal2').modal('hide');
 $("#loginModal").modal("show");
        //window.location.replace("http://api.omurserdar.com");
                        }
                        else if(eklendiMi.mesaj=="kullanicizatenmevcut"){
    var diKurKulVar=$.dialog({
    type:'orange',                            
    title: '<b class="text-warning">Bilgi ! </b>',
    content: 'Böyle bir kullanıcı zaten sistemde var!'
});
mesajKapat(diKurKulVar,2500);
                        }
                        else{
var diaKurKayitNo=$.dialog({
    type:'red',
    title: '<b class="text-danger">Kayıt YAPILAMADI </b>  ',
    content: 'Bilgileri Kontrol Et !'
});
mesajKapat(diaKurKayitNo,2500);
                        }
             },//success
               complete:function(data){
   $(".loader").hide();
   }
             });
  }

  

           });
        });
              </script>
 <!-- KAYIT API JS SON -->
 
      </div>
    </div>
  </div>
</div>


<!-- KAYIT MODAL -->

<!-- LOGIN MODAL -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-title text-center">
          <h4>Giriş Yap </h4>
        </div>
        <div class="d-flex flex-column text-center">
          <form id="loginform" method="post">
                		<input type="hidden" name="enckey" id="enckey" value="<?=generateRandomKey()?>">

               <input type="radio" name="radiogiris" id="idRadBir" value="bireysel" checked/>Bireysel
               <input type="radio" name="radiogiris" id="idRadKur" value="kurumsal" /> Kurumsal
            <div class="form-group">
              <input type="email" class="form-control"  id="email1" name="email1" placeholder="Email adresi girin">
            </div>
            <div class="btnsifregorgiris"><i class="fas fa-eye-slash"></i></div>
            <div class="form-group">
              <input type="password" class="form-control" id="password1" name="sifre1" placeholder="Şifre Girin">
            </div>
            <button type="button" id="btngirisyap" class="float-left btn btn-info col-md-8">Giriş Yap</button>
            <a href="/sifremiunuttum" class="ml-1 btn btn-outline-danger">Şifremi Unuttum</a>
            <center><div class="loader"></div></center>
            
            <p>bireysel-> email:hesap@bireysel.com ; şifre:api</p>
            <p>kurumsal-> email:hesap@kurumsal.com ; şifre:api</p>
          </form>
          
          
          
          <!-- ŞİFRE İŞLEM BUTON TIK-->
          
          <script type="text/javascript">
          
          $(document).ready(function() {
          
          $("#btnsifremiunuttum").on("click",function(){
               $('#loginModal').modal('hide');
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
              
          });
          });
          </script>
          <!--SON ŞİFRE İŞLEM BUTON TIK -->
          
          
          
          
          
          
          <!-- LOGIN JS -->
         <script type="text/javascript">
    $(function (){
        $(".loader").hide();
        
        
       
          /*
           $('input:radio[name="radiogiris"]').change(function() {
               dra=$(this).attr('value');
           });
          */
          
            $('.btnsifregorgiris').on("mouseenter", function(){
                     $('#password1').attr('type', 'text');
                     $('.btnsifregorgiris').html('<i class="fas fa-eye"></i>');
            }).on("mouseleave",function(){
                     $('#password1').attr('type', 'password');
                     $('.btnsifregorgiris').html('<i class="fas fa-eye-slash"></i>');
            }); 
            
       $("#password1").keypress(function(event) { 
            if (event.keyCode === 13) { 
                //$("#GFG_Button").click(); 
                $("#btngirisyap").trigger('click');
            } 
        }); 
            
          
    $('#btngirisyap').click(function(){
    //var dkilan_id = $(this).attr('id');
    
    /*var veri=$("#loginform").serialize();
     $.alert({
        columnClass: 'col-md-6 col-md-offset-3',
        type: 'blue',
        title: 'Giriş İşlemi',
        content: 'Gönderilen name alanları ve değerleri: '+veri,
        });
    return false;
    */
     var dsecim='btngirisyap';
     var dad=$('#email1').val();
     var dsif=$('#password1').val();
     //var dra=document.querySelector('input[name="radiogiris"]:checked').value;
     var dra=$("input[name='radiogiris']:checked").val();
      $.ajax({
     method: 'POST',
     url : "https://www.api.omurserdar.com/ajaxLogin.php",
     data : {secim:dsecim,tip:dra,ad:dad,sifre:dsif},
      beforeSend: function() {
        $(".loader").show();
    },
     success:function(bulunduMu){
         if(bulunduMu=="bulundu"){  
        window.location.replace("https://api.omurserdar.com");
                        }
          else{
        $.dialog({
        type: 'red',
        title: 'Giriş Yapılamadı!',
        content: 'Bilgileri Kontrol Et !',
                });
               }
             },//success
       complete:function(data){
           $(".loader").hide();
                    }
             });
           });
        });
              </script>
    <!-- LOGIN JS SON -->
          
        </div>
      </div>
    </div>
  </div>
</div>
<!-- LOGIN MODAL SON -->


<main role="main">

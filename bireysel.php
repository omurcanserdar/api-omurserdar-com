 <?php
 $title="rastgele bireysel bilgi";
 $desc="rastgele bireysel bilgi";
 include "header.php";?>
 <head>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.all.js"> </script>
     
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
     <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.css">
 </head>
<!-- kullandığım ilk api verileri -örnek olması için silmedim- -->

 <section class="jumbotron text-center">
    <div class="container">
      <h1>Bireysel <small>kullanıcı</small> </h1>
      <p class="lead text-muted">Bu sayfada <b>"http://www.api.omurserdar.com/api/bireysel"</b> sayfasından dönen veriler kullanılarak id değeri 1 ile 10 arasında olan üyenin bilgileri tekli bireysel için; <br>id değeri 1 ile 10 arasında olan rastgele üyelerin bilgileri çoklu bireysel için ekrana yansıtılmıştır </p>
      <p><!-- <a href="#" class="btn btn-primary my-2">Main call to action</a> --></p>
    </div>
  </section>
  <hr/> <h1> RASTGELE TEKLİ BİREYSEL ÜYE GETİR </h1>
<?php
if(!isset($_GET["id"]))
        $id=rand(1,10);
    else{ $id=$_GET["id"]; }
  $url = "http://api.omurserdar.com/api/bireysel?id=$id";
           $json = file_get_contents($url);
           $jsonverilerim = json_decode($json, true);   
            $kume=$jsonverilerim["bireyselbilgileri"];
                if(!empty($kume)){
?>
  <div class="album py-5 bg-light">
    <div class="container">
      <div class="row text-center">
        <div class="col-md-6">
          <div class="card mb-6 shadow-sm">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em"><?=$kume["kullaniciadi"]?></text></svg>
            <div class="card-body">
              <p class="card-text"><?=$kume["ad"].' '.$kume["soyad"]?></p>
              <p class="card-text"><?=$kume["email"]?></p>
              <div class="d-flex justify-content-between align-items-center">
                <!-- <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                  -->
                </div>
                <small class="text-muted"><?=$kume["kayit_tarihi"]?></small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
 <?php }
 else{
    echo '<div class="alert alert-dismissible alert-danger">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>Oops.. </strong> Bireysel Üye Bulunamadı <small>5 saniye sonra sayfa yenilenecek...</small>
</div>' ;
 ?>
 <script>
Swal.fire({
  icon: 'error',
  title: 'Oops...',
  text: 'Bireysel Üye Bulunamadı !',
  footer: '5 saniye sonra sayfa yenilenecek...'
})
setTimeout(function(){ window.location.reload(1); }, 5000);
</script>
 <? } ?>
 
 <hr/> <h1> RASTGELE ÇOKLU BİREYSEL ÜYELER GETİR </h1>
 
 

 
<?php
$sayilar=array();
$i=0;
while($i<5)
{
$rakam=rand(1,10);
	if (in_array($rakam,$sayilar)){
	    continue;
	}
	else{
    array_push($sayilar,$rakam);
	$i++;
	}
}
//print_r($sayilar);
?>
 
   <div class="album py-5 bg-light">
    <div class="container">
      <div class="row text-center">
 <?php
 foreach($sayilar as $bid){
 $url = "http://api.omurserdar.com/api/bireysel?id=$bid";
           $json = file_get_contents($url);
           $jsonverilerim = json_decode($json, true);   
            $kume=$jsonverilerim["bireyselbilgileri"];
            if(!empty($kume)){
 ?>
        <div class="col-md-6">
          <div class="card mb-6 shadow-sm">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em"><?=$kume["kullaniciadi"]?></text></svg>
            <div class="card-body">
              <p class="card-text"><?=$kume["ad"].' '.$kume["soyad"]?></p>
              <p class="card-text"><?=$kume["email"]?></p>
              <div class="d-flex justify-content-between align-items-center">
                <!-- <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                  -->
                </div>
                <small class="text-muted"><?=$kume["kayit_tarihi"]?></small>
              </div>
            </div>
          </div>
          
      
    
 <?}}?>
 </div>
</div>
</div>
    <?include "footer.php";?>

 <?php
 $title="rastgele kurumsal sayfası";
 $desc="rastgele kurumsal";
 include "header.php";?>
 <head>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.all.js"> </script>
     
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
     <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.css">
 </head>

<!-- kullandığım ilk api verileri -örnek olması için silmedim- -->

  <hr/> <h1> TEKLİ KURUMSAL ÜYE GETİR <small> id verilmemişse rastgele id üretir ve id ye ait üye varsa bilgileri görüntülenir </small> </h1>
<?php
    if(!isset($_GET["id"]))
        $id=rand(1,10);
    else{ $id=$_GET["id"]; }
  
  $url = "http://api.omurserdar.com/api/kurumsal?id=$id";
           $json = file_get_contents($url);
           $jsonverilerim = json_decode($json, true);   
            $kume=$jsonverilerim["kurumsalbilgileri"];
            $kume2=$jsonverilerim["kurumsalTab"];
             $kume3=$jsonverilerim["kurumsalEnv"];
                if(!empty($kume)){
                    //foreach($kume as $veri)
                        //echo $veri."<br/>";
                       //echo $kume2
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
    
    
    <div class="row">
     <?php $sayac=1; 
     foreach($kume2 as $k){ ?>
    <div class="card border-light col-md-4" style="max-width: 20rem;" id="accordion">
  <div class="card-header"> <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$sayac?>">
        <?=$k["ad"]?>     <span class="badge badge-primary badge-pill"><?=$k["sayi"]?></span></a>
    </div>
  <div class="card-body">
    <div id="collapse<?=$sayac?>" class="panel-collapse collapse in">
      <div class="panel-body">
          
          <?php foreach($kume3 as $k3){
            if($k["id"]==$k3["tabid"]){
                ?>
                <div class="card">
  <div class="card-body">
    <h5 class="card-title"><?=$k3["ad"]?><span class='badge badge-pill badge-info'><?=$k3['fiyat']?> &#8378;</span></h5>
    <? if($k3["tanim"]!=" "){ 
        echo '<h6 class="card-subtitle mb-2 text-muted">'.$k3["tanim"].'</h6>';
    } ?>

    <!-- <a href="#" class="card-link">Card link</a> -->
        
        <? if($k3["alim"]==1){
            echo "<span class='badge badge-pill badge-success'> Sipariş Verilebilir </span>";
            echo '<button type="button" class="btn btn-primary btn-outline btn-sm"> <i class="fas fa-cart-plus"></i> </button>';
            
        }
        else{
            echo "<span class='badge badge-pill badge-danger'> Sipariş Verilemez  </span>";
           
        }
        ?>
        
          
  </div>
</div>
<?  }
          }?>
      </div>
    </div>
  </div>
</div>
<?php $sayac++;} ?>
</div>

 <?php }
 else{
    echo '<div class="alert alert-dismissible alert-danger">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>Oops.. </strong> Kurumsal Üye Bulunamadı <small>5 saniye sonra sayfa yenilenecek...</small>
</div>' ;
 ?>


 <script>
Swal.fire({
  icon: 'error',
  title: 'Oops...',
  text: 'Kurumsal Üye Bulunamadı !',
  footer: '5 saniye sonra sayfa yenilenecek...'
})
setTimeout(function(){ window.location.reload(1); }, 5000);
</script>

<?
}
include "footer.php";
?>

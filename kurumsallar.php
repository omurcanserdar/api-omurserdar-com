<?php
ob_start();

 $title="Kurumsal Üyeler | API";
 $desc="kurumsal üyeler";
 include "header.php";
 
 /*
 function bsRenkYaziUret(){
     $renkDizi=array("primary","success","secondary","warning","info");
     return $renkDizi[array_rand($renkDizi,1)];
 }
 */
?>
<div class="container-fluid mt-1">
    <div class="row">
        
        <!-- col md 3 -->
    <div class="col-md-3 ">
    <div id="accordion">
  <div class="card">
    <div class="card-header">
      <a class="card-link" data-toggle="collapse" href="#collapseOne">
        Arama Yap <i class="fas fa-search"></i>
      </a>
    </div>
    <div id="collapseOne" class="collapse show" data-parent="#accordion">
      <div class="card-body">
        
    <form id="form2" method="get" action="kurumsallar">
      
      <div class="row mx-auto">
    
    
    <div class="form-group col-md-12" id="illerf2">
    <label for="select" class="control-label">İl</label>
<select class="form-control" id="il" name="il">
<option value="0">İl Seçiniz</option>
<?php
include "db.php";
$query=$db->query("SELECT id,il_adi FROM il ORDER BY id ASC");
while($row=$query->fetch()){
    if($_GET["il"]==$row["id"])
         echo '<option value="'.$row['id'].'" selected>'.$row['il_adi'].'</option>';
    else
        echo '<option value="'.$row['id'].'">'.$row['il_adi'].'</option>';
}
?>
</select>
</div>

 
<div class="form-group col-md-12" id="ilcelerf2">
<label for="select" class="control-label">İlçe</label>
<select class="form-control"  name="ilce" id="ilce">
<option value="0">İlçe Seçiniz</option>

<?
if(isset($_GET["ilce"])){
    
include "db.php";
$query=$db->query("SELECT ilce.id as ilceid,ilce.ilce_adi FROM il,ilce WHERE il.id=ilce.il_id AND il.id=".$_GET["il"]." ORDER BY il.id ASC");
while($row=$query->fetch()){
    if($_GET["ilce"]==$row["ilceid"])
         echo '<option value="'.$row['ilceid'].'" selected>'.$row['ilce_adi'].'</option>';
    else
        echo '<option value="'.$row['ilceid'].'">'.$row['ilce_adi'].'</option>';
}

}
?>

</select>
</div>

<!-- İl ilçe AJAX-->
 <script type="text/javascript">
 $(function(){
 $("#illerf2 select").change(function(){
 var deger = $(this).val();
 var degerler = $("#form2").serialize();
  //alert(degerler);
 $.ajax({
 type:"POST",
 url:"ilce_kontrol.php",
 data:degerler,
 success:function(x){
$('#ilcelerf2').find('option').remove();
 $("#ilcelerf2 select").prepend(x);
 }
 });
 });
});
</script>

<script>
$(function() {
 //$("#ilcelerf2 select").prop("disabled","true"); //ilçe disabled olsun sayfa yüklendiğinde
        $("#illerf2 select").change(function(){ //il secimi yapıldıgında 
           // console.log($("#illerf2 select").val()); secili il value
               if($("#illerf2 select").val()==0) //eğer secili il value 0("il seçiniz yazısı") na eşitse
            $("#ilcelerf2 select").prop("disabled","true"); //ilcelerf2 select disabled
        else//value 0 dan farklı ise
            $("#ilcelerf2 select").removeAttr('disabled'); //ilcelerf2 select yapılabilir. disabled kaldır
        });
});

 </script>
  <!-- İl ilçe AJAX SON-->



</div><!-- row !-->
<!-- İl ilçe HTML SON-->

<button type="submit" class="btn btn-outline-primary btn-block"><i class="fas fa-search"></i> ARA </button>

</form> <!-- esas form -->
        
        
      </div>
    </div>
  </div>
 </div>
</div> 
    
    <!-- col md 3 SON -->
   

<div class="col-md-9">
        
        
        <?php 
        if((isset($_GET["ilce"]) and !isset($_GET["il"])) or (!isset($_GET["il"]) and !isset($_GET["ilce"]))){
            $tumkurumsalgetir=$db->query("SELECT kurumsal.*,il.il_adi,ilce.ilce_adi FROM il,ilce,kurumsal 
        where il.id=ilce.il_id 
        AND kurumsal.il_id=il.id 
        AND kurumsal.ilce_id=ilce.id
        GROUP BY kurumsal.ad");
        }
            else{
         
        $durum=""; $adres=" ";
        if(isset($_GET["il"]) and $_GET["il"]>0){
            $ilid=$_GET["il"];
            $durum.="il.id=$ilid";
            $queryil=$db->query("SELECT il.il_adi FROM il WHERE il.id=$ilid")->fetch(PDO::FETCH_ASSOC);
            $adres.=$queryil["il_adi"];
        }
        else{
            //echo "lütfen il seçin";
            header("Location: https://www.omurserdar.com/404.php");
            exit;
        }
            
        if(isset($_GET["ilce"]) and $_GET["ilce"]>0 ){
            
            $ilceid=$_GET["ilce"];
            $durum.=" AND ilce.id=$ilceid";
            $queryilce=$db->query("SELECT ilce.ilce_adi FROM il,ilce WHERE il.id=ilce.il_id AND ilce.id=$ilceid")->fetch(PDO::FETCH_ASSOC);
            $adres.="/".$queryilce["ilce_adi"];
        }
            
      
                
        $tumkurumsalgetir=$db->query("SELECT kurumsal.*,il.il_adi,ilce.ilce_adi FROM il,ilce,kurumsal 
        where il.id=ilce.il_id 
        AND kurumsal.il_id=il.id 
        AND kurumsal.ilce_id=ilce.id
        AND $durum
        GROUP BY kurumsal.ad");
            }
        
        
        if($tumkurumsalgetir->rowCount()>0){
            echo "<h5 class='text-primary'>Toplam kayıt sayısı: ".$tumkurumsalgetir->rowCount().$adres."</h5>";
        }
        else{
            echo "<h5 class='text-primary'>Kayıt Yok</h5>";
        }
        
        
        ?>
        
        <div class="row">
            <div class="say" id="<?=$tumkurumsalgetir->rowCount()?>" hidden></div>
        <?
        $qrsayac=0;
        while($tumkurumsalcek=$tumkurumsalgetir->fetch(PDO::FETCH_ASSOC)){
            
            if($tumkurumsalcek["acikMi"]==0)
                $renk="danger";
            else
                $renk="success";
            
        ?>
  <div class="col-md-4"> 
    <div class="card text-white mr-1 mt-1 bg-<?=$renk?>">
     <div class="card-header" id="chka<?=$tumkurumsalcek["id"]?>"><?=$tumkurumsalcek["ad"]?></div>
      <div class="card-body">
      <h6 class="card-title">
          <?if($tumkurumsalcek["acikMi"]==0){
            echo '<span class="badge badge-danger"><i class="fas fa-lock"></i> KAPALI - Sipariş Verilemez</span>';
        }
        else{
            echo '<span class="badge badge-success"><i class="fas fa-lock-open"></i> AÇIK - Sipariş Verilebilir</span>';
        }
        ?>
          </h6>
          
          <span><i class="fas fa-map-marker-alt"></i> <?=$tumkurumsalcek["ilce_adi"]." / ".$tumkurumsalcek["il_adi"]?> </span>
          
          <div align="center" data-id="<?=$tumkurumsalcek["kullaniciadi"]?>" id="qrcode<?=$qrsayac?>"></div>
              
         
      </div>
         <div class="card-footer">
           <a href="kurumsal/<?=$tumkurumsalcek["kullaniciadi"]?>" class="btn btn-outline-light btn-block pull-right">İncele <i class="fas fa-arrow-circle-right"></i></a>
           
        </div>
    </div>
</div>   
        <? 
        $qrsayac++;
        }
        ?>
        
        <script>
                var lim=$(".say").attr("id");
                
                
                var i;
                for (i = 0; i < lim; i++) {
                    var ad= $('#qrcode'+i).attr("data-id");
                    
                    $('#qrcode'+i).qrcode({ 
                        width: 200,
                        height: 200,
                        render : "table", 
                        text : "https://www.api.omurserdar.com/kurumsal/"+ad
                    });
                }
              </script>
        
        
        </div>
    </div><!-- col-md-8 -->
      </div><!-- row -->
      </div>
      
      <?
      if(isset($_SESSION["kullanici_tip"])&&$_SESSION["kullanici_tip"]=="bireysel"){
	    include "sepetmodaljs.php";
	}
	?>
	</body>
	</html>
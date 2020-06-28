<?php
include "db.php";
if(isset($_POST["id"]))
    $id=strip_tags($_POST["id"]); //id güvenlik kontrolü

$url = "https://api.omurserdar.com/api/kurumsal?id=$id";
$json = file_get_contents($url);
$jsonverilerim = json_decode($json, true);
$kumekurbilgi=$jsonverilerim["kurumsalbilgileri"];
$kumekurbilgikurtab=$jsonverilerim["kurumsalTab"];
$kumekurbilgi_kurenvtab=$jsonverilerim["kurumsalEnv"];
if(!empty($kumekurbilgi)){
    $response='<div class="row text-center">';
    $sayac=1;
// $renkler=array("primary","secondary","success","danger","warning","info","dark"); $anahtar=array_rand($renkler,1);  $renkler[$anahtar]
     foreach($kumekurbilgikurtab as $kurbilgikt){
         $menuid=$kurbilgikt["id"];
         $response.='<div class="col-md-6"><div class="ml-1 mb-1 card border-success" style="max-width: 22rem;" id="accordion">
                <div class="card-header">
  <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$sayac.'" id="'.$menuid.'">';$response.=$kurbilgikt["ad"].'
  <span class="badge badge-primary badge-pill">'.$kurbilgikt["sayi"].'</span>
  </a>
    </div>
  <div class="card-body">
    <div id="collapse'.$sayac.'" class="panel-collapse collapse in">
      <div class="panel-body">';
         foreach($kumekurbilgi_kurenvtab as $kurbilgi_ket){
            if($kurbilgikt["id"]==$kurbilgi_ket["tabid"]){
                $response.='<div class="ml-1 mb-1 card border-dark">
  <div class="card-body">
    <h5 class="card-title">'.$kurbilgi_ket["ad"].' <span class="badge badge-pill badge-info">'.$kurbilgi_ket['fiyat'].' &#8378;</span></h5>';
    if($kurbilgi_ket["tanim"]!=" "){ 
        $response.='<h6 class="card-subtitle mb-2 text-muted">'.$kurbilgi_ket["tanim"].'</h6>';
    } ?>
        <? if($kurbilgi_ket["alim"]==1){
            $response.="<span class='badge badge-pill badge-success'><i class='fas fa-toggle-on'></i> Sipariş Verilebilir </span>";
            $response.='<br/><button type="button" id="'.$kurbilgi_ket["envanterid"].'" style="font-size:18px" class="btnsepetEkle btn btn-outline-primary btn-outline btn-sm mt-2"><i class="fas fa-cart-plus"></i> EKLE </button>';
            
        }
        else{
            $response.="<span class='badge badge-pill badge-danger'><i class='fas fa-toggle-off'></i> Sipariş Verilemez  </span>";
        }
       $response.=" </div>
</div>";
 }
}
        $response.="  </div>
        </div>
    </div>
  </div>
</div>";
$sayac++;} 
  $response.=" </div> ";  
 } 

$dizim=array();
$dizim["ad"]=$kumekurbilgi["ad"];
$dizim["cevap"]=$response;
echo json_encode($dizim);

exit;
?>
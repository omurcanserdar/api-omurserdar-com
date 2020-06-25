<?php

//projeye başlarken; dbye, yemeksepetindeki herhangi bir firmanın verilerini curl ile ekledim

/*

//GOOGLE
try {

 //$db = new PDO("mysql:host=35.224.252.205;port=3306;dbname=dbforcloud","root","serdar61"); 
$host="mysql:dbname=dbforcloud;host=35.224.252.205;port=1433";

	$db=new PDO($host,'omur','serdar61');
	if($db){
	    $sorgu=$db->query("select * from tasks")->fetch(PDO::FETCH_ASSOC);
	    echo $sorgu["title"];
	}
	echo "vuhuu";
}
catch (PDOExpception $e) {
	echo $e->getMessage();
}


//AZURE
try {
    $conn = new PDO("sqlsrv:server = tcp:testbulut.database.windows.net,1433; Database = DBtest", "omurserdarr", "serdar61");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

// SQL Server Extension Sample Code - AZURE
$connectionInfo = array("UID" => "omurserdarr", "pwd" => "{your_password_here}", "Database" => "DBtest", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:testbulut.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);


//rand(0, 1);


//hosting
try {
	$db=new PDO("mysql:host=localhost;dbname=omurserd_webapidb;charset=utf8",'omurserd_yurtduny_omurdb','ortak*1967');
	//echo "<script>alert('veritabanı bağlantısı başarılı');</script>";
}
catch (PDOExpception $e) {
	echo $e->getMessage();
}

function rastgeleTF(){
    return rand(0,1);
}



$url='https://www.yemeksepeti.com/sport-food-adapazari-cark-cad-sakarya';
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
$data = curl_exec($curl);

$dom = new DOMDocument;
$tabMenuDizim=array();
echo "----------TAB MENÜLER ----------<br>";
$k_id=9;
if($dom->loadHTML($data, LIBXML_NOWARNING)){
    foreach($dom->getElementsByTagName('h2') as $link) {
        $tabMenuAd = $link->nodeValue;
        echo $tabMenuAd."<br>";
        array_push($tabMenuDizim,$tabMenuAd);
        //$tabekle=$db->prepare("insert into tabMenu SET kurumsal_id=:pid,ad=:pad");
	    //$tabekle->execute(array('pid' => $k_id,'pad'=>$tabMenuAd));
    }
}





echo "----------ALT MENÜLER (envanter) ----------<br>";
    $dom2 = new DOMDocument;
    $dom2->loadHTML($data, LIBXML_NOWARNING);
   
    $envanterAdDizim=array();
    $envanterDetayDizim=array();
    $envanterFiyatDizim=array();
    $envanterAlimDizim=array();
   
   // $tab_id=0;$ad="";$tanim="";$fiyat=0;$alinabilirMi=0;
    
        foreach ($dom2->getElementsByTagName('div') as $tag) {
           if($tag->getAttribute('class')=='product-info'){
              foreach($tag->getElementsByTagName('a') as $atag){
                    $envanter=$atag->nodeValue;
                    if($envanter!="  "){
                            array_push($envanterAdDizim,trim($envanter));
                            echo $envanter."<br>";
                    } 
                      foreach($tag->getElementsByTagName('strong') as $atag){ 
                     if($atag->getAttribute('data-top-sold-product')=='false'){
                         $envanter=$atag->nodeValue;
                    if($envanter!="  "){
                            array_push($envanterAdDizim,trim($envanter));
                            echo $envanter."<br>";
                    } 
                          }
                      }
                }  
           }
           elseif($tag->getAttribute('class')=='product-desc'){
                    $envanterTanim=$tag->nodeValue;
                            array_push($envanterDetayDizim,$envanterTanim);
                            echo $envanterTanim."<br>";
           }
           elseif($tag->getAttribute('class')=='product-price' or $tag->getAttribute('class')=='product-price discounted'){
              foreach($tag->getElementsByTagName('span') as $atag){ 
                     if($atag->getAttribute('class')=='listed-price'){
                        continue;
              }
              elseif($atag->getAttribute('class')=='price'){
                   $deger=substr($atag->nodeValue,0,strlen($atag->nodeValue)-3);
                    $deger=str_replace(",",".",$deger);
                    array_push($envanterFiyatDizim,$deger);
                    array_push($envanterAlimDizim,rastgeleTF());
              }
                    else{
                   $deger=substr($atag->nodeValue,0,strlen($atag->nodeValue)-3);
                   $deger=str_replace(",",".",$deger);
                            array_push($envanterFiyatDizim,$deger);
                            array_push($envanterAlimDizim,rastgeleTF());
                    }
              }
           }
        }

for($i=1;$i<6;$i++){
            array_shift($envanterAdDizim);
            array_shift($envanterDetayDizim);
            array_shift($envanterFiyatDizim);    
            array_shift($envanterAlimDizim); 
}
           $tabMenuDizim=array_combine(range(1, count($tabMenuDizim)), array_values($tabMenuDizim));
           $envanterAdDizim=array_combine(range(1, count($envanterAdDizim)), array_values($envanterAdDizim));
           $envanterDetayDizim=array_combine(range(1, count($envanterDetayDizim)), array_values($envanterDetayDizim));
           $envanterFiyatDizim=array_combine(range(1, count($envanterFiyatDizim)), array_values($envanterFiyatDizim));
           $envanterAlimDizim=array_combine(range(1, count($envanterAlimDizim)), array_values($envanterAlimDizim));

           
            echo '<pre>';
       print_r(($tabMenuDizim));
       echo '</pre>';
 echo count($tabMenuDizim);
           
     echo '<pre>';
       print_r(($envanterAdDizim));
       echo '</pre>';
 echo count($envanterAdDizim);
 
 
     echo '<pre>';
       print_r(($envanterDetayDizim));
       echo '</pre>';
 echo count($envanterDetayDizim);
 
 echo '<pre>';
       print_r(($envanterFiyatDizim));
       echo '</pre>';
 echo count($envanterFiyatDizim);
 
 echo '<pre>';
       print_r(($envanterAlimDizim));
       echo '</pre>';
 echo count($envanterAlimDizim);
 
 $sira=1;
 foreach($envanterAdDizim as $eak){
     switch ($eak) {
case "Sporcu Çocuk Menüsü 1":
   $tab_id=63;
    break;
case "Mevsim Sebzeleri Çorbası":
   $tab_id=64;
    break;
case "Mücver":
   $tab_id=65;
    break;
case "Kinoalı Ekmeğe Peynirli Tost":
   $tab_id=66;
    break;
case "Tavuklu Salata":
   $tab_id=67;
    break;
case "Ayran (20 cl.)":
   $tab_id=68;
    break;
case "Yeşil Detoks (40 cl.)":
   $tab_id=69;
    break;
     }
     
    
    $envekle=$db->prepare("insert into envanter SET kurumsal_id=:pid,tabMenu_id=:p_tid,ad=:pad,tanim=:ptan,fiyat=:pfiy,alinabilirMi=:palim");
     $envekle->execute(array('pid' => $k_id,
                             'p_tid'=>$tab_id,
                             'pad'=>$eak,
                             'ptan'=>$envanterDetayDizim[$sira],
                             'pfiy'=>$envanterFiyatDizim[$sira],
                             'palim'=>$envanterAlimDizim[$sira]));
                             $sira++;
                         
                             
 }
     
*/
 
 
?>
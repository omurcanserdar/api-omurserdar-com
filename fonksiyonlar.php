<?php 

//IP ADRES
//https://www.javatpoint.com/how-to-get-the-ip-address-in-php
function getIP() {  
    //whether ip is from the share internet  
     if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
                $ip = $_SERVER['HTTP_CLIENT_IP'];  
        }  
    //whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
     }  
//whether ip is from the remote address  
    else{  
             $ip = $_SERVER['REMOTE_ADDR'];  
     }  
     return $ip;  
}  
//SON IP ADRES

//bootstrap renklerinden rastgele 1ini return eder
function bsRenkYaziUret(){
     $renkDizi=array("primary","success","secondary","warning","info");
     return $renkDizi[array_rand($renkDizi,1)];
 }
//son bs renk


//türkçe tarih
function turkcetarih($datetime){
    $z = date('j F Y l H:i', strtotime($datetime));
    $gun_dizi = array(
        'Monday'    => 'Pazartesi',
        'Tuesday'   => 'Salı',
        'Wednesday' => 'Çarşamba',
        'Thursday'  => 'Perşembe',
        'Friday'    => 'Cuma',
        'Saturday'  => 'Cumartesi',
        'Sunday'    => 'Pazar',
        'January'   => 'Ocak',
        'February'  => 'Şubat',
        'March'     => 'Mart',
        'April'     => 'Nisan',
        'May'       => 'Mayıs',
        'June'      => 'Haziran',
        'July'      => 'Temmuz',
        'August'    => 'Ağustos',
        'September' => 'Eylül',
        'October'   => 'Ekim',
        'November'  => 'Kasım',
        'December'  => 'Aralık',
    );
    foreach($gun_dizi as $en => $tr){
        $z = str_replace($en, $tr, $z);
    }
    //if(strpos($z, 'Mayıs') !== false && strpos($format, 'F') === false) $z = str_replace('Mayıs', 'May', $z);
    return $z;
}
//türkçe tarih son


//session yoket ve aktif sayfayı yenile

function sesDestYon(){
    session_start();
    session_destroy();
    //$root=$_SERVER["DOCUMENT_ROOT"];
    //api.omurserdar.com
    header("Location=api.omurserdar.com");
}

//

//API İSTEK SONUNDA BAŞLIK AYARLA VE JSON YAZ

function baslikAyarlaJSONyaz($httpKOD,$jsonparam){
SetHeader($httpKOD);
$jsonparam[$httpKOD] = HttpStatus($httpKOD);
echo json_encode($jsonparam);
}

//SON API İSTEK SONUNDA BAŞLIK AYARLA VE JSON DÖNDÜR

//mail gönder 

function mailGonder($gonderici,$gondericiUsername=null,$alici,$subject=null,$body,$altbody=null){
    
    require $_SERVER['DOCUMENT_ROOT']."/class/class.phpmailer.php";
                
    //EMAİL
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 1; // Hata ayıklama değişkeni: 1 = hata ve mesaj gösterir, 2 = sadece mesaj gösterir
    $mail->SMTPAuth = true; //SMTP doğrulama olmalı ve bu değer değişmemeli
    $mail->SMTPSecure = 'ssl'; // Normal bağlantı için tls , güvenli bağlantı için ssl yazın
    $mail->Host = "api.omurserdar.com"; // Mail sunucusunun adresi (IP de olabilir)
    $mail->Port = 465; // Normal bağlantı için 587, güvenli bağlantı için 465 yazın
    $mail->IsHTML(true);
    //$mail->SetLanguage("tr", "phpmailer/language");
    //$mail->CharSet  ="utf-8";
    
    $mail->Username = $gonderici; // Gönderici adresinizin sunucudaki kullanıcı adı (e-posta adresiniz)
    $mail->Password = "Serdar*1461"; // Mail adresimizin sifresi
                
                //MAİL İCERİK
    $mail->Subject = $subject; // Email konu başlığı
    $mail->Body = $body; // Mailin içeriği
    
    //$mail->AltBody = "This is the plain text version of the email content";
              
    /*CC ÇOKLU 
              
    $katilimcilar = array(
    'person1@domain.com' => 'Person One',
    'person2@domain.com' => 'Person Two',
     // ..
    );
    foreach($katilimcilar as $email => $name){
        $mail->AddCC($email, $name);
    }
              
    SON CC ÇOKLU */ 
    // TEK CC $mail->addCC("cc@example.com"); 
              
    //CEVAP ADRESİ $mail->AddReplyTo("cevap@api.omurserdar.com", "Cevap Adresi");
              
    $mail->AddAddress($alici); //mail alıcı adres
    $mail->SetFrom($gonderici, $gondericiUsername);
    // Mail atıldığında gorulecek isim ve email
                
    if($mail->Send()){
        return true;
    }
    else{
        echo "Email Gönderim Hatasi: ".$mail->ErrorInfo;
        return false;
    }
    
}

//son şifre mail gönder

// https://tools.ietf.org/html/rfc7231
function HttpStatus($kod) {
 $status = array(
        100 => 'Continue',101 => 'Switching Protocols',
        200 => 'OK', 201 => 'Created',  
        202 => 'Accepted',203 => 'Non-Authoritative Information',  
        204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content',  
        300 => 'Multiple Choices', 301 => 'Moved Permanently',
        302 => 'Found',  303 => 'See Other', 304 => 'Not Modified',  
        305 => 'Use Proxy', 306 => '(Unused)', 307 => 'Temporary Redirect', 
        400 => 'Bad Request', 401 => 'Unauthorized', 
        402 => 'Payment Required', 403 => 'Forbidden',          
        404 => 'Not Found', 405 => 'Method Not Allowed', 
        406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 
        408 => 'Request Timeout', 409 => 'Conflict',           
        410 => 'Gone', 411 => 'Length Required',    
        412 => 'Precondition Failed', 413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',  415 => 'Unsupported Media Type',  
        416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed',
        500 => 'Internal Server Error', 501 => 'Not Implemented',    
        502 => 'Bad Gateway', 503 => 'Service Unavailable',
        504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported');
        
    return $status[$kod] ? $status[$kod] : $status[500];
}

// API Gönderilecek Veri Header ayarlama fonksiyonu 
function SetHeader($kod){
    header("HTTP/1.1 ".$kod." ".HttpStatus($kod));
    header("Content-Type: application/json; charset=utf-8;");
}

//API index header
function beginAPIHeader(){
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
}

// örneğin bireysel yoksa (diğer fonk farkı json return etmez, bool return eder)
function spesifiksesvarmi($psesval,$pseskey){
    //session_start();
    if(isset($_SESSION[$psesval])&&$_SESSION["kullanici_tip"]==$pseskey)
        return true;
    else
        return false;
    //session_destroy();
}
//son spesifiksesvarmi


function sesYoksaCik($param,$deger=null){
    session_start();
    if(!isset($_SESSION[$param])||$_SESSION[$param]!=$deger){
        $jsonArray=array();
        $httpKOD = 401; //unauthorized
        SetHeader($httpKOD);
        $jsonArray[$httpKOD] = HttpStatus($httpKOD);
        $jsonArray["hata"]=true;
        $jsonArray["mesaj"]="yetkiyok";
        echo json_encode($jsonArray);
        exit;
    }
}

function genelSesYoksaCik($param){
    session_start();
    if(!isset($_SESSION[$param])){
        $jsonArray=array();
        $httpKOD = 403; //forbidden
        SetHeader($httpKOD);
        $jsonArray[$httpKOD] = HttpStatus($httpKOD);
        $jsonArray["hata"]=true;
        $jsonArray["mesaj"]="oturumhata";
        echo json_encode($jsonArray);
        exit;
    }
    else
    return true;
}


function pdoMultiInsert($tableName, $data, $pdoObject){
    //Will contain SQL snippets.
    $rowsSQL = array();
 
    //Will contain the values that we need to bind.
    $toBind = array();
    
    //Get a list of column names to use in the SQL statement.
    $columnNames = array_keys($data[0]);
 
    //Loop through our $data array.
    foreach($data as $arrayIndex => $row){
        $params = array();
        foreach($row as $columnName => $columnValue){
            $param = ":" . $columnName . $arrayIndex;
            $params[] = $param;
            $toBind[$param] = $columnValue; 
        }
        $rowsSQL[] = "(" . implode(", ", $params) . ")";
    }
 
    //Construct our SQL statement
    $sql = "INSERT INTO `$tableName` (" . implode(", ", $columnNames) . ") VALUES " . implode(", ", $rowsSQL);
 
    //Prepare our PDO statement.
    $pdoStatement = $pdoObject->prepare($sql);
 
    //Bind our values.
    foreach($toBind as $param => $val){
        $pdoStatement->bindValue($param, $val);
    }
    
    //Execute our statement (i.e. insert the data).
    //$pdoStatement->errorInfo();
    
    return $pdoStatement->execute();
}

function sipKodUret(){
$len = 4;   // total number of numbers
$min = 100;  // minimum
$max = 999;  // maximum
$range = []; // initialize array
$kod="";
foreach(range(0, $len - 1) as $i) {
    while(in_array($num = mt_rand($min, $max), $range));
    $range[] = $num;
    $kod.=$num;
}
return $kod;
}

//Genrate Random Key
function generateRandomKey(){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 32; $i++)
    {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//Genrate Random Key


/*
function ara($arrays, $key, $search) {
   $count = 0;
   foreach($arrays as $object) {
       if(is_object($object)) {
          $object = get_object_vars($object);
       }
       if(array_key_exists($key, $object) && $object[$key] == $search) $count++;
   }
   return $count;
}
*/
 
// kullanıcı adının uyumluluğunu kontrol eden fonksiyonumuz.
function kullaniciAdi($s) {
    $tr = array('ş','Ş','ı','İ','ğ','Ğ','ü','Ü','ö','Ö','Ç','ç');
    $eng = array('s','s','i','i','g','g','u','u','o','o','c','c');
    $s = str_replace($tr,$eng,$s);
    $s = strtolower($s);
    $s = preg_replace('/&.+?;/', '', $s);
    $s = preg_replace('/[^%a-z0-9 _-]/', '', $s);
    $s = preg_replace('/\s+/', '-', $s);
    $s = preg_replace('|-+|', '-', $s);
    $s = trim($s, '-');
 
    return $s;
}
   
    
?>
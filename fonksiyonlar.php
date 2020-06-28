<?php 

// https://tools.ietf.org/html/rfc7231
function HttpStatus($kod) {
 $status = array(
        100 => 'Continue',         
        101 => 'Switching Protocols',  
        200 => 'OK',               
        201 => 'Created',  
        202 => 'Accepted',         
        203 => 'Non-Authoritative Information',  
        204 => 'No Content',       
        205 => 'Reset Content',  
        206 => 'Partial Content',  
        300 => 'Multiple Choices',  
        301 => 'Moved Permanently',
        302 => 'Found',  
        303 => 'See Other',        
        304 => 'Not Modified',  
        305 => 'Use Proxy',        
        306 => '(Unused)',  
        307 => 'Temporary Redirect', 
        400 => 'Bad Request',  
        401 => 'Unauthorized',       
        402 => 'Payment Required',  
        403 => 'Forbidden',          
        404 => 'Not Found',  
        405 => 'Method Not Allowed', 
        406 => 'Not Acceptable',  
        407 => 'Proxy Authentication Required', 
        408 => 'Request Timeout',  
        409 => 'Conflict',           
        410 => 'Gone',  
        411 => 'Length Required',    
        412 => 'Precondition Failed',  
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',  
        415 => 'Unsupported Media Type',  
        416 => 'Requested Range Not Satisfiable',  
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',  
        501 => 'Not Implemented',    
        502 => 'Bad Gateway',  
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',  
        505 => 'HTTP Version Not Supported');
        
    return $status[$kod] ? $status[$kod] : $status[500];
}

// Header ayarlama fonksiyonu 
function SetHeader($kod){
    header("HTTP/1.1 ".$kod." ".HttpStatus($kod));
    header("Content-Type: application/json; charset=utf-8;");
}

//session yoksa çık
function sesYoksaCik($param,$deger){
    session_start();
    if(!isset($_SESSION[$param])||$_SESSION[$param]!=$deger||empty($_SESSION[$param])){
        $jsonArray=array();
        $httpKOD = 403; //forbidden
        SetHeader($httpKOD);
        $jsonArray[$httpKOD] = HttpStatus($httpKOD);
        $jsonArray["hata"]=true;
        $jsonArray["mesaj"]="oturumhata";
        echo json_encode($jsonArray);
        exit;
    }
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
    return $pdoStatement->execute();
}

function sipKodUret(){
$len = 4;   // total number of numbers
$min = 100;  // minimum
$max = 999;  // maximum
$range = []; // initialize array
$kod="S-";
foreach(range(0, $len - 1) as $i) {
    while(in_array($num = mt_rand($min, $max), $range));
    $range[] = $num;
    $kod.=$num;
}
return $kod;
}

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
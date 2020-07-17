<?php

try {
	$db=new PDO("mysql:host=localhost;dbname=omurserd_webapidb;charset=utf8",'***','***');
	//echo "<script>alert('veritabanı bağlantısı başarılı');</script>";
}
catch (PDOExpception $e) {
	echo $e->getMessage();
}

?>

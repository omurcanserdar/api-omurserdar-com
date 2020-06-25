<?php

try {
	$db=new PDO("mysql:host=localhost;dbname=omurserd_webapidb;charset=utf8",'omurserd_yurtduny_omurdb','ortak*1967');
	//echo "<script>alert('veritabanı bağlantısı başarılı');</script>";
}
catch (PDOExpception $e) {
	echo $e->getMessage();
}

?>
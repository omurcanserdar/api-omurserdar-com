<?php
try {
    $db=new PDO("mysql:host=localhost;dbname=omurserd_webapidb;charset=utf8",'***','***');
}
catch (PDOExpception $e) {
	echo $e->getMessage();
}
?>
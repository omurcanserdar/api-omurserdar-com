<?php 
	require_once("db.php");
	
	if($_POST){
	
		$id = $_POST["il"];
		$bul = $db->prepare("select * from ilce where il_id=:pilid");
		$bul->execute(['pilid' => $id]); 
		echo '<option value="0">İlçe Seçiniz</option>';
		while($row = $bul->fetch()){
		echo '<option value="'.$row["id"].'">'.$row["ilce_adi"].'</option>';
		}
	}else{
		return false;
	}
?>
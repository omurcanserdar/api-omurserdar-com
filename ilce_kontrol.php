<?php 
	require_once("db.php");
	
	if($_POST){
	
		$id = $_POST["il"];
		$bul = $db->prepare("select * from ilce where il_id=:pilid");
		$bul->execute(['pilid' => $id]);
		
		/*
		$urlm=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; //url cek
        $dizim=explode("?",$urlm);
		//print_r($dizim);
        
        $dizim2=explode("/",$dizim[0]);
		//print_r($dizim2);
		if($dizim2[2]=="kurumsallar")
		    echo '<option value="hepsi">Tümü</option>';
		    */
		//echo '<option value="0">İlçe Seçiniz</option>';    
		while($row = $bul->fetch()){
		echo '<option value="'.$row["id"].'">'.$row["ilce_adi"].'</option>';
		}
	}else{
		return false;
	}
?>
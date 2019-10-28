<?php
	//usa $id_inserito;

	$stringa_errori_file = "";
	$uploaddir = $_SESSION["path_upload_admin"];

	if((isset($_POST["remove_img"]) && $_POST["remove_img"]==1) || $_POST["hidden_remove_img"]==1){
		if($currfile!=""){
			if(file_exists($uploaddir.$_SESSION["path_filecaricati"].$currfile)){
				unlink($uploaddir.$_SESSION["path_filecaricati"].$currfile);
			}
		}
		$insertSQL = sprintf("UPDATE %s SET 
						 fileimg=%s
						 WHERE idc=%s",
			   $tabella,
			   GetSQLValueString("", "text"),
			   GetSQLValueString($id_inserito, "int"));

		mysqli_select_db($std_conn, $database_std_conn);
		$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
	}

	//first include the photo class	
	//@include 'photo.php';

	if(isset($_FILES["fileimg"]["name"]) && $_FILES["fileimg"]["name"]!=NULL)  {

		$nomeCampoForm = "fileimg";
		$thefile = preg_replace('/[^(A-Za-z0-9.)]*/','', $_FILES[$nomeCampoForm]['name']);
		$nome_file_inserito = $id_inserito."_".$thefile;

		if(isset($_FILES[$nomeCampoForm])) {

			if(move_uploaded_file($_FILES[$nomeCampoForm]['tmp_name'], $uploaddir.$_SESSION["path_filecaricati"]."/".$nome_file_inserito)){
				$insertSQL = sprintf("UPDATE %s SET 
								 fileimg=%s
								 WHERE idc=%s",
					   $tabella,
					   GetSQLValueString($nome_file_inserito, "text"),
					   GetSQLValueString($id_inserito, "int"));
				mysqli_select_db($std_conn, $database_std_conn);
				$Result1 = mysqli_query($std_conn, $insertSQL) or die(mysqli_error($std_conn));
			}
		}
	
	}
	
?>
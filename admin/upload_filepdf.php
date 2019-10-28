<?php
	$stringa_errori_file = "";
	$uploaddir = $_SESSION["path_upload_admin"];
	//first include the photo class	

	//PDF - inizio
	$directory = $uploaddir.$_SESSION["path_upload_pdf"];
	$tabella = "dny_statistiche";
	$nextID = $id_inserito;
	
	//file PDF
	$nomeCampoForm = "file_url";
	$nomeCampoDb = "file_url";

	//rimuovi PDF
	if((isset($_POST["rimuovipdf"]))&&($_POST["rimuovipdf"]=="1")){
		$nomefile = $row_Recordset1[$nomeCampoDb];
		$filesmall = $directory.$nomefile;
		if(($nomefile!="")&&(file_exists($filesmall))){
			unlink($filesmall);
		}
		$updateSQL = sprintf("UPDATE %s SET %s='' WHERE id=%s LIMIT 1",
			$tabella,
			$nomeCampoDb,
			GetSQLValueString($id_inserito, "int"));
		mysqli_select_db($std_conn, $database_std_conn);
		$Result1 = mysqli_query($std_conn, $updateSQL) or die(mysqli_error($std_conn));		
	}
		
	if(isset($_FILES[$nomeCampoForm]) && ($_FILES[$nomeCampoForm]['name']!="")) {
		//pass the image array to the photo class constructor
		$thefile = preg_replace('/[^(A-Za-z0-9.)]*/','', $_FILES[$nomeCampoForm]['name']);
		$nome_file_inserito = $nextID . "_" . $thefile;
		move_uploaded_file( $_FILES[$nomeCampoForm]['tmp_name'], $directory.$nome_file_inserito);
		$updateSQL = sprintf("UPDATE %s SET %s=%s WHERE id=%s LIMIT 1",
			$tabella,
			$nomeCampoDb,
			GetSQLValueString($nome_file_inserito, "text"),
			GetSQLValueString($id_inserito, "int"));
		mysqli_select_db($std_conn, $database_std_conn);
		$Result1 = mysqli_query($std_conn, $updateSQL) or die(mysqli_error($std_conn));
	}
		
	//PDF - fine
?>
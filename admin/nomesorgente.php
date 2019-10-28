<?php
//Restituisce il percorso ed il nome del file del modello di output
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$idfile = $_GET["idfile"];

//Cerco il record nel db e tutti i dati
mysqli_select_db($std_conn, $database_std_conn);
$query_RS1 = sprintf("SELECT *
					FROM dny_file_generati
					WHERE idc = %s", 
					GetSQLValueString($idfile, "int"));


$RS1 = mysqli_query($std_conn, $query_RS1) or die(mysqli_error($std_conn));

if($row_RS1 = mysqli_fetch_assoc($RS1)){

	$filesorgente = $_SESSION["path_upload_admin"] . $_SESSION["path_filecaricati"] . $row_RS1["fileimg"];	

	if( ($row_RS1["fileimg"]!="") && (file_exists($filesorgente)) ){ 
		//trovato
		echo $filesorgente;
	}else{
		//non trovato
		echo "";		
	}

}//Fine controllo esistenza record


?>
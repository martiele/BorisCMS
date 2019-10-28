<?php
//Restituisce il percorso ed il nome del file del modello di output
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$idfile = $_GET["idfile"];

//Cerco il record nel db e tutti i dati
mysqli_select_db($std_conn, $database_std_conn);
$query_RS1 = sprintf("SELECT A.*, B.fileimg as filemodello
					FROM dny_file_generati A LEFT JOIN dny_modelli B ON A.id_sezione = B.ids  
					WHERE A.idc = %s", 
					GetSQLValueString($idfile, "int"));

$RS1 = mysqli_query($std_conn, $query_RS1) or die(mysqli_error($std_conn));

if($row_RS1 = mysqli_fetch_assoc($RS1)){

	$filemodello = $_SESSION["path_upload_admin"] . $_SESSION["path_filemodelli"] . $row_RS1["filemodello"];	
	if( ($row_RS1["filemodello"]!="") && (file_exists($filemodello)) ){
		//trovato
		echo $filemodello;
	}else{
		//non trovato
		echo "";
	}

}//Fine controllo esistenza record


?>
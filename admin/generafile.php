<?php
//
//richiede $id_inserito per recuperare i dati da DB
//


//Cerco il record nel db e tutti i dati
mysqli_select_db($std_conn, $database_std_conn);
$query_RS1 = sprintf("SELECT A.*, B.fileimg as filemodello
					FROM dny_file_generati A LEFT JOIN dny_modelli B ON A.id_sezione = B.ids  
					WHERE A.idc = %s", 
					GetSQLValueString($id_inserito, "int"));
$RS1 = mysqli_query($std_conn, $query_RS1) or die(mysqli_error($std_conn));
$tutto_ok=true;
if($row_RS1 = mysqli_fetch_assoc($RS1)){

	$filemodello = $_SESSION["path_upload_admin"] . $_SESSION["path_filemodelli"] . $row_RS1["filemodello"];	
	if(!( ($row_RS1["filemodello"]!="") && (file_exists($filemodello)) )){ 
		$tutto_ok = false;
	}

	$filesorgente = $_SESSION["path_upload_admin"] . $_SESSION["path_filecaricati"] . $row_RS1["fileimg"];	
	if(!( ($row_RS1["filemodello"]!="") && (file_exists($filesorgente)) )){ 
		$tutto_ok = false;
	}
	
	$nomefilegen = date("YmdHis")."_".substr($row_RS1["fileimg"],strpos($row_RS1["fileimg"],"_")+1);
	$filedest = $_SESSION["path_upload_admin"] . $_SESSION["path_filegenerati"] . $nomefilegen;


	//Se ho la sorgente e il modello inizio il processo di creazione file
	if($tutto_ok){

		//Se avevo già un file generato prima lo elimino sennò mi si riempe l'FTP di file vecchi
		$vecchiofile = $_SESSION["path_upload_admin"] . $_SESSION["path_filegenerati"] . $row_RS1["file_generato"];
		if( ($row_RS1["file_generato"]!="") && (file_exists($vecchiofile)) ){
			unlink($vecchiofile);
		}

		ini_set('memory_limit', '2048M');


		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

		//require_once dirname(__FILE__) . '/./Classes/PHPExcel/IOFactory.php';
		require_once dirname(__FILE__) . '/./Classes/PHPExcel.php';

		$excel2source = PHPExcel_IOFactory::createReader('Excel2007');
		$excel2source = $excel2source->load($filesorgente); // File caricato

		$excel2dest = PHPExcel_IOFactory::createReader('Excel2007');
		$excel2dest = $excel2dest->load($filemodello); // File template buono

		//
		//
		//	QUI INIZIA LA LOGICA DI SOSTITUZIONE DATI 
		//
		//

		require("sostituisci_dati_form.php");

		require("sostituisci_dati_file.php");

    	//
		//
		//	QUI FINISCE LA LOGICA DI SOSTITUZIONE DATI 
		//
    	//

		$objWriter = PHPExcel_IOFactory::createWriter($excel2dest, 'Excel2007');
		$objWriter->save($filedest);


		//Se è andato tutto bene, salvo nel DB il nuovo nome file
		mysqli_free_result($RS1);
		$updateSQL = sprintf("UPDATE dny_file_generati SET file_generato=%s WHERE idc=%s",
			   GetSQLValueString($nomefilegen, "text"),
			   GetSQLValueString($id_inserito, "int"));
		mysqli_select_db($std_conn, $database_std_conn);
		mysqli_query($std_conn, $updateSQL) or die(mysqli_error($std_conn));


	}//Fine controllo è tuttook sui file sorgenti
}//Fine controllo esistenza record


?>
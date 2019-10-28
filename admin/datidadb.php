<?php 
/*
descrizione1pg6
descrizione2pg6
descrizione3pg6
*/


//Restituisce la lista di celle da sovrascrivere con i valori presi da DB
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$idfile = $_GET["idfile"];

//Cerco il record nel db e tutti i sostituzioni
mysqli_select_db($std_conn, $database_std_conn);
$query_RS1 = sprintf("SELECT A.*, B.fileimg as filemodello
				FROM dny_file_generati A LEFT JOIN dny_modelli B ON A.id_sezione = B.ids  
				WHERE A.idc = %s", 
				GetSQLValueString($idfile, "int"));

$RS1 = mysqli_query($std_conn, $query_RS1) or die(mysqli_error($std_conn));

if($row_RS1 = mysqli_fetch_assoc($RS1)){


	$sostituzioni = [];

	$indice = 0;

	//Pagina 0
	$sostituzioni[$indice]->sheet = "Pagina 0";
	$sostituzioni[$indice]->cell = "N23";
	$sostituzioni[$indice]->value = "Codice Doganale HS " . trim($row_RS1["codicedoganalepg0"]);
	$indice++;

	$righe = explode("\n", str_replace("\r", "", $row_RS1["descrizionepg0"]));
	$n_riga_start = 24;
	foreach ($righe as $key => $riga) {
		$sostituzioni[$indice]->sheet = "Pagina 0";
		$sostituzioni[$indice]->cell = 'N'.$n_riga_start;
		$sostituzioni[$indice]->value = trim($riga);
		$indice++;

		$n_riga_start++;
	}	

	/*
	//Pagina 0 (1)
	$sostituzioni[$indice]->sheet = "Pagina 0 (1)";
	$sostituzioni[$indice]->cell = "N23";
	$sostituzioni[$indice]->value =trim($row_RS1["codicedoganalepg0"]);
	$indice++;

	$righe = explode("\n", str_replace("\r", "", $row_RS1["descrizionepg0"]));
	$n_riga_start = 24;
	foreach ($righe as $key => $riga) {
		$sostituzioni[$indice]->sheet = "Pagina 0 (1)";
		$sostituzioni[$indice]->cell = 'N'.$n_riga_start;
		$sostituzioni[$indice]->value = trim($riga);
		$indice++;

		$n_riga_start++;
	}
	*/

	//Indice
	/*
	$sostituzioni[$indice]->sheet = "Indice";
	$sostituzioni[$indice]->cell = "E27";
	$sostituzioni[$indice]->value = trim($row_RS1["schedapaesepg1"]);
	$indice++;
	*/


	//Premessa
	$sostituzioni[$indice]->sheet = "Premessa";
	$sostituzioni[$indice]->cell = "B21";
	$sostituzioni[$indice]->value = trim($row_RS1["riga1pg2"]);
	$indice++;
	$sostituzioni[$indice]->sheet = "Premessa";
	$sostituzioni[$indice]->cell = "B23";
	$sostituzioni[$indice]->value = trim($row_RS1["riga2pg2"]);
	$indice++;


	//Cover
	$sostituzioni[$indice]->sheet = "Cover";
	$sostituzioni[$indice]->cell = "B5";
	$sostituzioni[$indice]->value = trim($row_RS1["descrizionepg3"]);
	$indice++;


	//Worlddata, CountryData, WorldExport
	$testo = "La tabella riporta i dati delle importazioni del prodotto selezionato nell’anno " . trim($row_RS1["annoapg4"]) . " (valore di mercato totale in migliaia di Euro) e il trend di crescita ponderato (media ponderata delle variazioni dal " . trim($row_RS1["annodapg4"]) . " al " . trim($row_RS1["annoapg4"]) . ", ovvero media che attribuisce importanza maggiore ai dati più recenti).";
	//Worlddata
	$sostituzioni[$indice]->sheet = "Worlddata";
	$sostituzioni[$indice]->cell = "B25";
	$sostituzioni[$indice]->value = $testo;
	$indice++;
	//CountryData
	$sostituzioni[$indice]->sheet = "CountryData";
	$sostituzioni[$indice]->cell = "B25";
	$sostituzioni[$indice]->value = $testo;
	$indice++;
	//WorldExport
	$sostituzioni[$indice]->sheet = "WorldExport";
	$sostituzioni[$indice]->cell = "B25";
	$sostituzioni[$indice]->value = $testo;
	$indice++;


	//Ilquadlegenda
	/*
	$testo = trim($row_RS1["percentualepg5"]) . " per il prodotto selezionato, nei mercati DRIVER e DEFENSE saranno indicati i Paesi che hanno";
	$sostituzioni[$indice]->sheet = "Ilquadlegenda";
	$sostituzioni[$indice]->cell = "B30";
	$sostituzioni[$indice]->value = $testo;
	$indice++;
	*/


	/*
	//SchedaLeg1
	$n_riga_start = 8;
	$righe = explode("\n", str_replace("\r", "", $row_RS1["descrizione1pg6"]));
	foreach ($righe as $key => $riga) {
		$sostituzioni[$indice]->sheet = "SchedaLeg1";
		$sostituzioni[$indice]->cell = 'B'.$n_riga_start;
		$sostituzioni[$indice]->value = trim($riga);
		$indice++;
		$n_riga_start++;
	}
	$n_riga_start++;
	$righe = explode("\n", str_replace("\r", "", $row_RS1["descrizione2pg6"]));
	foreach ($righe as $key => $riga) {
		$sostituzioni[$indice]->sheet = "SchedaLeg1";
		$sostituzioni[$indice]->cell = 'B'.$n_riga_start;
		$sostituzioni[$indice]->value = trim($riga);
		$indice++;
		$n_riga_start++;
	}
	$n_riga_start++;
	$righe = explode("\n", str_replace("\r", "", $row_RS1["descrizione3pg6"]));
	foreach ($righe as $key => $riga) {
		$sostituzioni[$indice]->sheet = "SchedaLeg1";
		$sostituzioni[$indice]->cell = 'B'.$n_riga_start;
		$sostituzioni[$indice]->value = trim($riga);
		$indice++;
		$n_riga_start++;
	}
	*/

	//SchedaLeg1
	$sostituzioni[$indice]->sheet = "SchedaLeg1";
	$sostituzioni[$indice]->cell = "B17";
	$sostituzioni[$indice]->value = trim($row_RS1["descrizione1pg6"]);
	$indice++;

	//SchedaLeg2
	$sostituzioni[$indice]->sheet = "SchedaLeg2";
	$sostituzioni[$indice]->cell = "B17";
	$sostituzioni[$indice]->value = trim($row_RS1["descrizione2pg6"]);
	$indice++;

	//SchedaLeg3
	$sostituzioni[$indice]->sheet = "SchedaLeg3";
	$sostituzioni[$indice]->cell = "B14";
	$sostituzioni[$indice]->value = trim($row_RS1["descrizione3pg6"]);
	$indice++;



	//Aggiungo il "foglio sorgente paese" e sparo fuori
	$dati->schedaPaeseSorgente = $row_RS1["foglio"];
	$dati->sostituzioni = $sostituzioni;

	echo json_encode($dati);
	exit;

}else{
	//Non ho trovato il record nel DB.
	//non stampo niente e ciao.
}
?>
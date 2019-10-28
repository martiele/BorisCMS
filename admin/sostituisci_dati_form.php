<?php 
//Qui ci occupiamo solo dei dati provenienti dal form, quindi dal DB
//Il recordset è in $row_RS1

/*
foglio
codicedoganalepg0
descrizionepg0
schedapaesepg1
riga1pg2
riga2pg2
descrizionepg3
annodapg4
annoapg4
percentualepg5
descrizione1pg6
descrizione2pg6
descrizione3pg6
*/

$foglio_da_sel = "Pagina 0";
$excel2dest->setActiveSheetIndexByName($foglio_da_sel);
if($excel2dest->getActiveSheet()->getTitle() == $foglio_da_sel){
	$idfoglioattivo = $excel2dest->getActiveSheetIndex();
	$excel2dest->getActiveSheet()->setCellValue('N21', $row_RS1["codicedoganalepg0"]);
	$righe = explode("\n", str_replace("\r", "", $row_RS1["descrizionepg0"]));
	$n_riga_start = 22;
	foreach ($righe as $key => $riga) {
		$excel2dest->getActiveSheet()->setCellValue('N'.$n_riga_start, $riga);
		$n_riga_start++;
	}	
}


$foglio_da_sel = "Pagina 0 (1)";
$excel2dest->setActiveSheetIndexByName($foglio_da_sel);
if($excel2dest->getActiveSheet()->getTitle() == $foglio_da_sel){
	$excel2dest->getActiveSheet()->setCellValue('N23', $row_RS1["codicedoganalepg0"]);
	$righe = explode("\n", str_replace("\r", "", $row_RS1["descrizionepg0"]));
	$n_riga_start = 24;
	foreach ($righe as $key => $riga) {
		$excel2dest->getActiveSheet()->setCellValue('N'.$n_riga_start, $riga);
		$n_riga_start++;
	}
}




?>
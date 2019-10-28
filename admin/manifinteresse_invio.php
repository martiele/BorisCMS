<?php

	//
	// Ricevo il parametro: $id_man_int -> contenente l'id della manifestazione di interesse
	// 						$titolomail -> oggetto messaggio
	// 						$tipomail	-> può essere "new" o "change" e determina il tipo di messaggio
	//

	//New PhpMailer
	include("../PHPMailer-master/config_consorzio.php");

	$mail->Subject	= $_SESSION["www_title"]." - ".$titolomail;

	$riepilogo_invii .= "<h3>Invii email Manifestazioni</h3>";					
	
	//recupero la categoria della manifestazione
	mysqli_select_db($std_conn, $database_std_conn);
	$query_RecordsetCat = sprintf("SELECT A.id idcat, C.* FROM dny_categoria as A 
									JOIN dny_sottocategoria as B ON A.id = B.id_categoria 
									JOIN dny_manifestazioniinteresse as C ON B.id = C.id_sottocategoria
									WHERE C.id=%s LIMIT 1;", 
						GetSQLValueString($id_man_int,"int"));
	$RecordsetCat = mysqli_query($std_conn, $query_RecordsetCat) or die(mysqli_error($std_conn));
	$row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat);
	$totalRows_RecordsetCat = mysqli_num_rows($RecordsetCat);
	if($totalRows_RecordsetCat>0){
		do{
			$idcat = $row_RecordsetCat["idcat"];

			// Recupero tutti gli utenti che ricevono notifiche da questa categoria
			$query_ciclo_utenti = sprintf("SELECT dny_utente_newsletter.* FROM dny_utente_newsletter JOIN dny_utente_newsletter_categoria ON dny_utente_newsletter.id = dny_utente_newsletter_categoria.id_utente_newsletter WHERE dny_utente_newsletter.deleted=0 AND dny_utente_newsletter.is_attivo=1 AND dny_utente_newsletter_categoria.id_categoria=%s", 
					GetSQLValueString($idcat,"int"));
			$Recordset_ciclo_utenti = mysqli_query($std_conn, $query_ciclo_utenti) or die(mysqli_error($std_conn));
			$row_Recordset_ciclo_utenti = mysqli_fetch_assoc($Recordset_ciclo_utenti);
			$totalRows_Recordset_ciclo_utenti = mysqli_num_rows($Recordset_ciclo_utenti);

			if($totalRows_Recordset_ciclo_utenti>0){
				do{
					//Devo mandare la mail a questi utenti

					//Email al cliente
					if($tipomail=="new"){
						$corpomail = sprintf("<html><body>
						<p>Salve %s,<br /><br />
						e' presente una nuova <strong>Manifestazione di Interesse</strong> sul sito %s.<br /><br />
						<a href='%s'>Collegati al sito</a> e controlla subito le novit&agrave;.</p>",
							$row_Recordset_ciclo_utenti["nome"],
							$_SESSION["www_title"],
							$_SESSION["globalCompleteUrl"]."/area-riservata/?mail=".$row_Recordset_ciclo_utenti["email"]);
					}else if($tipomail=="change"){
						$corpomail = sprintf("<html><body>
						<p>Salve %s,<br /><br />
						la seguente <strong>Manifestazione di Interesse</strong> sul sito %s e' stata aggiornata.<br /><br />
						<a href='%s'>Collegati al sito</a> e controlla subito le novit&agrave;.</p>",
							$row_Recordset_ciclo_utenti["nome"],
							$_SESSION["www_title"],
							$_SESSION["globalCompleteUrl"]."/area-riservata/?mail=".$row_Recordset_ciclo_utenti["email"]);							
					}
					
					$corpomail .= "<p>";
					$corpomail .= "<strong>Ente Appaltante:</strong> ".htmlentities($row_RecordsetCat["EnteAppaltante"],ENT_QUOTES, "UTF-8")."<br />";
					$corpomail .= "<strong>Oggetto Gara:</strong> ".htmlentities($row_RecordsetCat["OggettoGara"],ENT_QUOTES, "UTF-8")."<br />";
					if($row_RecordsetCat["SiNo"]!="")
						$corpomail .= "<strong>Stato:</strong> ".htmlentities($row_RecordsetCat["SiNo"],ENT_QUOTES, "UTF-8")."<br />";
					$corpomail .= "</p>";
	
					$corpomail .= "</body></html>";
					
					$mail->Body = $corpomail;
					$mail_text = $corpomail;

					$mail->AddAddress($row_Recordset_ciclo_utenti["email"]);
					$mail->Send();
					$riepilogo_invii .= "<br />Inviata mail a <strong>".$row_Recordset_ciclo_utenti["nome"]."</strong> (" . $row_Recordset_ciclo_utenti["email"] . ") per le categorie: ".$categorie_invio.".";
					$mail->ClearAddresses();
						
					
				}while($row_Recordset_ciclo_utenti = mysqli_fetch_assoc($Recordset_ciclo_utenti));	
				unset($mail);
				$riepilogo_invii .= "<h3>Testo del messaggio</h3>".$mail_text;
			}
										
			//ciclo utenti
			mysqli_free_result($Recordset_ciclo_utenti);				
			
		}while($row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat)); 
	}
	mysqli_free_result($RecordsetCat);	
	
	/*
	logThis($sufx_sezione, "Inviata mail ".$tipomail, $id_man_int);
	header("Location: ".$sufx_sezione."_gest.php");
	*/
?>
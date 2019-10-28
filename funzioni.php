<?php
if (!isset($_SESSION)) {
  session_start();
}

function setCoockiePers($id,$password){
	$dodiciore = 43200 + time(); 
	setcookie("login_myid", $id, $dodiciore);
	setcookie("login_pswd", md5($password), $dodiciore);
}				 

function doLogin($loginUsername, $password, $remember=0, $redirect=0){
	global $database_std_conn;
	global $std_conn;
	
	$MM_fldUserAuthorization = "level";
	$MM_redirectLoginSuccess = "home.php";
	$MM_redirecttoReferrer = false;
	mysqli_select_db($std_conn, $database_std_conn);
	
	$LoginRS__query=sprintf("SELECT id, email, pswd, level, nome FROM dny_utente WHERE email=%s AND pswd=%s",
	GetSQLValueString($loginUsername, "text"), 
	GetSQLValueString($password, "text")); 
	
	$LoginRS = mysqli_query($std_conn, $LoginRS__query) or die(mysqli_error($std_conn));
	$loginFoundUser = mysqli_num_rows($LoginRS);
	if ($loginFoundUser) {
		$LoginRS_row = mysqli_fetch_assoc($LoginRS);
		$loginStrGroup  = $LoginRS_row["level"];
		
		//declare two session variables and assign them
		$_SESSION['MM_Username'] = $loginUsername;
		$_SESSION['MM_UserGroup'] = $loginStrGroup;	
		//variabili di comodo personali
		$_SESSION['dny_Nome'] = $LoginRS_row["nome"];
		//Image manager tiny MCE
		$_SESSION['isLoggedIn']=true;
		$_SESSION['user']="Mus-e Italia";
				
		if($remember){
			$id = $LoginRS_row["id"];
			$password = $LoginRS_row["pswd"];
			setCoockiePers($id,$password);
		}
		
		if ($redirect) {
			session_write_close();
			header("Location: " . $MM_redirectLoginSuccess );
		}
	}else {
		return 1;
	}
	return 0;
}


function recuperaInfoDaCoockie(){
	global $database_std_conn;
	global $std_conn;

	//vedo se i coockies ci sono e non sono scaduti	
	if(isset($_COOKIE['login_myid'])){ 
		$theid = $_COOKIE['login_myid']; 
		$pswdmd5 = $_COOKIE['login_pswd']; 
		
		//Controllo che la pass corrisponda, evito hacking sul coockie
		mysqli_select_db($std_conn, $database_std_conn);
		$query_login = sprintf("SELECT email, pswd FROM dny_utente WHERE id=%s AND deleted=0", GetSQLValueString($theid, "int"));
		$rslogin = mysqli_query($std_conn, $query_login) or die(mysqli_error($std_conn));
		$row_rslogin = mysqli_fetch_assoc($rslogin);
		$totalRows_rslogin = mysqli_num_rows($rslogin);
		if($totalRows_rslogin>0){
			if(!strcmp(md5($row_rslogin["pswd"]),$pswdmd5)){
				$errori_login=false;
				$errori_login_messaggio="";
				doLogin($row_rslogin["email"],$row_rslogin["pswd"],0,0);
			}
		}
		mysqli_free_result($rslogin);
	}	
}


function data_a_video($valore_db,$mostraOrario=false){
	if($valore_db!="")
		if($mostraOrario)
			return date("d/m/Y H:i:s", strtotime($valore_db));
		else
			return date("d/m/Y", strtotime($valore_db));
	else 
		return "";	
}
function data_a_database($valore_testuale,$separatore="/"){
	if($valore_testuale){
		$date = explode($separatore,$valore_testuale);
		$data_per_db = date("Y-m-d",mktime(0,0,0,$date[1],$date[0],$date[2]));
		return $data_per_db; 
	}else{
		return "";
	}	
}

global $mesi_ita;
$mesi_ita = array("Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre","Ottobre","Novembre","Dicembre");
global $mesi_eng;
$mesi_eng = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");


function data_testuale_eventualmente_tradotta($valore_data){
	global $mesi_ita;
	if (ereg ('([0-9]{2})-([0-9]{2})-([0-9]{4})', $valore_data, $regs)) {
		$gg = $regs[1];
		$mm = ((int)$regs[2]);
		$aaaa = $regs[3];
		$mmtradotto = $mesi_ita[($mm-1)];
		$stringa_data = $gg." ".$mmtradotto." ".$aaaa;
		return $stringa_data; 
	}else{
		return $valore_data;
	}
}

function twodig($val){
	return ($val<10)?("0".$val):($val);
}
function display_data_evento($gg=0,$mm,$aaaa,$idLingua=1){
	//vuole in pasto: gg, mm, aaaa, idLingua
	//e si assume che idLingua = 1 --> Italiano
	//                idLingua = 2 --> Inglese
	//                idLingua = altro --> rendo la data in formato numerico
	//          
	global $mesi_ita;
	global $mesi_eng;
	switch($idLingua){
		case 1: //Italiano
			$mmtradotto = $mesi_ita[($mm-1)];
			if($gg==0){
				$stringa_data = $mmtradotto." ".$aaaa;
			}else{
				$stringa_data = twodig($gg)." ".$mmtradotto." ".$aaaa;
			}
			return $stringa_data; 
			break;
		case 2: // Inglese
			$mmtradotto = $mesi_eng[($mm-1)];
			if($gg==0){
				$stringa_data = $mmtradotto." ".$aaaa;
			}else{
				$stringa_data = twodig($gg)." ".$mmtradotto." ".$aaaa;
			}
			return $stringa_data; 
			break;
		default: //altre lingue
			if($gg==0){
				return twodig($mm)." / ".$aaaa;
			}else{
				return twodig($gg)." / ".twodig($mm)." / ".$aaaa;				
			}
	}
}

function TagliaStringa($stringa, $max_char=160){
	if(strlen($stringa)>$max_char){
		$stringa_tagliata=substr($stringa, 0,$max_char);
		$last_space=strrpos($stringa_tagliata," ");
		$stringa_ok=substr($stringa_tagliata, 0,$last_space);
		return $stringa_ok." (...)";
	}else{
		return $stringa;
	}
}



function logThis($tabella, $descrizione, $id_record=0){
	global $database_std_conn;
	global $std_conn;
	
	$data_azione = date("Y-m-d H:i:s");
	$id_utente_amministratore = ($_SESSION['MM_idUserAdm']>0)?$_SESSION['MM_idUserAdm']:0;
	mysqli_select_db($std_conn, $database_std_conn);
	$query_addlog = SPRINTF("INSERT INTO dny_log (created, id_utente, id_record, tabella, descrizione) VALUES (%s,%s,%s,%s,%s)",
			GetSQLValueString($data_azione,"date"),
			GetSQLValueString($id_utente_amministratore,"int"),
			GetSQLValueString($id_record,"int"),
			GetSQLValueString($tabella,"text"),
			GetSQLValueString($descrizione,"text"));
	mysqli_query($std_conn, $query_addlog) or die(mysqli_error($std_conn));
}

function isUrl($url){
    $info = parse_url($url);
    return ($info['scheme']=='http'||$info['scheme']=='https')&&$info['host']!="";
} 

function giornoSettimana($valore_testuale,$separatore="/"){
	$date = explode($separatore,$valore_testuale);
	$data_formattata = date("Y-m-d",mktime(0,0,0,$date[1],$date[0],$date[2]));

	$weekday_num = date('w', strtotime($data_formattata)); 
	switch($weekday_num){
		case 0: $weekday="Domenica"; break;	
		case 1: $weekday="Luned&igrave;"; break;	
		case 2: $weekday="Marted&igrave;"; break;	
		case 3: $weekday="Mercoled&igrave;"; break;	
		case 4: $weekday="Gioved&igrave;"; break;	
		case 5: $weekday="Venerd&igrave;"; break;	
		case 6: $weekday="Sabato"; break;	
	}
	return $weekday; 
}

function unaPasswordACaso() {
	// setto la gamma di caratteri per generare la password
	// attenzione che la l (L) e 1 (uno) nel risultato possono essere simili
	// se volete potete togliere entrambi dalla stringa seguente
	$gammaDeiCaratteri = "abcdefghijkmnopqrstuvwxyz023456789";

	// inizializzo il generatore di numeri casuali
	// la riga seguente può essere saltata se si usa PHP 4.2.0 o superiore
	srand((double)microtime()*1000000);

	$elaborazione = '' ;
	for ($contatore=0; $contatore<8; $contatore++) {
		$numeroCasuale = rand(0, strlen($gammaDeiCaratteri)-1);
		$carattere = substr($gammaDeiCaratteri, $numeroCasuale, 1);
		$elaborazione = $elaborazione . $carattere;
	}
	return $elaborazione;
}


function DuplicateMySQLRecord($table, $id_field, $id, $id_new=0, $id_field2="", $id2=0) {
	global $database_std_conn;
	global $std_conn;

	if(($id_field2!="")&&($id2>0)){
		//due campi chiave
		
		// load the original record into an array
		mysqli_select_db($std_conn, $database_std_conn);
		$result = mysqli_query($std_conn, "SELECT * FROM {$table} WHERE {$id_field}={$id} AND {$id_field2}={$id2}");
		$original_record = mysqli_fetch_assoc($result);
		$fields = mysqli_num_fields($result);
	
		$laquery="";
		$laquery_parte1="";
		$laquery_parte2="";
		$laquery .= "INSERT INTO {$table} (";
		$nonprimocampo=false;
		$k=0;
		foreach ($original_record as $key => $value) {
			//$type  = mysql_field_type($result, $k);
			$finfo = mysqli_fetch_field_direct($result, $k);
			$type = $finfo->type;
			//echo "<br>Tipo campo:".$type;
			if ($key != $id_field) {
				//colonna non chiave
				if($nonprimocampo){
					$laquery_parte1 .= ", ";
					$laquery_parte2 .= ", ";					
				}
				$nonprimocampo=true;
				$laquery_parte1 .= '`'.$key.'`';
				$laquery_parte2 .= GetSQLValueString($value,$type);				
			} else {
				//colonna CHIAVE
				if($id_new>0){
					//se specificata voglio settarne il nuovo valore
					if($nonprimocampo){
						$laquery_parte1 .= ", ";
						$laquery_parte2 .= ", ";					
					}
					$nonprimocampo=true;
					$laquery_parte1 .= '`'.$id_field.'`';
					$laquery_parte2 .= GetSQLValueString($id_new,$type);									
				}
			}
			$k++;
		}
		$laquery .= $laquery_parte1 . ") VALUES (" . $laquery_parte2 . ");";
		
		//echo $laquery;
		mysqli_query($std_conn, $laquery) or die(mysqli_error($std_conn));
		
	}else{
		//un solo campo chiave

		// load the original record into an array
		mysqli_select_db($std_conn, $database_std_conn);
		$result = mysqli_query($std_conn, "SELECT * FROM {$table} WHERE {$id_field}={$id}");
		$original_record = mysqli_fetch_assoc($result);
		$fields = mysqli_num_fields($result);
	
		$laquery="";
		$laquery_parte1="";
		$laquery_parte2="";
		$laquery .= "INSERT INTO {$table} (";
		$nonprimocampo=false;
		$k=0;
		foreach ($original_record as $key => $value) {
			//$type  = mysql_field_type($result, $k);
			$finfo = mysqli_fetch_field_direct($result, $k);
			$type = $finfo->type;
			//echo "<br>Tipo campo:".$type;
			if ($key != $id_field) {
				//colonna non chiave
				if($nonprimocampo){
					$laquery_parte1 .= ", ";
					$laquery_parte2 .= ", ";					
				}
				$nonprimocampo=true;
				$laquery_parte1 .= '`'.$key.'`';
				$laquery_parte2 .= GetSQLValueString($value,$type);				
			} 
			$k++;
		}
		$laquery .= $laquery_parte1 . ") VALUES (" . $laquery_parte2 . ");";
		
		//echo $laquery;
		mysqli_query($std_conn, $laquery) or die(mysqli_error($std_conn));
		$newid = mysqli_insert_id($std_conn);
		
		// return the new id
		return $newid;
		
	}//fine if solo un campo chiave
}


function showProvincia($val){
	global $database_std_conn;
	global $std_conn;
	$risultato = "";
	if(is_numeric(trim($val))){
		mysqli_select_db($std_conn, $database_std_conn);
		$query_rs_generic = sprintf("SELECT * FROM dny_provincia WHERE id=%s", GetSQLValueString($val, "int"));
		$rs_generic = mysqli_query($std_conn, $query_rs_generic) or die(mysqli_error($std_conn));
		if($row_rs_generic = mysqli_fetch_assoc($rs_generic)){
			$risultato = $row_rs_generic["nome"];
		}
		mysqli_free_result($rs_generic);
	}else{
		$risultato = $val;
	}
	return $risultato;
}

function showNazione($val){
	global $database_std_conn;
	global $std_conn;
	$risultato = "";
	if(is_numeric(trim($val))){
		mysqli_select_db($std_conn, $database_std_conn);
		$query_rs_generic = sprintf("SELECT * FROM dny_nazione WHERE id=%s", GetSQLValueString($val, "int"));
		$rs_generic = mysqli_query($std_conn, $query_rs_generic) or die(mysqli_error($std_conn));
		if($row_rs_generic = mysqli_fetch_assoc($rs_generic)){
			$risultato = $row_rs_generic["nome"];
		}
		mysqli_free_result($rs_generic);
	}
	return $risultato;
}

?>
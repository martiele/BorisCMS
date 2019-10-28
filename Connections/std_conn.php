<?php


//LOCALE
$hostname_std_conn = "localhost";
$database_std_conn = "boris";
$username_std_conn = "root";
$password_std_conn = "root";


//REMOTO
/*
$hostname_std_conn = "****";
$database_std_conn = "****";
$username_std_conn = "****";
$password_std_conn = "****";
*/

$std_conn = mysqli_connect('p:'.$hostname_std_conn, $username_std_conn, $password_std_conn, $database_std_conn) or trigger_error(mysqli_error($std_conn),E_USER_ERROR);

$std_conn->set_charset("utf8");



if (!isset($_SESSION)) {
  session_start();
}

$folder_public = "public/";
$folder_lavori = "lavori/";
$folder_icone = "icone/";

$iframe_width = "1010";
$iframe_height = "600";
$iframe_auguri_width = "970";
$iframe_auguri_height = "600";


// Variabili globali di configurazione
if (!function_exists("caricaVariabiliSessione")) {
	function caricaVariabiliSessione(){
		global $database_std_conn;
		global $std_conn;
		mysqli_select_db($std_conn, $database_std_conn);
		$query_rs = "SELECT * FROM dny_site_setting WHERE deleted=0";
		$rs = mysqli_query($std_conn, $query_rs) or die(mysqli_error($std_conn));
		$row_rs = mysqli_fetch_assoc($rs);
		$totalRows_rs = mysqli_num_rows($rs);
		$fields = mysqli_num_fields($rs);
		if($totalRows_rs>1){
			do{
				$_SESSION[$row_rs["nome"]]=$row_rs["valore"];
			}while($row_rs = mysqli_fetch_assoc($rs));
		}
		$_SESSION["login"]=true;
		mysqli_free_result($rs);	
	}
}


if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  global $std_conn;
  $theValue = mysqli_real_escape_string($std_conn, $theValue);

  switch ($theType) {
    case "text":
    case "blob":
	case "string":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "real":
    case "double":
	  $theValue = str_replace(",",".",$theValue);
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "datetime":
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

caricaVariabiliSessione();

?>
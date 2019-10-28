<!--

Esempio di chiamata

var menuId = $( "ul.nav" ).first().attr( "id" );
var request = $.ajax({
  url: "script.php",
  method: "POST",
  data: { id : menuId },
  dataType: "html"
});

request.done(function( msg ) {
  $( "#log" ).html( msg );
});

request.fail(function( jqXHR, textStatus ) {
  alert( "Request failed: " + textStatus );
});

-->
<?php require_once('../Connections/std_conn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
  global $std_conn;
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($std_conn, $theValue) : mysqli_escape_string($std_conn, $theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
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

if( isset($_POST) && isset($_GET["nomecliente"]) && ($_POST["nomecliente"]!="") ){
	$nomeCliente = $_POST["nomecliente"];

	//Devo aggiungerlo in lista con una $query


	//A voler fare le cose per bene dovrei anche prima controllare che questo nome cliente non esista già.



}

$tabella = "dny_clienti2";

$sel = $_GET["sel"]>0 ? $_GET["sel"] : 0;
$subsel = $_GET["subsel"]>0 ? $_GET["subsel"] : 0;

mysqli_select_db($std_conn, $database_std_conn);
$query_rs = sprintf("SELECT * FROM %s WHERE deleted = 0 ORDER BY ordinamento ASC", $tabella);
$rs = mysqli_query($std_conn, $query_rs) or die(mysqli_error($std_conn));
$row_rs = mysqli_fetch_assoc($rs);
$totalRows_rs = mysqli_num_rows($rs);
if($totalRows_rs==1){
	//Se c'è una sola lingua la preseleziono automaticamente
	printf('<input type="hidden" name="id_lingua" id="id_lingua" value="%s" />', $row_rs['id']);
}else{
?>
    <label>Lingua</label>
    <select name="id_lingua" id="id_lingua" class="small-input" onchange="linee_ajax_list_update();">
      <?php
 	if($totalRows_rs>0){
		do {
    ?>
        <option value="<?php echo $row_rs['id']?>" <?php if (!(strcmp($sel, $row_rs['id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs['nome']?></option>
      <?php
    	} while ($row_rs = mysqli_fetch_assoc($rs));
	}else{
		//Se sono qui � perch� non � stata inserita nessuna Lingua.
		echo '<option value="0">Aggiungere un valore --> </option>';
	}
    ?>
    </select>

<?php
}
mysqli_free_result($rs);
?>

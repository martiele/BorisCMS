<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="clienti";
$tabella = "dny_utente_newsletter";

$colname_Recordset1 = "-1";
if (isset($_GET['id'])) {
  $colname_Recordset1 = $_GET['id'];
}
mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset1 = sprintf("SELECT * FROM %s WHERE id = %s", 
					$tabella,
					GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysqli_query($std_conn, $query_Recordset1) or die(mysqli_error($std_conn));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
if($totalRows_Recordset1==0)
	die();


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Dettagli Utente Area Riservata</title>

		<?php require_once("header.php"); ?>

</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

			
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Dettagli Utente Area Riservata</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Informazioni Cliente</a></li>
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
                    
								
							  <p>
                              	<strong>Nome:</strong> <?php echo $row_Recordset1["nome"]; ?><br />
							<strong>E-mail:</strong> <?php echo $row_Recordset1["email"]; ?><br />
								<strong>Azienda:</strong> <?php echo $row_Recordset1["azienda"]; ?>
                              </p>

<?php
//Cerco il numero di accessi totali di questo utente
mysqli_select_db($std_conn, $database_std_conn);
$query_nextOrdinamento = sprintf("SELECT COUNT(*) accessi FROM dny_utente_newsletter_stat WHERE id_cliente=%s",
	GetSQLValueString($colname_Recordset1, "int"));
$nextOrdinamento = mysqli_query($std_conn, $query_nextOrdinamento) or die(mysqli_error($std_conn));
$row_nextOrdinamento = mysqli_fetch_assoc($nextOrdinamento);
$totalRows_nextOrdinamento = mysqli_num_rows($nextOrdinamento);
if($totalRows_nextOrdinamento>0){
	$naccessi = $row_nextOrdinamento["accessi"];
}else{
	$naccessi = 0;
}
mysqli_free_result($nextOrdinamento);
?>
                              <p>
								<strong>N&deg; Accessi:</strong> <?php echo $naccessi; ?><br />
                              </p>

<?php
//Cerco l'ultimo accesso di questo utente
mysqli_select_db($std_conn, $database_std_conn);
$query_nextOrdinamento = sprintf("SELECT * FROM dny_utente_newsletter_stat WHERE id_cliente=%s ORDER BY id DESC",
	GetSQLValueString($colname_Recordset1, "int"));
$nextOrdinamento = mysqli_query($std_conn, $query_nextOrdinamento) or die(mysqli_error($std_conn));
$row_nextOrdinamento = mysqli_fetch_assoc($nextOrdinamento);
$totalRows_nextOrdinamento = mysqli_num_rows($nextOrdinamento);
if($totalRows_nextOrdinamento>0){
	echo "<p>";
	$k=1;
	do{
		echo "<strong>".$k.")</strong> ".$row_nextOrdinamento["nome"]."<br />";
		$k++;
	}while($row_nextOrdinamento = mysqli_fetch_assoc($nextOrdinamento));
	echo "</p>";
}else{
	echo "Nessun accesso";
}
mysqli_free_result($nextOrdinamento);
?>


							  <p><input class="button" type="button" value="Chiudi" onclick="javascript:location.href='clienti_stats.php?<?php echo $_SERVER['QUERY_STRING']; ?>';" /></p>
                                
						
					</div> <!-- End #tab1 -->
					
        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			

			
            <?php require_once("footer.php"); ?>

			
		</div> <!-- End #insert-content -->
		
	</div>
    </body>
    
</html>
<?php
mysqli_free_result($Recordset1);
?>

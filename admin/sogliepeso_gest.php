<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="soglie";
$tabella = "dny_soglia_spedizione";
$sufx_sezione_padre="gruppi";
$tabella_padre = "dny_gruppo_spedizione";
$label_id_padre = "id_gruppo_spedizione";
$padre_get_post = "gruppo";
$id_padre=0;
if( isset($_POST[$padre_get_post]) && ($_POST[$padre_get_post]>0) ){
	$id_padre = $_POST[$padre_get_post];
}else if( isset($_GET[$padre_get_post]) && ($_GET[$padre_get_post]>0) ){
	$id_padre = $_GET[$padre_get_post];
}
if($id_padre<=0) die();

// ELIMINO
if( (isset($_GET["elimina"])) && ($_GET["elimina"]=="1") ){
	if( (isset($_GET["id"])) && ($_GET["id"]>0) ){
		$deleteSQL = sprintf("UPDATE %s SET deleted=1 WHERE id=%s LIMIT 1",
		   $tabella,
		   GetSQLValueString($_GET["id"], "int"));
		mysqli_select_db($std_conn, $database_std_conn);
		mysqli_query($std_conn, $deleteSQL) or die(mysqli_error($std_conn));
	}
}


mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset1 = sprintf("SELECT * FROM %s WHERE deleted=0 AND %s=%s ORDER BY fino_a ASC", 
					$tabella,
					$label_id_padre,
					GetSQLValueString($id_padre,"int"));
$Recordset1 = mysqli_query($std_conn, $query_Recordset1) or die(mysqli_error($std_conn));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);


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
		<title><?php echo $_SESSION["www_title"]; ?> - Elenco Soglie Prezzo/Peso</title>
		<?php require_once("header.php"); ?>
	</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->

		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="main-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

			<?php 
				require_once("breadcrump2.php");
				//passo l'id linea
				breadcrump($sufx_sezione,"gest",$id_padre);
			?>
            
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Elenco Soglie Prezzo/Peso</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Elenco Soglie Prezzo/Peso</a></li>
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
						<!--
						<div class="notification attention png_bg">
							<a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								This is a Content Box. You can put whatever you want in it. By the way, you can close this notification with the top-right cross.
							</div>
						</div>
						-->
						<div class="notification attention png_bg">
							<a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								Tramite la tabella sottostante puoi inserire / modificare / eliminare l'elenco delle soglie prezzo/peso.</div>
						</div>

						<table>
							
							<thead>
								<tr>
	                                <th>Soglia Peso</th>
	                                <th>Prezzo</th>
	                                <th>&nbsp;</th>
								</tr>
								
							</thead>
						 
							<tfoot>
								<tr>
									<td colspan="3">
										<div class="bulk-actions align-left">
	<a href="<?php echo $sufx_sezione; ?>_add.php?<?=$padre_get_post?>=<?=$id_padre?>">Aggiungi nuovo</a>
	                               	    <!--
										<select name="dropdown">
												<option value="option1">Choose an action...</option>
												<option value="option2">Edit</option>
												<option value="option3">Delete</option>
											</select>
											<a class="button" href="#">Apply to selected</a>
                                        -->
										</div>
                                     
                                      <!-- End .pagination -->
                                    <div class="clear"></div>
                                  </td>
								</tr>
							</tfoot>
						 
							<tbody>
<?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty 
?>
    <?php do { ?>
  <tr>
    <td><?php echo number_format($row_Recordset1['fino_a'],2,",",""); ?></td>
    <td><?php echo number_format($row_Recordset1['costo'],2,",",""); ?> &euro;</td>
    <td>
      <!-- Icons -->
      <a href="<?php echo $sufx_sezione; ?>_edit.php?id=<?php echo $row_Recordset1['id']; ?>&<?=$padre_get_post?>=<?=$id_padre?>" title="Edit"><img src="resources/images/icons/pencil.png" alt="Edit" /></a>
      <a href="<?php echo $sufx_sezione; ?>_gest.php?elimina=1&id=<?php echo $row_Recordset1['id']; ?>&<?=$padre_get_post?>=<?=$id_padre?>" title="Delete" onclick="return confirm('Eliminare il record?');"><img src="resources/images/icons/cross.png" alt="Delete" /></a> 
    </td>
  </tr>
  <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_Recordset1 == 0) { // Show if recordset empty ?>
  <tr>
    <td colspan="2">Nessun record in elenco</td>
    <td>&nbsp;</td>
  </tr>
  <?php } // Show if recordset empty ?>
                            </tbody>
							
						</table>
                       
						
					</div> <!-- End #tab1 -->
					
        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			

			
            <?php require_once("footer.php"); ?>

			
		</div> <!-- End #main-content -->
		
	</div></body>
  
</html>
<?php
mysqli_free_result($Recordset1);
?>

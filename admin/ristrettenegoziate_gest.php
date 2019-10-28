<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="ristrettenegoziate";
$tabella = "dny_news";

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

// ARCHIVIA
if( (isset($_GET["archive"])) && ($_GET["archive"]=="1") ){
	if( (isset($_GET["id"])) && ($_GET["id"]>0) ){
		$deleteSQL = sprintf("UPDATE %s SET archived=1 WHERE id=%s LIMIT 1",
		   $tabella,
		   GetSQLValueString($_GET["id"], "int"));
		mysqli_select_db($std_conn, $database_std_conn);
		mysqli_query($std_conn, $deleteSQL) or die(mysqli_error($std_conn));
	}
}

// MODIFICA ORDINAMENTO
if( (isset($_POST["riordina"])) && ($_POST["riordina"]=="1") ){
	mysqli_select_db($std_conn, $database_std_conn);
	$query_rs_aggiorna_ordine = sprintf("SELECT * FROM %s WHERE is_ristretta_negoziata=1 AND deleted=0 ORDER BY ordinamento DESC", $tabella);
	$rs_aggiorna_ordine = mysqli_query($std_conn, $query_rs_aggiorna_ordine) or die(mysqli_error($std_conn));
	$row_rs_aggiorna_ordine = mysqli_fetch_assoc($rs_aggiorna_ordine);
	$totalRows_rs_aggiorna_ordine = mysqli_num_rows($rs_aggiorna_ordine);
	if($totalRows_rs_aggiorna_ordine>0){
		do{
			if(isset($_POST["ordinamento".$row_rs_aggiorna_ordine["id"]])){
				$valore = str_replace(",",".",$_POST["ordinamento".$row_rs_aggiorna_ordine["id"]]);
				$updateSQL = sprintf("UPDATE %s SET ordinamento=%s WHERE id=%s LIMIT 1", $tabella,
				   GetSQLValueString($valore, "double"),
				   GetSQLValueString($row_rs_aggiorna_ordine["id"], "int"));
				mysqli_select_db($std_conn, $database_std_conn);
				mysqli_query($std_conn, $updateSQL) or die(mysqli_error($std_conn));
			}
		}while($row_rs_aggiorna_ordine = mysqli_fetch_assoc($rs_aggiorna_ordine));
	}
	mysqli_free_result($rs_aggiorna_ordine);
}

$maxRows_Recordset1 = 40;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

$ordinaper = ""; 
if((isset($_GET['orderby']))&&($_GET['orderby']!="")) {
  $ordinaper = "A.".$_GET['orderby']." DESC, ";
}

mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset1 = sprintf("SELECT A.*, B.nome FROM (%s as A LEFT JOIN %s as B ON A.id = B.id_ref) WHERE A.is_ristretta_negoziata=1 AND A.deleted=0 AND (B.id_lingua=%s OR B.id_lingua is Null) ORDER BY %s A.ordinamento DESC", 
					$tabella,
					$tabella."_lingua",
					GetSQLValueString($_SESSION["linguadefault"],"int"),
					$ordinaper);
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysqli_query($std_conn, $query_limit_Recordset1) or die(mysqli_error($std_conn));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysqli_query($std_conn, $query_Recordset1);
  $totalRows_Recordset1 = mysqli_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$currentPage = $_SERVER["PHP_SELF"];

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false &&
        stristr($param, "elimina") == false &&
        stristr($param, "id") == false) 
	{
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $_SESSION["www_title"]; ?> - Articoli Area Riservata</title>
		<?php require_once("header.php"); ?>
	</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->

		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="main-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

			
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Elenco Articoli</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Elenco</a></li>
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
								Tramite la tabella sottostante puoi inserire / modificare / eliminare l'elenco delle news.</div>
						</div>


					<form name="ordinamento" id="ordinamento" action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="riordina" value="1" />
                        <input type="hidden" name="padre" value="<?=$padre?>" />
                                              
						<table>
							
							<thead>
								<tr>
                                	<th><input type="submit" value="Salva" /></th>
	                                <th><a href="<?php echo $sufx_sezione; ?>_gest.php?orderby=progressivo">Progr.</a></th>
	                                <th>Titolo</th>
	                                <th>Categoria</th>
	                                <th>Inserito</th>
	                                <th>Scaduto</th>
	                                <th>Inviato</th>
	                                <th>Archiv.</th>
	                                <th width="60">&nbsp;</th>
								</tr>
								
							</thead>
						 
							<tfoot>
								<tr>
									<td colspan="9">
										<div class="bulk-actions align-left">
	<a href="<?php echo $sufx_sezione; ?>_add.php">Aggiungi nuovo</a>
	                               	    <!--
										<select name="dropdown">
												<option value="option1">Choose an action...</option>
												<option value="option2">Edit</option>
												<option value="option3">Delete</option>
											</select>
											<a class="button" href="#">Apply to selected</a>
                                        -->
										</div>
                                        
                                        
                                        
                                      <div class="pagination">
                                      	
                                        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>" title="First Page">&laquo; First</a><a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>" title="Previous Page">&laquo; Previous</a>
						<?php for($i=0;$i<$totalPages_Recordset1+1;$i++){ 
							if($i==$pageNum_Recordset1){ ?>
			                    <a href="#" class="number current" title="<?php echo $i+1; ?>"><?php echo $i+1; ?></a>	
						<?php }else{ ?>
        		                <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $i, $queryString_Recordset1); ?>" class="number" title="<?php echo $i+1; ?>"><?php echo $i+1; ?></a>
                        <?php
							}
						} ?>
                            

                                        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>" title="Next Page">Next &raquo;</a><a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>" title="Last Page">Last &raquo;</a>
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
  <tr <?php if (!(strcmp($row_Recordset1['is_attivo'],"0"))) {echo 'class="not_attivo"';} ?>>
    <td><input type="text" name="ordinamento<?php echo $row_Recordset1['id']; ?>" value="<?php echo $row_Recordset1['ordinamento']; ?>" size="5" maxlength="8" style="text-align:center" /></td>
    <td><?php echo $row_Recordset1['progressivo']; ?></td>
    <td><?php echo $row_Recordset1['nome']; ?></td>
    <td><?php
		mysqli_select_db($std_conn, $database_std_conn);
		$query_RecordsetSubCat = sprintf("SELECT A.*, B.nome FROM (dny_sottocategoria as A LEFT JOIN dny_sottocategoria_lingua as B ON A.id = B.id_ref) WHERE A.deleted=0 AND A.id=%s AND (B.id_lingua=%s OR B.id_lingua is Null)", 
			GetSQLValueString($row_Recordset1["id_sottocategoria"],"int"),
			GetSQLValueString($_SESSION["linguadefault"],"int"));
		$RecordsetSubCat = mysqli_query($std_conn, $query_RecordsetSubCat) or die(mysqli_error($std_conn));
		if($row_RecordsetSubCat = mysqli_fetch_assoc($RecordsetSubCat)){

			mysqli_select_db($std_conn, $database_std_conn);
			$query_RecordsetCat = sprintf("SELECT A.*, B.nome FROM (dny_categoria as A LEFT JOIN dny_categoria_lingua as B ON A.id = B.id_ref) WHERE A.deleted=0 AND A.id=%s AND (B.id_lingua=%s OR B.id_lingua is Null)", 
				GetSQLValueString($row_RecordsetSubCat["id_categoria"],"int"),
				GetSQLValueString($_SESSION["linguadefault"],"int"));
			$RecordsetCat = mysqli_query($std_conn, $query_RecordsetCat) or die(mysqli_error($std_conn));
			if($row_RecordsetCat = mysqli_fetch_assoc($RecordsetCat)){
				echo $row_RecordsetCat["nome"] . " - " . $row_RecordsetSubCat["nome"];
			}
			mysqli_free_result($RecordsetCat);

		}
		mysqli_free_result($RecordsetSubCat);
	?></td>
    <td align="center" style="text-align:center;"><?php
		$valore_testuale = data_a_video($row_Recordset1['data_inserimento']);
		if($valore_testuale!=""){
			echo $valore_testuale;
		}else{
			echo "-";
		}
	?></td>
    <td align="center" style="text-align:center;"><?php
		$valore_testuale = data_a_video($row_Recordset1['data_scadenza']);
		if($valore_testuale!=""){
			$date = explode("/",$valore_testuale);
			$data_new = mktime(0,0,0,$date[1],$date[0],$date[2]);
			$data_oggi = mktime(0,0,0,date("m"),date("d"),date("Y")); 
			echo ($data_oggi<=$data_new)?"<strong>No</strong>":"S&igrave;";
		}else{
			echo "-";
		}
	?></td>
    <td align="center" style="text-align:center;"><?php echo ($row_Recordset1['segnalato_newsletter']=="1")?"<strong>S&igrave;</strong>":"No"; ?></td>
    <td align="center" style="text-align:center;"><?php 
		if($row_Recordset1['archived']=="1"){
			echo "S&igrave;";
		}else{ ?>
			<a href="<?php echo $sufx_sezione; ?>_gest.php?archive=1&id=<?php echo $row_Recordset1['id']; ?>" title="Archivia" onclick="return confirm('Spostare il record in Archivio?');"><img src="resources/images/icons/archive.png" width="20" alt="Archivia" /></a>
		<?php }?>
    </td>
    <td>
      <!-- Icons -->
      <a href="<?php echo $sufx_sezione; ?>_notifiche.php?id=<?php echo $row_Recordset1['id']; ?>" title="Interesse / Non Interesse"><img src="resources/images/icons/information.png" alt="Interesse / Non Interesse" /></a>
      <a href="<?php echo $sufx_sezione; ?>_edit.php?id=<?php echo $row_Recordset1['id']; ?>" title="Edit"><img src="resources/images/icons/pencil.png" alt="Edit" /></a>
      <a href="<?php echo $sufx_sezione; ?>_gest.php?elimina=1&id=<?php echo $row_Recordset1['id']; ?>" title="Delete" onclick="return confirm('Eliminare il record?');"><img src="resources/images/icons/cross.png" alt="Delete" /></a> 
    </td>
  </tr>
  <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_Recordset1 == 0) { // Show if recordset empty ?>
  <tr>
    <td>&nbsp;</td>
    <td colspan="7">Nessun record in elenco</td>
    <td>&nbsp;</td>
  </tr>
  <?php } // Show if recordset empty ?>
                            </tbody>
							
						</table>
					</form>
                       
						
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

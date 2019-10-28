<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2";
require_once("restrict.php");

$sufx_sezione="ordininegozi_evadere";
$tabella = "dny_ordine_negozi";

// ELIMINO
if( (isset($_GET["elimina"])) && ($_GET["elimina"]=="1") ){
	if( (isset($_GET["id"])) && ($_GET["id"]>0) ){
		$deleteSQL = sprintf("UPDATE %s SET id_stato_ordine=9 WHERE id=%s LIMIT 1",
		   $tabella,
		   GetSQLValueString($_GET["id"], "int"));
		mysqli_select_db($std_conn, $database_std_conn);
		mysqli_query($std_conn, $deleteSQL) or die(mysqli_error($std_conn));
	}
}


$maxRows_Recordset1 = 20;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

$orderby = "id DESC";
if($_GET["orderby"]!=""){
	if($_GET["orderby"]=="created"){ $orderby="id DESC"; 
	}else if($_GET["orderby"]=="negozio") { $orderby="negozio ASC"; 
	}else if($_GET["orderby"]=="nome") { $orderby="nome ASC"; 
	}else if($_GET["orderby"]=="email") { $orderby="email ASC"; 
	}else if($_GET["orderby"]=="id_stato_ordine") { $orderby="id_stato_ordine ASC"; 
	}
}
mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset1 = sprintf("SELECT * FROM %s WHERE id_stato_ordine>1 AND id_stato_ordine<7 ORDER BY %s", $tabella, $orderby);
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
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $_SESSION["www_title"]; ?> - Ordini Negozi da Evadere</title>
		<?php require_once("header.php"); ?>
	</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="main-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

			
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Elenco Ordini Negozi da Evadere</h3>
					
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
								Tramite la tabella sottostante puoi consultare e gestire gli ordini che non sono ancora stati conclusi definitivamente.</div>
						</div>
						
						<table>
							
							<thead>
								<tr>
                                	<th><a href="<?php echo $sufx_sezione; ?>_gest.php?<?=$padre_get_post?>=<?=$id_padre?>&orderby=created">ID - Data</a></th>
	                                <th><a href="<?php echo $sufx_sezione; ?>_gest.php?<?=$padre_get_post?>=<?=$id_padre?>&orderby=nome">Cliente</a></th>
	                                <th><a href="<?php echo $sufx_sezione; ?>_gest.php?<?=$padre_get_post?>=<?=$id_padre?>&orderby=negozio">Negozio</a></th>
	                                <th><a href="<?php echo $sufx_sezione; ?>_gest.php?<?=$padre_get_post?>=<?=$id_padre?>&orderby=email">Email</a></th>
	                                <th><a href="<?php echo $sufx_sezione; ?>_gest.php?<?=$padre_get_post?>=<?=$id_padre?>&orderby=id_stato_ordine">Stato</a></th>
	                                <th>Totale</th>
	                                <th>&nbsp;</th>
								</tr>
								
							</thead>
						 
							<tfoot>
								<tr>
									<td colspan="7">
                                        
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
  <tr>
    <td><strong><?php echo str_pad($row_Recordset1['id'],5,"0",STR_PAD_LEFT); ?></strong> - <?php echo data_a_video($row_Recordset1['created'],1); ?></td>
    <td><?php echo $row_Recordset1['nome']; ?></td>
    <td><?php echo $row_Recordset1['negozio']; ?></td>
    <td><?php echo $row_Recordset1['email']; ?></td>
    <td class="colore_ordine_stato<?php echo $row_Recordset1['id_stato_ordine']; ?>"><?php 
		switch($row_Recordset1['id_stato_ordine']){
			case 1: echo "Abbandonato"; break;
			case 2: echo "Attesa Pagamento"; break;
			case 3: echo "Richiesta inviata"; break;
			case 4: echo "PAGATO"; break;
			case 5: echo "Preso in carico"; break;
			case 6: echo "Spedito"; break;
			case 7: echo "Concluso"; break;
			case 8: echo "Annullato"; break;
			case 9: echo "Eliminato"; break;
		}
	?></td>
    <td><?php echo number_format($row_Recordset1['totale'],2,",",""); ?> &euro;</td>
    <td>
      <!-- Icons -->
      <a href="<?php echo $sufx_sezione; ?>_edit.php?id=<?php echo $row_Recordset1['id']; ?>" title="Gestisci ordine"><img src="resources/images/icons/pencil.png" alt="Edit" /></a>
      <a href="<?php echo $sufx_sezione; ?>_gest.php?elimina=1&id=<?php echo $row_Recordset1['id']; ?>" title="Delete" onclick="return confirm('Annullare questo ordine?');"><img src="resources/images/icons/cross.png" alt="Delete" /></a> 
    </td>
  </tr>
  <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_Recordset1 == 0) { // Show if recordset empty ?>
  <tr>
    <td>&nbsp;</td>
    <td colspan="5">Nessun record in elenco</td>
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

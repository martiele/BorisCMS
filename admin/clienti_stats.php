<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="clienti";
$tabella = "dny_utente_newsletter";


$maxRows_Recordset1 = 75;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

$orderby = "id DESC";
if($_GET["orderby"]!=""){
	if($_GET["orderby"]=="id"){ $orderby="id DESC"; 
	}else if($_GET["orderby"]=="nome") { $orderby="Nome ASC"; 
	}else if($_GET["orderby"]=="Lingua") { $orderby="Lingua ASC"; 
	}else if($_GET["orderby"]=="cognome") { $orderby="Cognome ASC"; 
	}else if($_GET["orderby"]=="email") { $orderby="Email ASC"; 
	}else if($_GET["orderby"]=="azienda") { $orderby="Azienda ASC"; 
	}else if($_GET["orderby"]=="abilitato") { $orderby="abilitato ASC"; 
	}
}
if( (isset($_GET["key"])) && ($_GET["key"]!="") ){
	$keysearch = str_replace("'","",$_GET["key"]);
	$filtro_cerca = " AND (nome LIKE '%".$keysearch."%' OR azienda LIKE '%".$keysearch."%' OR Email LIKE '%".$keysearch."%') ";
}else{
	$filtro_cerca = "";	
}

mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset1 = sprintf("SELECT * FROM %s WHERE deleted=0 %s ORDER BY %s", $tabella, $filtro_cerca, $orderby);
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysqli_query($std_conn, $query_limit_Recordset1) or die(mysqli_error($std_conn));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);

$all_Recordset1 = mysqli_query($std_conn, $query_Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($all_Recordset1);
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$currentPage = $_SERVER["PHP_SELF"];

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
	if( strcmp(substr($param,0,strpos($param,"=")), "pageNum_Recordset1") &&
		strcmp(substr($param,0,strpos($param,"=")), "totalRows_Recordset1") &&
		strcmp(substr($param,0,strpos($param,"=")), "elimina") &&
		strcmp(substr($param,0,strpos($param,"=")), "id") &&
		strcmp(substr($param,0,strpos($param,"=")), "key") &&
		strcmp(substr($param,0,strpos($param,"=")), "") &&
		strcmp(substr($param,0,strpos($param,"=")), "orderby") )
	{
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$keysearch = ($_GET["key"]!="")?("&key=".$_GET["key"]):"";
$keyorder = ($_GET["orderby"]!="")?("&orderby=".$_GET["orderby"]):"";
$queryString_ricordapagina = sprintf("&totalRows_Recordset1=%d&pageNum_Recordset1=%d%s%s%s", $totalRows_Recordset1, $pageNum_Recordset1, $keysearch, $keyorder, $queryString_Recordset1);
$queryString_but_search = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
$queryString_but_order = sprintf("&totalRows_Recordset1=%d%s%s", $totalRows_Recordset1, $keysearch, $queryString_Recordset1);
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s%s%s", 
$totalRows_Recordset1, $keysearch, $keyorder, $queryString_Recordset1);


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
		<title><?php echo $_SESSION["www_title"]; ?> - Elenco Utenti Area Riservata</title>
		<?php require_once("header.php"); ?>
        
	</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="main-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

			
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Elenco Utenti Area Riservata</h3>
                    
                    <input type="search" id="keysearch" name="key" value="<?php echo $_GET["key"]; ?>" placeholder="Cerca..." data-dest="<?php echo $_SERVER['PHP_SELF']."?".$queryString_but_search; ?>" />
					
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
								Tramite la tabella sottostante puoi consultare i clienti registrati al sito.</div>
						</div>
						
						<table>
							
							<thead>
								<tr>
	                                <th><a href="<?php echo $sufx_sezione; ?>_stats.php?<?php echo $queryString_but_order; ?>&orderby=id">ID</a></th>
	                                <th><a href="<?php echo $sufx_sezione; ?>_stats.php?<?php echo $queryString_but_order; ?>&orderby=nome">Nome</a></th>
	                                <th><a href="<?php echo $sufx_sezione; ?>_stats.php?<?php echo $queryString_but_order; ?>&orderby=email">Email</a></th>
	                                <th><a href="<?php echo $sufx_sezione; ?>_stats.php?<?php echo $queryString_but_order; ?>&orderby=azienda">Azienda</a></th>
	                                <th>N. Accessi</th>
	                                <th style="text-align:center;">Ultimo Accesso</th>
	                                <th>&nbsp;</th>
								</tr>
								
							</thead>
						 
							<tfoot>
								<tr>
									<td colspan="8">
										<div class="bulk-actions align-left">
	<a href="<?php echo $sufx_sezione; ?>_add.php?<?php echo $queryString_ricordapagina; ?>">Aggiungi nuovo</a>
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
    <?php do { 

//Cerco il numero di accessi totali di questo utente
mysqli_select_db($std_conn, $database_std_conn);
$query_nextOrdinamento = sprintf("SELECT COUNT(*) accessi FROM dny_utente_newsletter_stat WHERE id_cliente=%s",
	GetSQLValueString($row_Recordset1["id"], "int"));
$nextOrdinamento = mysqli_query($std_conn, $query_nextOrdinamento) or die(mysqli_error($std_conn));
$row_nextOrdinamento = mysqli_fetch_assoc($nextOrdinamento);
$totalRows_nextOrdinamento = mysqli_num_rows($nextOrdinamento);
if($totalRows_nextOrdinamento>0){
	$naccessi = $row_nextOrdinamento["accessi"];
}else{
	$naccessi = 0;
}
mysqli_free_result($nextOrdinamento);

//Cerco l'ultimo accesso di questo utente
mysqli_select_db($std_conn, $database_std_conn);
$query_nextOrdinamento = sprintf("SELECT * FROM dny_utente_newsletter_stat WHERE id_cliente=%s ORDER BY id DESC LIMIT 1",
	GetSQLValueString($row_Recordset1["id"], "int"));
$nextOrdinamento = mysqli_query($std_conn, $query_nextOrdinamento) or die(mysqli_error($std_conn));
$row_nextOrdinamento = mysqli_fetch_assoc($nextOrdinamento);
$totalRows_nextOrdinamento = mysqli_num_rows($nextOrdinamento);
if($totalRows_nextOrdinamento>0){
	$lastaccess = $row_nextOrdinamento["nome"];
}else{
	$lastaccess = "";
}
mysqli_free_result($nextOrdinamento);
	
	
	?>
  <tr>
    <td><?php echo $row_Recordset1['id']; ?></td>
    <td><?php echo $row_Recordset1['nome']; ?></td>
    <td><?php echo $row_Recordset1['email']; ?></td>
    <td><?php echo $row_Recordset1['azienda']; ?></td>
    <td><?php echo $naccessi; ?></td>
    <td style="text-align:center;" id="img_ab_<?php echo $row_Recordset1['id']; ?>"><?php echo $lastaccess; ?></td>
    <td>
      <!-- Icons -->
      <a href="cliente_stat.php?id=<?php echo $row_Recordset1['id']; ?><?php echo $queryString_ricordapagina; ?>" title="View"><img src="images/magnifier_zoom_in.png" alt="View" /></a>
    </td>
  </tr>
  <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_Recordset1 == 0) { // Show if recordset empty ?>
  <tr>
    <td>&nbsp;</td>
    <td colspan="6">Nessun record in elenco</td>
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

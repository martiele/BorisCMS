<?php 
require_once('../Connections/std_conn.php');
require_once('../funzioni.php');

$MM_authorizedUsers = "1,2,3";
require_once("restrict.php");

$sufx_sezione="ristrettenegoziate";
$tabella = "dny_news";

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


mysqli_select_db($std_conn, $database_std_conn);
$query_rs_lingua = "SELECT * FROM dny_lingua WHERE deleted = 0 ORDER BY ordinamento ASC";
$rs_lingua = mysqli_query($std_conn, $query_rs_lingua) or die(mysqli_error($std_conn));
$row_rs_lingua = mysqli_fetch_assoc($rs_lingua);
$totalRows_rs_lingua = mysqli_num_rows($rs_lingua);
if($totalRows_rs_lingua<=0){
	die();
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $_SESSION["www_title"]; ?> - Interesse / Non Interesse</title>
		<?php require_once("header.php"); ?>
        
        <link rel="stylesheet" href="css/notifiche.css" type="text/css" media="screen" />


</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<?php require_once("sidebar_messages.php"); ?>
		
		<div id="insert-content"> <!-- Main Content Section with everything -->
			
            <?php require_once("top_bar.php"); ?>

                
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Notifiche di Interesse / Non Interesse</h3>
					
					<ul class="content-box-tabs">
						<?php
						do{ 
							$i = $row_rs_lingua["id"];
						?>
						<li><a href="#tab<?=$i?>" <?php echo ($i == $_SESSION["linguadefault"])?'class="default-tab"':''; ?>><?=$row_rs_lingua["nome"]?></a></li>
                        <?php
						}while($row_rs_lingua = mysqli_fetch_assoc($rs_lingua));
						mysqli_data_seek($rs_lingua,0);
						$row_rs_lingua = mysqli_fetch_assoc($rs_lingua);
						?>
               		</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<?php
					do{ 
						$i = $row_rs_lingua["id"];

						mysqli_select_db($std_conn, $database_std_conn);
						$query_rs_info = sprintf("SELECT * FROM %s WHERE id_ref=%s AND id_lingua=%s",
							$tabella."_lingua",
							GetSQLValueString($colname_Recordset1, "int"),
							GetSQLValueString($i, "int"));
						$rs_info = mysqli_query($std_conn, $query_rs_info) or die(mysqli_error($std_conn));
						$row_rs_info = mysqli_fetch_assoc($rs_info);

					?>
					<div class="tab-content <?php echo ($i == $_SESSION["linguadefault"])?'default-tab':''; ?>" id="tab<?=$i?>"> <!-- This is the target div. id must match the href of this div's tab -->
                    
								
							<p>
								<strong>Titolo:</strong> 
								<?=$row_rs_info["nome"]?>
							  </p>		

							  <p>
								<strong>Messaggio:</strong> 
                                <?=$row_rs_info["descrizione"]?>
							  </p>		
                            
	                      
							
							<div class="clear"></div><!-- End .clear -->
												
					</div> <!-- End #tabX -->
					<?php
						mysqli_free_result($rs_info);
					}while($row_rs_lingua = mysqli_fetch_assoc($rs_lingua));
					mysqli_data_seek($rs_lingua,0);
					$row_rs_lingua = mysqli_fetch_assoc($rs_lingua);
					?>		
					
        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
	
    
    		
				
                <div class="divnotifiche">
                	<p>Lista utenti invitati ad esprimere il loro intento di <strong>Interesse</strong> / <strong>Non Interesse</strong></p>
                    <p>Gli utenti verranno invitati ad esprimersi solo dopo aver Inviato le Notifiche dall'<a href="invionews_gest.php" target="_blank">apposita sezione</a> dell'area admin.</p>
                	<div class="interessati colonnanotifiche">
                    	<span class="titoletto">INTERESSATI</span>
<?php
mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset5 = sprintf("SELECT dny_utente_newsletter.* FROM dny_news_letta_utente JOIN dny_utente_newsletter ON dny_news_letta_utente.id_utente_newsletter = dny_utente_newsletter.id WHERE dny_news_letta_utente.id_news=%s AND dny_utente_newsletter.deleted=0 AND dny_news_letta_utente.interesse=1", 
					GetSQLValueString($colname_Recordset1, "int"));
$Recordset5 = mysqli_query($std_conn, $query_Recordset5) or die(mysqli_error($std_conn));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);
if($totalRows_Recordset5>0){
?>
        <ul>
            <?php do{ ?>
            <li><?php echo $row_Recordset5["nome"]; ?><?php if($row_Recordset5["azienda"]!=""){ echo " (".$row_Recordset5["azienda"].")"; } ?></li>
            <?php }while($row_Recordset5 = mysqli_fetch_assoc($Recordset5)); ?>
        </ul>
<?php }else{ ?>
	<br /><br /><br />Nessun nome in elenco
<?php 
} 
mysqli_free_result($Recordset5);
?>
                    </div>
                	<div class="inattesa colonnanotifiche">
                    	<span class="titoletto">IN ATTESA DI CONFERMA</span>
<?php
mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset5 = sprintf("SELECT dny_utente_newsletter.* FROM dny_news_letta_utente JOIN dny_utente_newsletter ON dny_news_letta_utente.id_utente_newsletter = dny_utente_newsletter.id WHERE dny_news_letta_utente.id_news=%s AND dny_utente_newsletter.deleted=0 AND dny_news_letta_utente.interesse=0", 
					GetSQLValueString($colname_Recordset1, "int"));
$Recordset5 = mysqli_query($std_conn, $query_Recordset5) or die(mysqli_error($std_conn));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);
if($totalRows_Recordset5>0){
?>
        <ul>
            <?php do{ ?>
            <li><?php echo $row_Recordset5["nome"]; ?><?php if($row_Recordset5["azienda"]!=""){ echo " (".$row_Recordset5["azienda"].")"; } ?></li>
            <?php }while($row_Recordset5 = mysqli_fetch_assoc($Recordset5)); ?>
        </ul>
<?php }else{ ?>
	<br /><br /><br />Nessun nome in elenco
<?php 
} 
mysqli_free_result($Recordset5);
?>
                    </div>
                	<div class="noninteressati colonnanotifiche">
                    	<span class="titoletto">NON INTERESSATI</span>
<?php
mysqli_select_db($std_conn, $database_std_conn);
$query_Recordset5 = sprintf("SELECT dny_utente_newsletter.*, dny_news_letta_utente.motivo FROM dny_news_letta_utente JOIN dny_utente_newsletter ON dny_news_letta_utente.id_utente_newsletter = dny_utente_newsletter.id WHERE dny_news_letta_utente.id_news=%s AND dny_utente_newsletter.deleted=0 AND dny_news_letta_utente.interesse=-1", 
					GetSQLValueString($colname_Recordset1, "int"));
$Recordset5 = mysqli_query($std_conn, $query_Recordset5) or die(mysqli_error($std_conn));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);
if($totalRows_Recordset5>0){
?>
        <ul>
            <?php do{ ?>
	            <li><?php echo $row_Recordset5["nome"]; ?>
	            	<?php if($row_Recordset5["azienda"]!=""){ echo " (".$row_Recordset5["azienda"].")"; } ?>
	            	<?php if(($row_Recordset5["motivo"]!="non definito")&&($row_Recordset5["motivo"]!="")){ 
	            			echo "<br /><em style='text-decoration:underline; color:#222222;'>".$row_Recordset5["motivo"]."</em>"; 
	            	} ?>	            		
	            </li>
            <?php }while($row_Recordset5 = mysqli_fetch_assoc($Recordset5)); ?>
        </ul>
<?php }else{ ?>
	<br /><br /><br />Nessun nome in elenco
<?php 
} 
mysqli_free_result($Recordset5);
?>
                    </div>
                	<div class="clear_invisibile">&nbsp;</div>
                </div>

            
            <p>
                <input class="button" type="button" value="Chiudi" onclick="javascript:location.href='<?php echo $sufx_sezione; ?>_gest.php';" />
            </p>
            </form>			

	
			
            <?php require_once("footer.php"); ?>

			
		</div> <!-- End #insert-content -->
		
	</div>
   
    </body>
    
</html>
<?php
mysqli_free_result($Recordset1);
mysqli_free_result($rs_lingua);
?>

<?php
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

$myPageURL = curPageName();
?>

<ul id="main-nav">  <!-- Accordion Menu -->
    
	<?php if(
			 (!strcmp("home.php",$myPageURL))
			){ $attivo="current"; }else{ $attivo=""; }
	?>
    <li>
        <a href="home.php" class="nav-top-item no-submenu <?php echo $attivo; ?>">  <!-- Add the class "no-submenu" to menu items with no sub menu -->
            Dashboard
        </a>       
    </li>


    <?php if(
                (!strcmp("modelli_gest.php",$myPageURL))
             || (!strcmp("modelli_edit.php",$myPageURL))
             || (!strcmp("modelli_add.php",$myPageURL))
            ){ $attivo="current"; }else{ $attivo=""; }
        ?>    
        <li>
            <a href="#" class="nav-top-item <?php echo $attivo; ?>">Modelli</a>
            <ul>
                <li><a href="modelli_gest.php" <?php if(!strcmp("modelli_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Modelli</a></li>
                <li><a href="modelli_add.php" <?php if(!strcmp("modelli_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Modello</a></li>
            </ul>
        </li>

    <?php if(
                (!strcmp("files_gest.php",$myPageURL))
             || (!strcmp("files_edit.php",$myPageURL))
             || (!strcmp("files_add.php",$myPageURL))
            ){ $attivo="current"; }else{ $attivo=""; }
        ?>    
        <li>
            <a href="#" class="nav-top-item <?php echo $attivo; ?>">Genera File</a>
            <ul>
                <li><a href="files_gest.php" <?php if(!strcmp("files_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Files Generati</a></li>
                <li><a href="files_add.php" <?php if(!strcmp("files_add.php",$myPageURL)){echo 'class="current"';}?>>Crea Nuovo File</a></li>                
            </ul>
        </li>

        
	
    	<?php if($_SESSION['MM_UserGroup']<=2){ //utenti amministratori o gestori ?>
        <?php if(
                (!strcmp("utenti_gest.php",$myPageURL))
             || (!strcmp("utenti_add.php",$myPageURL))
             || (!strcmp("utenti_edit.php",$myPageURL))
            ){ $attivo="current"; }else{ $attivo=""; }
        ?>    
        <li>
            <a href="#" class="nav-top-item <?php echo $attivo; ?>">Utenti Amministratori</a>
            <ul>
                <li><a href="utenti_gest.php" <?php if(!strcmp("utenti_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Amministratori</a></li>
                <li><a href="utenti_add.php" <?php if(!strcmp("utenti_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Amministratore</a></li>
            </ul>
        </li>
    <?php } // fine sezione dedicata all'amministratore o gestore ?>    

    <?php if($_SESSION['MM_UserGroup']==1){ //utenti amministratori ?>
        <?php if(
                (!strcmp("settings_gest.php",$myPageURL))
             || (!strcmp("settings_add.php",$myPageURL))
             || (!strcmp("settings_edit.php",$myPageURL))
            ){ $attivo="current"; }else{ $attivo=""; }
        ?>    
        <li>
            <a href="#" class="nav-top-item <?php echo $attivo; ?>">Settings</a>
            <ul>
                <li><a href="settings_gest.php" <?php if(!strcmp("settings_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Impostazioni</a></li>
                <li><a href="settings_add.php" <?php if(!strcmp("settings_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Impostazione</a></li>
            </ul>
        </li>
        
            
    <?php } // fine sezione dedicata al cliente ?>    

</ul> <!-- End #main-nav -->
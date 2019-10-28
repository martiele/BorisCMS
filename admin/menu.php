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
            (!strcmp("linee_gest.php",$myPageURL))
         || (!strcmp("linee_add.php",$myPageURL))
         || (!strcmp("linee_edit.php",$myPageURL))
         || (!strcmp("categorie_gest.php",$myPageURL))
         || (!strcmp("categorie_add.php",$myPageURL))
         || (!strcmp("categorie_edit.php",$myPageURL))		 
         || (!strcmp("sottocategorie_gest.php",$myPageURL))
         || (!strcmp("sottocategorie_add.php",$myPageURL))
         || (!strcmp("sottocategorie_edit.php",$myPageURL))
        ){ $attivo="current"; }else{ $attivo=""; }
    ?>    
    <li>
        <a href="#" class="nav-top-item <?php echo $attivo; ?>">Categorie Articoli</a>
        <ul>
            <li><a href="categorie_gest.php?linea=1" <?php if(!strcmp("linee_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Categorie</a></li>
            <li><a href="categorie_add.php?linea=1" <?php if(!strcmp("linee_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Categoria</a></li>
        </ul>
    </li>    
    
	<?php if(
            (!strcmp("utenti_newsletter_gest.php",$myPageURL))
         || (!strcmp("utenti_newsletter_add.php",$myPageURL))
         || (!strcmp("utenti_newsletter_edit.php",$myPageURL))
         || (!strcmp("clienti_stats.php",$myPageURL))
         || (!strcmp("cliente_stat.php",$myPageURL))
        ){ $attivo="current"; }else{ $attivo=""; }
    ?>    
    <li>
        <a href="#" class="nav-top-item <?php echo $attivo; ?>">Utenti Area Riservata</a>
        <ul>
            <li><a href="utenti_newsletter_gest.php" <?php if(!strcmp("utenti_newsletter_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Utenti</a></li>
            <li><a href="utenti_newsletter_add.php" <?php if(!strcmp("utenti_newsletter_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Utenti</a></li>
            <li><a href="clienti_stats.php" <?php if(!strcmp("clienti_stats.php",$myPageURL)){echo 'class="current"';}?>>Statistiche Accesso</a></li>                
        </ul>
    </li>    



	<?php if(
            (!strcmp("news_gest.php",$myPageURL))
         || (!strcmp("news_add.php",$myPageURL))
         || (!strcmp("news_edit.php",$myPageURL))
         || (!strcmp("news_notifiche.php",$myPageURL))
        ){ $attivo="current"; }else{ $attivo=""; }
    ?>    
    <li>
        <a href="#" class="nav-top-item <?php echo $attivo; ?>">Articoli Area Riservata</a>
        <ul>
            <li><a href="news_gest.php" <?php if(!strcmp("news_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Articoli</a></li>
            <li><a href="news_add.php" <?php if(!strcmp("news_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Articolo</a></li>
        </ul>
    </li>

  <?php if(
            (!strcmp("ristrettenegoziate_gest.php",$myPageURL))
         || (!strcmp("ristrettenegoziate_add.php",$myPageURL))
         || (!strcmp("ristrettenegoziate_edit.php",$myPageURL))
         || (!strcmp("ristrettenegoziate_notifiche.php",$myPageURL))         
        ){ $attivo="current"; }else{ $attivo=""; }
    ?>    
    <li>
        <a href="#" class="nav-top-item <?php echo $attivo; ?>">Gare su Invito</a>
        <ul>
            <li><a href="ristrettenegoziate_gest.php" <?php if(!strcmp("ristrettenegoziate_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Gare</a></li>
            <li><a href="ristrettenegoziate_add.php" <?php if(!strcmp("ristrettenegoziate_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Gara</a></li>
        </ul>
    </li>


	<?php if(
            (!strcmp("invionews_gest.php",$myPageURL))
         || (!strcmp("invionews_complete.php",$myPageURL))
        ){ $attivo="current"; }else{ $attivo=""; }
    ?>    
    <li>
        <a href="#" class="nav-top-item <?php echo $attivo; ?>">Invio Notifiche a Utenti</a>
        <ul>
            <li><a href="invionews_gest.php" <?php if(!strcmp("invionews_gest.php",$myPageURL)){echo 'class="current"';}?>>Gestione Notifiche</a></li>
        </ul>
    </li>    

	<?php if(
            (!strcmp("manifinteresse_gest.php",$myPageURL))
         || (!strcmp("manifinteresse_add.php",$myPageURL))
         || (!strcmp("manifinteresse_edit.php",$myPageURL))
        ){ $attivo="current"; }else{ $attivo=""; }
    ?>    
    <li>
        <a href="#" class="nav-top-item <?php echo $attivo; ?>">Manifestazioni Interesse</a>
        <ul>
            <li><a href="manifinteresse_gest.php" <?php if(!strcmp("manifinteresse_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Manif. Interesse</a></li>
            <li><a href="manifinteresse_add.php" <?php if(!strcmp("manifinteresse_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Manif. Interesse</a></li>
        </ul>
    </li>
	<?php if(
            (!strcmp("fornitori_gest.php",$myPageURL))
         || (!strcmp("fornitori_add.php",$myPageURL))
         || (!strcmp("fornitori_edit.php",$myPageURL))
        ){ $attivo="current"; }else{ $attivo=""; }
    ?>    
    <li>
        <a href="#" class="nav-top-item <?php echo $attivo; ?>">Albo Fornitori</a>
        <ul>
            <li><a href="fornitori_gest.php" <?php if(!strcmp("fornitori_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Fornitori</a></li>
            <li><a href="fornitori_add.php" <?php if(!strcmp("fornitori_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Fornitore</a></li>
        </ul>
    </li>
	<?php if(
            (!strcmp("garepartecipate_gest.php",$myPageURL))
         || (!strcmp("garepartecipate_add.php",$myPageURL))
         || (!strcmp("garepartecipate_edit.php",$myPageURL))
        ){ $attivo="current"; }else{ $attivo=""; }
    ?>    
    <li>
        <a href="#" class="nav-top-item <?php echo $attivo; ?>">Gare Partecipate</a>
        <ul>
            <li><a href="garepartecipate_gest.php" <?php if(!strcmp("garepartecipate_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Gare</a></li>
            <li><a href="garepartecipate_add.php" <?php if(!strcmp("garepartecipate_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Gara</a></li>
        </ul>
    </li>


	<?php if(
            (!strcmp("comunicazionigenerali_gest.php",$myPageURL))
         || (!strcmp("comunicazionigenerali_add.php",$myPageURL))
         || (!strcmp("comunicazionigenerali_info.php",$myPageURL))
         || (!strcmp("comunicazionigenerali_edit.php",$myPageURL))
        ){ $attivo="current"; }else{ $attivo=""; }
    ?>    
    <li>
        <a href="#" class="nav-top-item <?php echo $attivo; ?>">Comunicazioni Generali</a>
        <ul>
            <li><a href="comunicazionigenerali_gest.php" <?php if(!strcmp("comunicazionigenerali_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Comunicazioni</a></li>
            <li><a href="comunicazionigenerali_add.php" <?php if(!strcmp("comunicazionigenerali_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Comunicazione</a></li>
        </ul>
    </li>

	<?php if(
            (!strcmp("richiestadocumentazione_gest.php",$myPageURL))
         || (!strcmp("richiestadocumentazione_add.php",$myPageURL))
         || (!strcmp("richiestadocumentazione_info.php",$myPageURL))
         || (!strcmp("richiestadocumentazione_edit.php",$myPageURL))
        ){ $attivo="current"; }else{ $attivo=""; }
    ?>    
    <li>
        <a href="#" class="nav-top-item <?php echo $attivo; ?>">Richieste Documentaz.</a>
        <ul>
            <li><a href="richiestadocumentazione_gest.php" <?php if(!strcmp("richiestadocumentazione_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Richieste</a></li>
            <li><a href="richiestadocumentazione_add.php" <?php if(!strcmp("richiestadocumentazione_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Richiesta</a></li>
        </ul>
    </li>

	<?php if(
            (!strcmp("statistiche_gest.php",$myPageURL))
         || (!strcmp("statistiche_add.php",$myPageURL))
         || (!strcmp("statistiche_edit.php",$myPageURL))
        ){ $attivo="current"; }else{ $attivo=""; }
    ?>    
    <li>
        <a href="#" class="nav-top-item <?php echo $attivo; ?>">Statistiche</a>
        <ul>
            <li><a href="statistiche_gest.php" <?php if(!strcmp("statistiche_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Statistiche</a></li>
            <li><a href="statistiche_add.php" <?php if(!strcmp("statistiche_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Statistiche</a></li>
        </ul>
    </li>
    
    
    <?php if($_SESSION['MM_UserGroup']==1){ //utenti amministratori ?>
    
		<?php if(
                (!strcmp("lingue_gest.php",$myPageURL))
             || (!strcmp("lingue_add.php",$myPageURL))
             || (!strcmp("lingue_edit.php",$myPageURL))
            ){ $attivo="current"; }else{ $attivo=""; }
        ?>    
        <li>
            <a href="#" class="nav-top-item <?php echo $attivo; ?>">Lingue</a>
            <ul>
                <li><a href="lingue_gest.php" <?php if(!strcmp("lingue_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Lingue</a></li>
                <li><a href="lingue_add.php" <?php if(!strcmp("lingue_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Lingua</a></li>
            </ul>
        </li>
    
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
    <?php } // fine sezione dedicata all'amministratore ?>    

</ul> <!-- End #main-nav -->
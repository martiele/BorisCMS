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
	        (!strcmp("linee_gest.php",$myPageURL))
         || (!strcmp("linee_add.php",$myPageURL))
         || (!strcmp("linee_edit.php",$myPageURL))
	     || (!strcmp("categorie_gest.php",$myPageURL))
         || (!strcmp("categorie_add.php",$myPageURL))
         || (!strcmp("categorie_edit.php",$myPageURL))		 
	     || (!strcmp("sottocategorie_gest.php",$myPageURL))
         || (!strcmp("sottocategorie_add.php",$myPageURL))
         || (!strcmp("sottocategorie_edit.php",$myPageURL))
	     || (!strcmp("prodotti_gest.php",$myPageURL))
         || (!strcmp("prodotti_add.php",$myPageURL))
         || (!strcmp("prodotti_edit.php",$myPageURL))
	     || (!strcmp("foto_prodotti_gest.php",$myPageURL))
	     || (!strcmp("foto_prodotti_add.php",$myPageURL))
	     || (!strcmp("foto_prodotti_edit.php",$myPageURL))
	     || (!strcmp("taglie_colori_gest.php",$myPageURL))
	     || (!strcmp("taglie_colori_add.php",$myPageURL))
	     || (!strcmp("taglie_colori_edit.php",$myPageURL))
        ){ $attivo="current"; }else{ $attivo=""; }
	?>    
    <li>
        <a href="#" class="nav-top-item <?php echo $attivo; ?>">Linee, Categorie, Prodotti</a>
        <ul>
            <li><a href="linee_gest.php" <?php if(!strcmp("linee_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Linee</a></li>
            <li><a href="linee_add.php" <?php if(!strcmp("linee_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Linea</a></li>
        </ul>
    </li>


	<?php if(
	     	(!strcmp("lookbook_gest.php",$myPageURL))
         || (!strcmp("lookbook_add.php",$myPageURL))
         || (!strcmp("lookbook_edit.php",$myPageURL))
	     || (!strcmp("foto_lookbook_gest.php",$myPageURL))
	     || (!strcmp("foto_lookbook_add.php",$myPageURL))
	     || (!strcmp("foto_lookbook_edit.php",$myPageURL))
        ){ $attivo="current"; }else{ $attivo=""; }
	?>    
    <li>
        <a href="#" class="nav-top-item <?php echo $attivo; ?>">Lookbook</a>
        <ul>
            <li><a href="lookbook_gest.php" <?php if(!strcmp("lookbook_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco composizioni</a></li>
            <li><a href="lookbook_add.php" <?php if(!strcmp("lookbook_add.php",$myPageURL)){echo 'class="current"';}?>>Crea composizione</a></li>
        </ul>
    </li>



	<?php if(
	        (!strcmp("novita_gest.php",$myPageURL))
         || (!strcmp("homepage_gest.php",$myPageURL))
        ){ $attivo="current"; }else{ $attivo=""; }
	?>    
    <li>
        <a href="#" class="nav-top-item <?php echo $attivo; ?>">Prodotti Novit&agrave; e Home</a>
        <ul>
            <li><a href="novita_gest.php" <?php if(!strcmp("novita_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Prodotti Novit&agrave;</a></li>
            <li><a href="homepage_gest.php" <?php if(!strcmp("homepage_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Prodotti Home Page</a></li>
        </ul>
    </li>    



        <?php if(
                (!strcmp("clienti_gest.php",$myPageURL))
             || (!strcmp("clienti_details.php",$myPageURL))
            ){ $attivo="current"; }else{ $attivo=""; }
        ?>    
        <li>
            <a href="#" class="nav-top-item <?php echo $attivo; ?>">Clienti Registrati</a>
            <ul>
                <li><a href="clienti_gest.php" <?php if(!strcmp("clienti_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Clienti</a></li>
            </ul>
        </li>


        <?php if(
                (!strcmp("ordini_gest.php",$myPageURL))
             || (!strcmp("ordini_evadere_gest.php",$myPageURL))
             || (!strcmp("ordini_edit.php",$myPageURL))
             || (!strcmp("ordini_evadere_edit.php",$myPageURL))
            ){ $attivo="current"; }else{ $attivo=""; }
        ?>    
        <li>
            <a href="#" class="nav-top-item <?php echo $attivo; ?>">Ordini Clienti</a>
            <ul>
                <li><a href="ordini_gest.php" <?php if(!strcmp("ordini_gest.php",$myPageURL)){echo 'class="current"';}?>>Tutti gli Ordini</a></li>
                <li><a href="ordini_evadere_gest.php" <?php if(!strcmp("ordini_evadere_gest.php",$myPageURL)){echo 'class="current"';}?>>Ordini da evadere</a></li>
            </ul>
        </li>


        <?php if(
                (!strcmp("negozianti_gest.php",$myPageURL))
             || (!strcmp("negozianti_add.php",$myPageURL))
             || (!strcmp("negozianti_edit.php",$myPageURL))
            ){ $attivo="current"; }else{ $attivo=""; }
        ?>    
        <li>
            <a href="#" class="nav-top-item <?php echo $attivo; ?>">Area Negozianti</a>
            <ul>
                <li><a href="negozianti_gest.php" <?php if(!strcmp("negozianti_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Negozianti</a></li>
                <li><a href="negozianti_add.php" <?php if(!strcmp("negozianti_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Negoziante</a></li>
            </ul>
        </li>


        <?php if(
                (!strcmp("ordininegozi_gest.php",$myPageURL))
             || (!strcmp("ordininegozi_evadere_gest.php",$myPageURL))
             || (!strcmp("ordininegozi_edit.php",$myPageURL))
             || (!strcmp("ordininegozi_evadere_edit.php",$myPageURL))
            ){ $attivo="current"; }else{ $attivo=""; }
        ?>    
        <li>
            <a href="#" class="nav-top-item <?php echo $attivo; ?>">Ordini Negozi</a>
            <ul>
                <li><a href="ordininegozi_gest.php" <?php if(!strcmp("ordininegozi_gest.php",$myPageURL)){echo 'class="current"';}?>>Tutti gli Ordini</a></li>
                <li><a href="ordininegozi_evadere_gest.php" <?php if(!strcmp("ordininegozi_evadere_gest.php",$myPageURL)){echo 'class="current"';}?>>Ordini da evadere</a></li>
            </ul>
        </li>

    
	        
    <?php if($_SESSION['MM_UserGroup']<=2){ //utenti amministratori o gestori ?>
    
    
		<?php if(
            (!strcmp("gruppi_gest.php",$myPageURL))
         || (!strcmp("gruppi_add.php",$myPageURL))
         || (!strcmp("gruppi_edit.php",$myPageURL))
         || (!strcmp("soglie_gest.php",$myPageURL))
         || (!strcmp("soglie_add.php",$myPageURL))
         || (!strcmp("soglie_edit.php",$myPageURL))
         || (!strcmp("pacco_gest.php",$myPageURL))
         || (!strcmp("pacco_add.php",$myPageURL))
         || (!strcmp("pacco_edit.php",$myPageURL))
         || (!strcmp("nazioni_gest.php",$myPageURL))
         || (!strcmp("nazioni_add.php",$myPageURL))
         || (!strcmp("nazioni_edit.php",$myPageURL))
         || (!strcmp("provincie_gest.php",$myPageURL))
         || (!strcmp("provincie_add.php",$myPageURL))
         || (!strcmp("provincie_edit.php",$myPageURL))
        ){ $attivo="current"; }else{ $attivo=""; }
        ?>    
        <li>
            <a href="#" class="nav-top-item <?php echo $attivo; ?>">Spedizioni</a>
            <ul>
                <li><a href="gruppi_gest.php" <?php if(!strcmp("gruppi_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Gruppi Spedizione</a></li>
                <li><a href="gruppi_add.php" <?php if(!strcmp("gruppi_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Gruppo Spedizione</a></li>
                <li><a href="nazioni_gest.php" <?php if(!strcmp("nazioni_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Paesi</a></li>
                <li><a href="nazioni_add.php" <?php if(!strcmp("nazioni_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Paese</a></li>
                <li><a href="pacco_gest.php" <?php if(!strcmp("pacco_gest.php",$myPageURL)){echo 'class="current"';}?>>Elenco Pacchi Disponibili</a></li>
                <li><a href="pacco_add.php" <?php if(!strcmp("pacco_add.php",$myPageURL)){echo 'class="current"';}?>>Aggiungi Pacco</a></li>
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
    <?php } // fine sezione dedicata all'amministratore ?>    

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
    <?php } // fine sezione dedicata all'amministratore ?>    

</ul> <!-- End #main-nav -->
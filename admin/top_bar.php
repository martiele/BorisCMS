<noscript> <!-- Show a notification if the user has disabled javascript -->
    <div class="notification error png_bg">
        <div>
            Javascript is disabled or is not supported by your browser. Please <a href="http://browsehappy.com/" title="Upgrade to a better browser">upgrade</a> your browser or <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser">enable</a> Javascript to navigate the interface properly.
        </div>
    </div>
</noscript>

<!-- Page Head -->
<h2>Ciao <?php echo $_SESSION['dny_Nome']; ?></h2>
<p id="page-intro">Benvenuto nell'area di generazione files</p>

<ul class="shortcut-buttons-set"> <!-- Replace the icons URL's with your own -->
                
    <li><a class="shortcut-button" href="modelli_gest.php"><span>Modelli</span></a></li>
    
    <li><a class="shortcut-button" href="files_gest.php"><span>Files</span></a></li>

    <?php if($_SESSION['MM_UserGroup']<=2){ //utenti amministratori o gestori ?>    
    <li><a class="shortcut-button" href="utenti_gest.php"><span>Amministratori</span></a></li>
    <?php } ?>

    
</ul><!-- End .shortcut-buttons-set -->

<div class="clear"></div> <!-- End .clear -->
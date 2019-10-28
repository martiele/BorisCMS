<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
    
    <h1 id="sidebar-title"><a href="#"><?php echo $_SESSION["www_title"]; ?></a></h1>
  
    <!-- Logo (221px wide) -->
    <a href="index.php"><img id="logo" src="img/logo_top.png" alt="<?php echo $_SESSION["www_title"]; ?>" /></a>
  
    <!-- Sidebar Profile links -->
    <div id="profile-links">
      <br />
Ciao <a href="#"><?php echo $_SESSION['dny_Nome']; ?></a>
      <!-- , <br>
hai <a href="#messages" rel="modal" title="3 notifiche">3 notifiche</a> non lette. -->
        <br /><br />
        <a href="../index.php" target="_blank" title="Vai al sito">Vai al sito</a> | <a href="logout.php" title="Log Out">Log Out</a>
    </div>        
    
    <?php require_once("menu.php"); ?>

    

    
</div></div> <!-- End #sidebar -->
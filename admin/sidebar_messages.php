<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
    
    <h1 id="sidebar-title"><a href="#"><?php echo $_SESSION["www_title"]; ?></a></h1>
  
    <!-- Logo (221px wide) -->
    <a href="index.php"><img id="logo" src="img/REP-logov2.jpg" alt="<?php echo $_SESSION["www_title"]; ?>" /></a>
  
    <!-- Sidebar Profile links -->
    <div id="profile-links">
      <br />
Ciao <a href="#"><?php echo $_SESSION['dny_Nome']; ?></a>
      <!-- , <br>
hai <a href="#messages" rel="modal" title="3 notifiche">3 notifiche</a> non lette. -->
        <br /><br />
        <a href="logout.php" title="Log Out">Log Out</a>
    </div>        
    
    <?php require_once("menu.php"); ?>

    
    <!-- Logo (221px wide) -->
    <a href="https://aiosa.net/" target="_blank" title="Web Agency Pistoia" style="display:block;text-align: center;"><img id="logo" src="img/logo_top.png" alt="Aiosa Web Agency Pistoia" style="width:50%; margin: 50px auto 0px;" align="center" /></a>

    
</div></div> <!-- End #sidebar -->
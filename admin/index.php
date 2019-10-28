<?php 
require_once('../Connections/std_conn.php'); 
require_once('../funzioni.php'); 

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['mymail'])) {
  $loginUsername=$_POST['mymail'];
  $password=$_POST['pswd'];
  $remember=$_POST["remember"]=="1"?1:0;
  $redirect = 1;
  $errori = doLogin($loginUsername, $password, $remember, $redirect);
}

recuperaInfoDaCoockie();
if(isset($_SESSION['MM_Username'])){
	header("location:home.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		
		<title><?php echo $_SESSION["www_title"]; ?> - Admin Login</title>
		
		<!--                       CSS                       -->
	  
		<!-- Reset Stylesheet -->
		<link rel="stylesheet" href="resources/css/reset.css" type="text/css" media="screen" />
	  
		<!-- Main Stylesheet -->
		<link rel="stylesheet" href="resources/css/style.css" type="text/css" media="screen" />
		
		<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
		<link rel="stylesheet" href="resources/css/invalid.css" type="text/css" media="screen" />	
		
		<!-- Colour Schemes
	  
		Default colour scheme is green. Uncomment prefered stylesheet to use it.
		
		<link rel="stylesheet" href="resources/css/blue.css" type="text/css" media="screen" />
		
		<link rel="stylesheet" href="resources/css/red.css" type="text/css" media="screen" />  
	 
		-->
		
		<!-- Internet Explorer Fixes Stylesheet -->
		
		<!--[if lte IE 7]>
			<link rel="stylesheet" href="resources/css/ie.css" type="text/css" media="screen" />
		<![endif]-->
		
		<!--                       Javascripts                       -->
	  
		<!-- jQuery -->
		<script type="text/javascript" src="resources/scripts/jquery-1.3.2.min.js"></script>
		
		<!-- jQuery Configuration -->
		<script type="text/javascript" src="resources/scripts/simpla.jquery.configuration.js"></script>
		
		<!-- Facebox jQuery Plugin -->
		<script type="text/javascript" src="resources/scripts/facebox.js"></script>
		
		<!-- jQuery WYSIWYG Plugin -->
		<script type="text/javascript" src="resources/scripts/jquery.wysiwyg.js"></script>
	<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
		
		<!-- Internet Explorer .png-fix -->
		
		<!--[if IE 6]>
			<script type="text/javascript" src="resources/scripts/DD_belatedPNG_0.0.7a.js"></script>
			<script type="text/javascript">
				DD_belatedPNG.fix('.png_bg, img, li');
			</script>
		<![endif]-->
		
    <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>
  
	<body id="login">
		
		<div id="login-wrapper" class="png_bg">
			<div id="login-top">
			
				<h1>Roncucci & Partners - Admin</h1>
				<!-- Logo (221px width) -->
				<img id="logo" src="img/REP-logov2.jpg" alt="Roncucci & Partners" />
			</div> <!-- End #logn-top -->
			
			<div id="login-content">
				
				<form id="login_admin" name="login_admin" method="POST" action="<?php echo $loginFormAction; ?>">
				
					<div class="notification information png_bg">
						<div>
							Inserisci email e password per accedere.
						</div>
					</div>
					
					<p>
					    <span id="sprytextfield1">
						<label>E-mail</label>
						<input class="text-input" type="text" name="mymail" />
                        	<span class="textfieldRequiredMsg"><img src="resources/images/icons/cross.png" border="0" /></span>
                        </span>
                    </p>
					<div class="clear"></div>
					<p>
						<span id="sprytextfield2">
						<label>Password</label>
						<input class="text-input" type="password" name="pswd" />
						<span class="textfieldRequiredMsg"><img src="resources/images/icons/cross.png" border="0" /></span></span></p>
				  <div class="clear"></div>
					<p id="remember-password">
						<input type="checkbox" name="remember" value="1" />
						Ricordami
					</p>
					<div class="clear"></div>
					<p>
						<input class="button" type="submit" value="Accedi" />
					</p>
					
				</form>
			</div> <!-- End #login-content -->
			
		</div> <!-- End #login-wrapper -->
	<script type="text/javascript"><!--//
<?php if($errori){ ?>
	alert("E-mail o Password non corretti... prego ritentare.");
<?php } ?>
    var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
    var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
    //-->
    </script>		
</body>
  
</html>
<?php
require_once('../Connections/std_conn.php'); 

// *** Logout the current user.
$logoutGoTo = "index.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);

//cancello le variabili di sessione
global $database_std_conn;
global $std_conn;
mysqli_select_db($std_conn, $database_std_conn);
$query_rs = "SELECT * FROM dny_site_setting";
$rs = mysqli_query($std_conn, $query_rs) or die(mysqli_error($std_conn));
$row_rs = mysqli_fetch_assoc($rs);
$totalRows_rs = mysqli_num_rows($rs);
$fields = mysqli_num_fields($rs);
if($totalRows_rs>1){
	do{
		unset($_SESSION[$row_rs["nome"]]);
	}while($row_rs = mysqli_fetch_assoc($rs));
}
unset($_SESSION["login"]);
mysqli_free_result($rs);	
		

//cancello i coockies
$past = time() - 10; 
 //this makes the time 10 seconds ago 
setcookie("login_myid", "", $past);
setcookie("login_pswd", "", $past);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><?php echo $_SESSION["www_title"]; ?> - Admin Logout</title>
</head>

<body>
</body>
</html>
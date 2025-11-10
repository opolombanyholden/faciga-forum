<?php  if (!isset($_SESSION)) { session_start(); } ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zxx">
<?php 
	//manipulation des donnees de la base de donnees
	include('wcore/cl_sqltransaction.php');
	$sqltransaction = new sqltransaction;
	
	//autre fonction php utile
	require_once('wcore/cl_phpgeneric.php');
	$phpgeneric = new phpgeneric;
	
	
	//manipulation of charlocters
	include('wcore/GetSQLValueString.php');

?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="images/logo/icon-anpi.png" type="image/x-icon">
  <title>FACIGA 2025 : Un Nouveau Partenariat Économique</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!--CSS Plugins-->
  <link rel="stylesheet" href="css/plugin.css">
  <!-- Default CSS-->
  <link rel="stylesheet" href="css/default.css">
  <!--Custom CSS-->
  <link rel="stylesheet" href="css/styles.css">


  <!--FontAwesome CSS-->
  <link rel="stylesheet" href="icons/font-awesome.min.css">


</head>

<body>
  <!--Header Section start-->
 <?php include('include/header.php'); ?>
  <!-- Header section ends -->

  <!-- Bannner section starts -->
  <?php include('include/mainbanner.php'); ?>

  <!--Banner Section end -->

<?php 
    if(isset($_GET['pg']) && !empty($_GET['pg'])){
      include('pages/'.$_GET['pg'].'.php');
    }else{
      include('pages/home.php');
    }
?>





  <!--Footer Section start-->
  <?php include('include/footer.php'); ?>
  <!--Footer Section end-->

  <!--Bacl-to-top Button start-->
  <div id="back-to-top">
    <a href="#" class="bg-pink position-relative align-items-center rounded-circle d-block"></a>
  </div>
  <!--Bacl-to-top Button end-->


  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/custom-nav.js"></script>
  <script src="js/plugin.js"></script>
  <script src="js/main.js"></script>
</body>

</html>
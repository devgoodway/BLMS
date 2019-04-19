
<!DOCTYPE html>
<html>
<head>
<title>BLMS</title>
<!--META-->
<meta charset="utf-8">
<link rel="stylesheet" href="/css/w3_4_1.css">
 <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-dark-grey.css">
<link rel="stylesheet" href="/css/table_ver3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
<!--JAVA SCRIPT-->
<script type="text/javascript" src="js/login.js"></script>
<script type="text/javascript" src="js/search.js"></script>
<script type="text/javascript" src="js/menu.js"></script>
<script type="text/javascript" src="js/open_middle_page.js"></script>
<script src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="https://apis.google.com/js/client.js?onload=onload"></script>
<script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
<!--PHP-->
<?php
require_once("php/db_info.php");
?>
<!-- style -->

<style media="print">
#print-button{
display : none;
}

#result-menu{
display : none;
}
</style>

<!--PHP-->
</head>
<body>
  <table style="width:100%;" id = "print-button" class="w3-card-4 w3-bordered w3-centered w3-hide-small">
    <td><button style="width:100%;" class="w3-button w3-theme-dark w3-hide-small" onclick="window.print()"><strong>이곳을 눌러 인쇄하세요!</strong></button></td>
  </table>
  <?php
    if( empty($_GET['id']) == false ) {
        require("php/".$_GET['id'].".php");
    }
    else {
        require("php/Print_Timetable.php");
    }
  ?>
</body>
</html>

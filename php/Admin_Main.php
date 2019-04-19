<?php
// 권한 관리
require_once 'php/user_chk.php';
 ?>
<body class="w3-light-grey">

<h4><strong>관리자</strong></h4>

<!-- Top container -->
<div class="w3-bar w3-theme-l5">
  <a href="index.php?id=Admin_Main&admin=Admin_Statistics" class="w3-bar-item w3-button w3-mobile">통계</a>
  <a href="index.php?id=Admin_Main&admin=Admin_User" class="w3-bar-item w3-button w3-mobile">사용자</a>
  <a href="index.php?id=Admin_Main&admin=Admin_Season" class="w3-bar-item w3-button w3-mobile">시즌</a>
  <a href="index.php?id=Admin_Main&admin=Admin_Curriculum" class="w3-bar-item w3-button w3-mobile">교과/과목</a>
  <a href="index.php?id=Admin_Main&admin=Admin_Classroom" class="w3-bar-item w3-button w3-mobile">강의실</a>
  <a href="index.php?id=Admin_Main&admin=Admin_Apv" class="w3-bar-item w3-button w3-mobile">학습계획서 관리</a>
  <a href="index.php?id=Admin_Main&admin=Admin_Update_Record" class="w3-bar-item w3-button w3-mobile">수강신청 및 평가</a>
  <a href="index.php?id=Admin_Main&admin=Admin_Atd_Admin" class="w3-bar-item w3-button w3-mobile">출결관리</a>
  <a href="index.php?id=Admin_Main&admin=Admin_Backup" class="w3-bar-item w3-button w3-mobile">백업</a>
</div>

<!-- !PAGE CONTENT! -->
<div class="w3-main">
  <?php
    if( empty($_GET['admin']) == false ) {
        require("php/".$_GET['admin'].".php");
    }
    else {
        require("php/Admin_Statistics.php");
    }
  ?>

  <!-- End page content -->
</div>

<script>
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
    if (mySidebar.style.display === 'block') {
        mySidebar.style.display = 'none';
        overlayBg.style.display = "none";
    } else {
        mySidebar.style.display = 'block';
        overlayBg.style.display = "block";
    }
}

// Close the sidebar with the close button
function w3_close() {
    mySidebar.style.display = "none";
    overlayBg.style.display = "none";
}
</script>

</body>

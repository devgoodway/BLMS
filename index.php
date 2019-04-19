<!DOCTYPE html>
<html>
<head>
<title>별무리고등학교 학습 관리 시스템(BLMS)</title>
<!-- favicon -->
<!DOCTYPE html>
<head>
<link rel="shortcut icon" href="img/favicon11.ico">
<!--META-->
<meta charset="utf-8">
<meta name="google-site-verification" content="<YOUR_VERIFICATION_KEY>" />
<meta name="google-signin-client_id" content="<YOUR_CLIENT_ID>">
<!--CSS-->
<link rel="stylesheet" href="css/w3_4_1.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-indigo.css">
<link rel="stylesheet" href="css/table_ver3.css">
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
//지정된 기본 시즌을 호출한다.
$result_admin_option = mysqli_query($conn, "SELECT * FROM bs_admin WHERE admin_option ='Y'");
while( $row_admin_option = mysqli_fetch_assoc($result_admin_option)){
  $root_season = $row_admin_option[admin_season];
}

//해당 쿼터를 표시하는 쿠키를 생성한다.
if(empty($_GET['season']) == false){
  setcookie('Season',$_GET['season']);
  $season = $_GET['season'];
}elseif(!isset($_COOKIE['Season'])){
  $_GET['season'] = $root_season;
  setcookie('Season',$_GET['season']);
  $season = $root_season;
}else{$season = $_COOKIE['Season'];}
//사용자 정보를 표시하는 변수를 생성한다.
$name = $_COOKIE['Name'];
$email = $_COOKIE['Email'];
$avatar = $_COOKIE['Avatar'];
?>
</head>
<body class="w3-theme-l3">
<!-- Navbar -->
<div class="w3-top">
 <div class="w3-bar w3-theme-d2 w3-left-align w3-large">
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-theme-d2" href="javascript:void(0);" onclick="openNav()"><i class="fa fa-bars"></i></a>
    <a href="index.html" class="w3-bar-item w3-button w3-padding-large w3-theme-d4"><i class="fa fa-home w3-margin-right"></i>별무리고등학교 학습 관리 시스템</a>
<!--안내-->
    <div class="w3-dropdown-hover w3-hide-small">
    <a href="#" class="w3-button w3-padding-large" title="About">안내</a>
    <div class="w3-dropdown-content w3-card-4 w3-bar-block">
      <a class="w3-bar-item w3-button" href="index.php?id=About_Information">안내</a>
      <a class="w3-bar-item w3-button" href="index.php?id=About_Guide">이용방법</a>
    </div>
  </div>
<!--설계-->
    <div class="w3-hide-small w3-dropdown-hover">
      <a href="#" class="w3-padding-large w3-button" title="Design">설계</a>
      <div class="w3-dropdown-content w3-bar-block w3-card-4">
        <a class="w3-bar-item w3-button" href="index.php?id=Design_Plan">학습계획서</a>
        <a class="w3-bar-item w3-button" href="index.php?id=Design_Timetable">수강신청</a>
      </div>
    </div>
  <!--결과-->
    <div class="w3-hide-small w3-dropdown-hover">
      <a href="#" class="w3-padding-large w3-button" title="Result">결과</a>
      <div class="w3-dropdown-content w3-bar-block w3-card-4">
        <a class="w3-bar-item w3-button" href="index.php?id=Result_Plan">학습계획서</a>
        <a class="w3-bar-item w3-button" href="index.php?id=Result_Timetable">시간표</a>
      </div>
    </div>
  <!--시스템-->
    <div class="w3-hide-small w3-dropdown-hover">
      <a href="#" class="w3-padding-large w3-button" title="System">시스템</a>
      <div class="w3-dropdown-content w3-bar-block w3-card-4">
        <a class="w3-bar-item w3-button" href="http://yangjaehun7.cafe24.com/">CAP</a>
        <a class="w3-bar-item w3-button" href="index.php?id=System_Classroom">강의실</a>
        <a class="w3-bar-item w3-button" href="index.php?id=System_yearplan">학사일정</a>
        <a class="w3-bar-item w3-button" href="index.php?id=System_debate">게시판</a>
        <a class="w3-bar-item w3-button" href="php/System_Curriculum.php" target="_blank">개설교과목록</a>
      </div>
    </div>

    <a class="w3-bar-item w3-button w3-hide-small w3-right w3-padding-large w3-hover-white" href="index.php?id=Information_My"><span id="avatar"></span></a>

  <div class="w3-hide-small w3-dropdown-hover w3-right">
    <a href="#" class="w3-padding-large w3-button"><?php echo $season;?></a>
    <div class="w3-dropdown-content w3-bar-block w3-card-4">
      <?php
        $year_chk = 0;
        $result_admin = mysqli_query($conn, "SELECT * FROM bs_admin ORDER BY admin_season DESC");//오름차순 ASC 내림차순 DESC
        while( $row_admin = mysqli_fetch_assoc($result_admin))
        {
          if(substr($row_admin['admin_season'],0,4)!=$year_chk){
            if($year_chk>0){
              echo '</div>';
            }
            echo '<button onclick="myFunction('.substr($row_admin['admin_season'],0,4).')" class="w3-button w3-bar-item">'.substr($row_admin['admin_season'],0,4).'학년도</button><div id="'.substr($row_admin['admin_season'],0,4).'" class="w3-hide w3-bar-block"><a class="w3-bar-item w3-button" href="index.php?season='.$row_admin[admin_season].'">'.$row_admin[admin_season].'</a>';
            $year_chk = substr($row_admin['admin_season'],0,4);
          }else{
            echo '<a class="w3-bar-item w3-button" href="index.php?season='.$row_admin[admin_season].'">'.$row_admin[admin_season].'</a>';
          }
        }
        echo '</div>';
        ?>
    </div>
  </div>
</div>
</div>

<!-- Page Container -->
<div class="w3-container w3-content" style="max-width:1400px;margin-top:80px">
  <!-- The Grid -->
  <div class="w3-row">
    <!-- Left Column -->
    <div class="w3-col m3">
      <!-- Profile -->
      <div class="w3-card-2 w3-round w3-white">
        <div class="w3-container">
         <h4 class="w3-center">My Profile</h4>
         <a href="index.php?id=Result_Timetable&user_name=<?php echo $_COOKIE['Name'];?>"><p  class="w3-center" id="picture"></p></a>
         <hr>
         <p><i class="fa fa-child fa-fw w3-margin-right w3-text-theme"></i><span id="name"></span></p>
         <p><i class="fa fa-envelope fa-fw w3-margin-right w3-text-theme"></i><span id="email"></span></p>
         <p><span class="w3-left w3-circle w3-margin-right w3-text-theme" id="my-signin2"></span>
         <i class="fa fa-lock fa-fw w3-margin-right w3-text-theme"></i><a href="#" title="My Account" onclick="signOut();">Sign out</a></p>
       </div>
      </div>
      <br>
      <!-- 검색 아이콘 --><form action="index.php" method="get"><input name="id" value="Result_Timetable" type="hidden">
      <div class="w3-round w3-card-2">
  <div class="w3-theme-l5 w3-left-align"><i class="fa fa-search fa-fw w3-margin-right" style="width:10%;"></i><input class="w3-theme-l5" style="padding:7px;border:none;width:60%;" name="user_name" type="text" placeholder="Search for timetables ♡"><input class="w3-button w3-theme-l1 w3-ripple" style="padding:7px;border:none;width:20%;float:right;" name="submit" type="submit" value="검색"></div>
</div></form>
      <br>
      <!-- side bar -->

      <?php
      $result_user = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season='".substr($_COOKIE['Season'],0,4)."'");//년도를 가져온다.
      while( $row_user = mysqli_fetch_assoc($result_user))
      {
        if($row_user[user_email]==$_COOKIE['Email'] && isset($_COOKIE['Email'])){
          if($row_user[user_grade]=="교사"){
             echo '<div class="w3-card-2 w3-round w3-theme-l5 w3-border w3-theme-border w3-margin-bottom w3-hide-small">
             <a href="index.php?id=Information_My" style="width:100%;text-align:left;" class="w3-bar-item w3-button"><i class="fa fa-check fa-fw w3-margin-right"></i>나의 정보</a>
              <a href="index.php?id=Information_Adv" style="width:100%;text-align:left;" class="w3-bar-item w3-button"><i class="fa fa-check fa-fw w3-margin-right"></i>학생 정보</a>
              <a href="index.php?id=Information_Mnt" style="width:100%;text-align:left;" class="w3-bar-item w3-button"><i class="fa fa-check fa-fw w3-margin-right"></i>교과 정보</a>
              <a href="index.php?id=System_Atd_Reg" style="width:100%;text-align:left;" class="w3-bar-item w3-button"><i class="fa fa-check fa-fw w3-margin-right"></i>출결 정보</a>
              <a href="index.php?id=Result_Record" style="width:100%;text-align:left;" class="w3-bar-item w3-button"><i class="fa fa-check fa-fw w3-margin-right"></i>생활기록부</a>';
          }else{
            echo '<div class="w3-card-2 w3-round w3-theme-l5 w3-border w3-theme-border w3-margin-bottom w3-hide-small">
            <a href="index.php?id=Information_My" style="width:100%;text-align:left;" class="w3-bar-item w3-button"><i class="fa fa-check fa-fw w3-margin-right"></i>나의 정보</a>';
          }
          if($row_user[user_email]=="<YOUR_MASTER_EMAIL>" || $row_user[user_email]=="<YOUR_MASTER2_EMAIL>"){
            echo '<a href="index.php?id=Admin_Main" style="width:100%;text-align:left;" class="w3-bar-item w3-button"><i class="fa fa-check fa-fw w3-margin-right"></i>관리자</a></div>';
          }else{
            echo '</div>';
          }
        }
      }?>
    <!-- End Left Column -->
    </div>
    <!-- Middle Column -->
    <div class="w3-col m9">
      <div class="w3-row-padding">
        <!-- <div class="w3-col m12"> -->
          <div class="w3-card-2 w3-round w3-white">
            <div class="w3-container w3-padding-small">
              <!--tab을 이용하여 전환 할 페이지-->
              <!-- 안내 안내 페이지 -->
              <div class="w3-container middle_page">
                <?php
                  if( empty($_GET['id']) == false ) {
                      require("php/".$_GET['id'].".php");
                  }
                  else {
                      require("php/About_Information.php");
                  }
                ?>
              </div>
            <!-- </div> -->
          </div>
          <!--모바일 화면에서 아랫쪽 여백 만들기-->
          <br>
        </div>
      </div>
    </div>

    <!-- Right Column -->

  <!-- End Grid -->
  </div>

<!-- End Page Container -->
</div>
<br>

<!-- Footer -->
<footer class="w3-container w3-theme-d3 w3-padding-16">
  <h5>A disciple of Christ who is responsible for the kingdom of GOD - Community ● Discipleship ● Shalom ● Calling</h5>
</footer>

<footer class="w3-container w3-theme-d5">
  <p>Copyright@ <a href="mailto:<YOUR_MASTER_EMAIL>" target="_blank"><YOUR_MASTER_EMAIL><a></p>
</footer>
</body>
</html>

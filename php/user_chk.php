<?php
// 권한 관리(권한이 없으면 메인화면으로 이동)
$admin_key = 0;
$result_user = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season='".substr($_COOKIE['Season'],0,4)."'");//년도를 가져온다.
while( $row_user = mysqli_fetch_assoc($result_user))
{
  if(isset($_COOKIE['Name'])||isset($_COOKIE['Email'])){
  }if($row_user[user_name]==$_COOKIE['Name']){
  }if($row_user[user_email]==$_COOKIE['Email']){
      $admin_key = 1;
}}
if($admin_key==0){echo("<script>alert('사용 권한이 없습니다. 계정을 확인하세요.');location.href='<YOUR_ROOT_URL>index.php';</script>");exit();}?>

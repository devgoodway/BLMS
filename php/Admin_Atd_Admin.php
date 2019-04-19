<?php
// 권한 관리
require_once 'php/user_chk.php';
//출결등록 및 삭제
if($_POST[submit]=='등록'){
  mysqli_query($conn,"INSERT INTO bs_atd_admin (atd_admin_option,atd_admin_contents,atd_admin_created) SELECT '".$_POST[atd_admin_option]."','".$_POST[atd_admin_contents]."',now()");
}elseif ($_POST[submit]=='삭제') {
  mysqli_query($conn,"DELETE FROM bs_atd_admin WHERE atd_admin_id ='".$_POST[atd_admin_id]."'");
}
 ?>
<h4><strong>출결 관리</strong></h4>

<h6><strong>시간 및 사유 등록</strong></h6>
<form action="index.php?id=Admin_Main&admin=Admin_Atd_Admin" method="post">
<table style="width:100%">
  <tr>
    <th>구분</th>
      <td><select style="width:100%" class="w3-input" name="atd_admin_option" required>
        <option>시간</option>
        <option>사유</option>
      </td>
  </tr>
  <tr>
    <th>내용</th><td><input class="w3-input" type="text" name="atd_admin_contents" value="" required></td>
  </tr>
  <tr>
    <td colspan="2"><input class="w3-input" type="submit" name="submit" value="등록"></td>
  </tr>
</table>
</form>

<h6><strong>관리 내용 확인</strong></h6>
<table style="width:100%">
  <tr>
    <th>구분</th><th>내용</th><th>삭제</th>
  </tr>
  <?php
//관리 정보 가져오기
$result_admin = mysqli_query($conn, "SELECT * FROM bs_atd_admin ORDER BY atd_admin_option,atd_admin_id");
while( $row_admin = mysqli_fetch_assoc($result_admin)){
  echo
  '<form action="index.php?id=Admin_Main&admin=Admin_Atd_Admin" method="post">
  <tr>
    <td>'.$row_admin[atd_admin_option].'</td>
    <td>'.$row_admin[atd_admin_contents].'</td>
    <td><input class="w3-input" type="hidden" name="atd_admin_id" value="'.$row_admin[atd_admin_id].'"><input class="w3-input" type="submit" name="submit" value="삭제"></td>
  </tr></form>';
}
   ?>
</table>
</form>

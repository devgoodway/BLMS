<?php
// 권한 관리
require_once 'php/user_chk.php';
//등록&삭제
if($_POST[submit]=='등록'){
  mysqli_query($conn, "INSERT INTO bs_classroom(classroom_name,classroom_created) SELECT '".$_POST[classroom_name]."',now()");
}else if($_POST[submit]=='삭제'){
  mysqli_query($conn, "DELETE FROM bs_classroom WHERE classroom_id = '".$_POST[id]."'");
}
 ?>
<h5><strong>강의실<strong><h5>
  <form class="w3-form" action="index.php?id=Admin_Main&admin=Admin_Classroom" method="post">
    <table class="w3-small w3-card-4 w3-bordered w3-centered" style="width:100%">
      <th>강의실</th><th><input class="w3-input" type="text" name="classroom_name" value=""></th><th><input class="w3-input" type="submit" name="submit" value="등록"></th>
    </table>
  </form>
  <br>
<table class="w3-small w3-card-4 w3-bordered w3-centered" style="width:100%">
  <tr>
    <th>번호</th><th>강의실</th><th>삭제</th>
  </tr>
  <form class="w3-form" action="index.php?id=Admin_Main&admin=Admin_Classroom" method="post">
  <?php
  //시즌 추가&삭제
  $result = mysqli_query($conn, "SELECT * FROM bs_classroom ORDER BY classroom_name");$i=1;
  while($row = mysqli_fetch_assoc($result)){
    echo '<form class="w3-form" action="index.php?id=Admin_Main&admin=Admin_Classroom" method="post">';
    echo '<tr><td>'.$i.'</td><td>'.$row['classroom_name'].'</td><td><input type="hidden" name="id" value="'.$row['classroom_id'].'"><input class="w3-input" type="submit" name="submit" value="삭제"></td></tr>';$i++;
    echo '</form>';
  }?>
</table>

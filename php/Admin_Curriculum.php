<?php
// 권한 관리
require_once 'php/user_chk.php';
//등록&삭제
if($_POST[submit]=='등록'){
  mysqli_query($conn, "INSERT INTO bs_curriculum(curriculum_cur,curriculum_sub,curriculum_apv,curriculum_created) SELECT '".$_POST[cur_cur]."','".$_POST[cur_sub]."','Y',now()");
}elseif($_POST[submit]=='삭제'){
  mysqli_query($conn, "DELETE FROM bs_curriculum WHERE curriculum_id = '".$_POST[cur_id]."'");
}elseif($_POST[submit]=='수정'){
  mysqli_query($conn, "UPDATE bs_curriculum SET curriculum_cur = '".$_POST[cur_cur]."', curriculum_sub = '".$_POST[cur_sub]."', curriculum_apv = '".$_POST[cur_apv]."', curriculum_created = now() WHERE curriculum_id='".$_POST[cur_id]."'");
}
 ?>
<h5><strong>교과/과목<strong><h5>
  <form class="w3-form" action="index.php?id=Admin_Main&admin=Admin_Curriculum" method="post">
    <table class="w3-small w3-card-4 w3-bordered w3-centered" style="width:100%">
      <th>교과</th><th><input class="w3-input" type="text" name="cur_cur" value=""></th><th>과목</th><th><input class="w3-input" type="text" name="cur_sub" value=""></th><th><input class="w3-input" type="submit" name="submit" value="등록"></th>
    </table>
  </form>
  <br>
<table class="w3-small w3-card-4 w3-bordered w3-centered" style="width:100%">
  <tr>
    <th>번호</th><th>교과</th><th>과목</th><th>사용여부</th><th>삭제</th><th>수정</th>
  </tr>
  <?php
  //시즌 추가&삭제
  $result = mysqli_query($conn, "SELECT * FROM bs_curriculum ORDER BY curriculum_cur,curriculum_sub");$i=1;
  while($row = mysqli_fetch_assoc($result)){
    echo '<form class="w3-form" action="index.php?id=Admin_Main&admin=Admin_Curriculum" method="post">';
    echo '<tr><td>'.$i.'</td><td><input class="w3-input" type="text" name="cur_cur" value="'.$row[curriculum_cur].'"></td><td><input class="w3-input" type="text" name="cur_sub" value="'.$row[curriculum_sub].'"></td><td>';
    echo '<select class="w3-input" name="cur_apv">
      <option>'.$row[curriculum_apv].'</option>
      <option>Y</option>
      <option>N</option>
    </select>';
    echo '</td><td><input type="hidden" name="cur_id" value="'.$row[curriculum_id].'"><input class="w3-input" type="submit" name="submit" value="삭제"></td><td><input class="w3-input" type="submit" name="submit" value="수정"></td></tr>';
    echo '</form>';$i++;
  }?>
</table>

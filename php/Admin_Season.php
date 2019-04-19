<?php
// 권한 관리
require_once 'php/user_chk.php';
//등록&삭제
if($_POST[submit]=='등록'){
  mysqli_query($conn, "INSERT INTO bs_admin(admin_season,admin_option,admin_created) SELECT '".$_POST[year].'학년도 '.$_POST[season]."','".$_POST[root_season]."',now()");
}else if($_POST[submit]=='삭제'){
  mysqli_query($conn, "DELETE FROM bs_admin WHERE admin_id = '".$_POST[id]."'");
}
 ?>
<h5><strong>시즌<strong><h5>
  <form class="w3-form" action="index.php?id=Admin_Main&admin=Admin_Season" method="post">
    <table class="w3-small w3-card-4 w3-bordered w3-centered" style="width:100%">
      <th>학년도</th><th><input class="w3-input" type="text" name="year" value=""></th><th>시즌</th><th><input class="w3-input" type="text" name="season" value=""></th><th>현재 시즌</th><td>
        <select class="w3-input" name="root_season">
          <option value="Y">Y</option>
          <option value="N">N</option>
        </select>
      </td><th><input class="w3-input" type="submit" name="submit" value="등록"></th>
    </table>
  </form>
  <br>
<table class="w3-small w3-card-4 w3-bordered w3-centered" style="width:100%">
  <tr>
    <th>번호</th><th>학년도</th><th>시즌</th><th>현재 시즌</th><th>삭제</th>
  </tr>
  <?php
  //시즌 추가&삭제
  $result = mysqli_query($conn, "SELECT * FROM bs_admin ORDER BY admin_season");$i=1;
  while($row = mysqli_fetch_assoc($result)){
    echo '<form class="w3-form" action="index.php?id=Admin_Main&admin=Admin_Season" method="post">';
    echo '<tr><td>'.$i.'</td><td>'.substr($row['admin_season'],0,4).'</td><td>'.substr($row['admin_season'],14).'</td><td>'.$row[admin_option].'</td><td><input type="hidden" name="id" value="'.$row['admin_id'].'"><input class="w3-input" type="submit" name="submit" value="삭제"></td></tr>';$i++;
    echo '</form>';
  }?>
</table>

<?php
// 권한 관리
require_once 'php/user_chk.php';

//수정
$result = mysqli_query($conn, "UPDATE bs_apply SET apply_season = '".$_POST[apply_season]."', apply_grade = '".$_POST[apply_grade]."', apply_sub = '".$_POST[apply_sub]."', apply_point = '".$_POST[apply_point]."', apply_mento_evl = '".addslashes(nl2br($_POST[apply_mento_evl]))."', apply_rating = '".$_POST[apply_rating]."' WHERE apply_id='".$_POST['apply_id']."'");

//사용자 정보 호출
$i = 0;
$result_apply = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_email = '".$_POST[user_email]."' AND NOT apply_rating = ''  AND NOT apply_sub = '' ORDER BY apply_season, apply_grade");
while( $row_apply = mysqli_fetch_assoc($result_apply)){
  $apply_id[$i] = $row_apply[apply_id];
  $apply_season[$i] = $row_apply[apply_season];
  $apply_email[$i] = $row_apply[apply_email];
  $apply_grade[$i] = $row_apply[apply_grade];
  $apply_name[$i] = $row_apply[apply_name];
  $apply_sub[$i] = $row_apply[apply_sub];
  $apply_class[$i] = $row_apply[apply_class];
  $apply_point[$i] = $row_apply[apply_point];
  $apply_time[$i] = $row_apply[apply_point];
  $apply_self_evl[$i] = $row_apply[apply_self_evl];
  $apply_mento_evl[$i] = $row_apply[apply_mento_evl];
  $apply_rating[$i] = $row_apply[apply_rating];
  $i++;
}
?>
<!-- 수정시 팝업을 띄우는 함수 -->
  <script>
function confirmDel() {
        var r = confirm("정말 실행하시겠습니까?");
        if (r == true) {
        } else {
        return false;
        }
}
</script>
<!DOCTYPE html>
<html>
<head>
<style>
@page a4sheet { size: 21.0cm 29.7cm }
.a4 { page: a4sheet; page-break-after: always }

table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

th, td {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 5px;
}

th {
    background-color: #f2f2f2;
}

</style>
</head>
<body>
<h4><strong>교과학습발달상황 수정 - <?php echo $apply_name[0]."/".$apply_email[0]; ?> </strong></h4>
<h5><center><strong><font color="red">수정하기 이전 정보를 저장하고 있지 않습니다. 신중하게 수정하시기 바랍니다!</font></strong></center></h5>
<table class="w3-card-4 w3-bordered w3-centered w3-small">
    <thead>
      <tr>
        <form action="index.php?id=Update_Record_Evl" method="post">
          <select class="w3-select" name="user_email" onchange="form.submit()"><option></option>
            <?php
            $result_users = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".substr($_COOKIE['Season'],0,4)."' AND user_grade LIKE '%학년' ORDER BY user_grade, user_name");
            while($row_users = mysqli_fetch_assoc($result_users)){
              echo '<option value="'.$row_users[user_email].'">'.$row_users[user_grade].'/'.$row_users[user_name].'</option>';
            }?>
          </select>
        </form>
      </tr>
  </thead>
  <tbody>
    <tr>
      	<th>apply_id</th><th>apply_season</th><th>apply_grade</th><th>apply_sub</th><th style="width:5%">apply_point</th><th style="width:40%">apply_mento_evl</th><th>apply_rating</th><th>수정</th>
    </tr>
    <?php
    for($j=0;$j<$i;$j++){
      echo '<tr>';
      echo '<form action="index.php?id=Update_Record_Evl" onsubmit="return confirmDel()" method="post"><input type="hidden" name="apply_id" value="'.$apply_id[$j].'"><input type="hidden" name="user_email" value="'.$apply_email[$j].'">';
      echo '<td>'.$j.'</td>';
      echo '<td><input class="w3-input" type="text" name="apply_season" value="'.$apply_season[$j].'" readonly></td>';
      echo '<td><input class="w3-input" type="text" name="apply_grade" value="'.$apply_grade[$j].'" readonly></td>';
      echo '<td><input class="w3-input" type="text" name="apply_sub" value="'.$apply_sub[$j].'" readonly></td>';
      echo '<td><input class="w3-input" type="text" name="apply_point" value="'.$apply_point[$j].'" readonly></td>';
      echo '<td><textarea class="w3-input" type="text" name="apply_mento_evl">'.str_replace('<br />','',$apply_mento_evl[$j]).'</textarea></td>';
      echo '<td><input  class="w3-input" type="text" name="apply_rating" value="'.$apply_rating[$j].'" readonly></td>';
      echo '<td><input class="w3-input" class="w3-button w3-theme-l3" type="submit" name="수정" value="수정"></td>';
      echo '</form></tr>';
    } ?>
  </tbody>
</table>
<br>
</body>
</html>

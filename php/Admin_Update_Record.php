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
<h4><strong>수강신청 및 평가<?php if($apply_name[0])echo " - ".$apply_name[0]."/".$apply_email[0]; ?> </strong></h4>
<h5><center><strong><font color="red">수정하기 이전 정보를 저장하고 있지 않습니다. 신중하게 수정하시기 바랍니다!</font></strong></center></h5>
<table class="w3-card-4 w3-bordered w3-centered w3-small">
    <thead>
      <tr>
        <form action="index.php?id=Admin_Main&admin=Admin_Update_Record" method="post">
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
      echo '<form action="index.php?id=Admin_Main&admin=Admin_Update_Record" onsubmit="return confirmDel()" method="post"><input type="hidden" name="apply_id" value="'.$apply_id[$j].'"><input type="hidden" name="user_email" value="'.$apply_email[$j].'">';
      echo '<td>'.$j.'</td>';
      echo '<td><select class="w3-select" name="apply_season"><option>'.$apply_season[$j].'</option>';
      $result_admin = mysqli_query($conn, "SELECT * FROM bs_admin ORDER BY admin_season");
      while($row_admin = mysqli_fetch_assoc($result_admin)){
        echo '<option value="'.$row_admin[admin_season].'">'.$row_admin[admin_season].'</option>';}
      echo '</select></td>';
      echo '<td><select class="w3-select" name="apply_grade"><option value="'.$apply_grade[$j].'">'.$apply_grade[$j].'</option>';
      echo '<option value="10학년">10학년</option><option value="11학년">11학년</option><option value="12학년">12학년</option>';
      echo '</select></td>';
      echo '<td><select class="w3-select" name="apply_sub"><option>'.$apply_sub[$j].'</option>';
      $result_curriculum = mysqli_query($conn, "SELECT * FROM bs_curriculum ORDER BY curriculum_cur, curriculum_sub");
      while($row_curriculum = mysqli_fetch_assoc($result_curriculum)){
        echo '<option value="'.$row_curriculum[curriculum_cur].'/'.$row_curriculum[curriculum_sub].'">'.$row_curriculum[curriculum_cur].'/'.$row_curriculum[curriculum_sub].'</option>';}
      echo '</select></td>';
      echo '<td><input class="w3-input" type="number" style="ime-mode:disabled;" name="apply_point" value="'.$apply_point[$j].'"></td>';
      echo '<td><textarea class="w3-input" type="text" name="apply_mento_evl">'.str_replace('<br />','',$apply_mento_evl[$j]).'</textarea></td>';
      echo '<td><select class="w3-select" name="apply_rating"><option>'.$apply_rating[$j].'</option>';
      echo '<option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="F">F</option>';
      echo '</select></td>';
      echo '<td><input class="w3-button w3-theme-l3" type="submit" name="수정" value="수정"></td>';
      echo '</form></tr>';
    } ?>
  </tbody>
</table>
<br>
</body>
</html>

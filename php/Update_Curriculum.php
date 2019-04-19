<?php
// 권한 관리
require_once 'php/user_chk.php';

//교과명 특수문자 에러 체크
$special_pattern = "/[_\/()]/";

if(preg_match($special_pattern,$_POST['plan_class'])){
$msg = "교과명에 _/() 특수문자는 사용할 수 없습니다.";
echo("<script>alert('$msg');history.back();</script>");
exit;
}

//교육과정 호출
$i=0;
$result_curriculum = mysqli_query($conn, "SELECT * FROM bs_curriculum");
while( $row_curriculum = mysqli_fetch_assoc($result_curriculum)){
$data_curriculum[$i] = array($row_curriculum['curriculum_id'], $row_curriculum['curriculum_cur'], $row_curriculum['curriculum_sub'], $row_curriculum['curriculum_text']);$i++;
}

?>

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

</style>

<h4><strong>교과 / 과목 수정 - 2016~2017학년도</strong></h4>
<table class="w3-card-4 w3-bordered w3-centered w3-small">
<thead>
<form action="index.php?id=Update_Curriculum" method="post">
<select style="width:100%" name="mentor_name" onchange="form.submit()">
  <?php
    $result_user = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_grade = '교사' AND (user_season = '2017' OR user_season = '2016') GROUP BY user_name ORDER BY user_name");
    echo '<option>멘토를 선택하세요.</option>';
    while($row_user = mysqli_fetch_assoc($result_user)){
    echo '<option value="'.$row_user['user_name'].'">'.$row_user['user_name'].'['.$row_user['user_email'].']</option>';
    }
    ?>
  </select>
</form>
</thead>
</table><br>
  <table class="w3-card-4 w3-bordered w3-centered w3-small">
    <thead>
      <tr>
        <th style="width:13%" class="">멘토</th>
        <th class="" colspan="6"><?php echo $_POST['mentor_name'];?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th style="width:13%" class="">쿼터</th>
        <th style="width:26%" class="">교과 / 과목</th>
        <th style="width:40%" class="">강좌명</th>
        <th style="width:10%" class="">수정</th>
        <th style="width:10%" class="">결과</th>
      </tr>
        <?php
        $result_plan = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_mentor = '".$_POST['mentor_name']."' AND (plan_season LIKE '2017%' OR plan_season LIKE '2016%') ORDER BY plan_season, plan_class");
        while( $row_plan = mysqli_fetch_assoc($result_plan)){
        echo '<form action="index.php?id=Update_Curriculum" method="post">';
        echo '<input name="planId" value="'.$row_plan[plan_id].'" type="hidden">';
        echo '<tr><td style="width:13%">'.$row_plan[plan_season].'</td>';
        echo '<td style="width:26%"><select name="cur"><option>'.$row_plan[plan_sub].'</option><option></option>';
        $result_cur = mysqli_query($conn, "SELECT * FROM bs_sub GROUP BY sub_sub ORDER BY sub_sub");
        while( $row_cur = mysqli_fetch_assoc($result_cur)){echo '<option>'.$row_cur[sub_sub].'</option>';}
        echo '</select></td>';
        echo '<td style="width:40%">'.$row_plan[plan_class].'</td>';
        echo '<td style="width:10%">수정</td>';
        echo '<td style="width:10%">결과</td></tr></form>';
  }?>
  </tbody></table></form>

<?php
// 권한 관리
require_once 'php/user_chk.php';

//수강신청 목록에서 시간과 이름을 data_apply에 저장한다.
$i=0;
$result_apply_user = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_season='".$_COOKIE[Season]."'");
while( $row_apply_user = mysqli_fetch_assoc($result_apply_user))
{
  $data_apply_name[$i] = $row_apply_user[apply_name];
  $data_apply_time[$i] = $row_apply_user[apply_time];
  $i++;
}

//apply_update_class를 확인하여 apply에 입력한다. 동시에 여러번 입력을 차단하기 위한 중복제거!!
if(empty($_POST['apply_update_class'])===false){
    mysqli_query($conn,"INSERT INTO bs_apply (apply_season,apply_grade,apply_name,apply_email,apply_class,apply_time,apply_created) SELECT '".$_COOKIE[Season]."','".$_POST['user_grade']."','".$_POST['user_name']."','".$_POST['user_email']."','".$_POST['apply_update_class']."','".$_POST['apply_update_time']."',now() FROM DUAL WHERE NOT EXISTS (SELECT * FROM bs_apply WHERE apply_season='".$_COOKIE[Season]."' AND apply_email='".$_POST['user_email']."' AND apply_time='".$_POST['apply_update_time']."' AND apply_class='".$_POST['apply_update_class']."')");}

//시간표를 직접작성하면 name_user를 확인하여 apply에 입력한다. 동시에 여러번 입력을 차단하기 위한 중복제거!!
if($_POST[apv_id]=="신청"){
  $result_plan = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season = '".$_COOKIE[Season]."'");
  while( $row_plan = mysqli_fetch_assoc($result_plan)){
  if($row_plan['plan_id']===$_POST['plan_id']){
    mysqli_query($conn, "INSERT INTO bs_apply (apply_season,apply_grade,apply_name,apply_email,apply_sub,apply_class,apply_point,apply_time,apply_created)
      SELECT '".$_COOKIE[Season]."','".$_POST['user_grade']."','".$_POST['user_name']."','".$_POST['user_email']."','".$row_plan['plan_sub']."','".$row_plan['plan_class']."','".$row_plan['plan_point']."','".$row_plan['plan_time']."',now() FROM DUAL WHERE NOT EXISTS (SELECT * FROM bs_apply WHERE apply_season='".$_COOKIE[Season]."' AND apply_email='".$_POST['user_email']."' AND apply_time='".$row_plan['plan_time']."' AND apply_class='".$row_plan['plan_class']."')");$_POST[apv_id] = NULL;
    }}

}

//사용자 정보를 data_user에 저장
$result_user = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".substr($_COOKIE['Season'],0,4)."'");
while( $row_user = mysqli_fetch_assoc($result_user)){
if($row_user['user_email']===$_POST['user_email']){
  $data_user_grade = $row_user['user_grade'];
}
if($row_user['user_name']===$_POST['mentor_name']){
$data_user = array($row_user['user_id'], $row_user['user_name'], $row_user['user_email'], $row_user['user_adv'], $row_user['user_grade'], $row_user['user_'.substr($_COOKIE['Season'],14,1).'q'], $row_user['user_created']);}}

//선택한 멘토가 개설한 학습계획서 plan_id를 data_plan_apply에 저장
$result_plan_apply = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season = '".$_COOKIE[Season]."'");
$i = $j = 0;
while( $row_plan_apply = mysqli_fetch_assoc($result_plan_apply)){
if($row_plan_apply['plan_mentor']===$_POST['mentor_name']){
$data_plan_apply[$i] = $row_plan_apply['plan_id'];$i++;}
if(strpos($row_plan_apply['plan_time'],$_POST['day'])!==false){
$data_plan_apply[$i] = $row_plan_apply['plan_id'];$i++;
}
//멘토링을 하고있는 멘토의 이름을 저장
$mentoring[$j] = $row_plan_apply['plan_mentor'];
$j++;}
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
<h4><strong>수강 신청 - <?php echo $_COOKIE[Season];?></strong></h4>
<table class="w3-card-4 w3-bordered w3-centered w3-small">
<thead>
<form action="index.php?id=Design_Timetable" method="post">
<input name="user_name" value="<?php echo $_COOKIE[Name];?>" type="hidden"><input name="user_email" value="<?php echo $_COOKIE[Email];?>" type="hidden">
<input class="w3-input w3-third" type="submit" name="memo" value="메모 입력">
<select class="w3-input w3-third" name="mentor_name" onchange="form.submit()">
  <?php
    $result_user_mentor_grade = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".substr($_COOKIE['Season'],0,4)."'");
    echo '<option>멘토를 선택하세요.</option>';
    while( $row_user = mysqli_fetch_assoc($result_user_mentor_grade)){
      //멘토링을 하고있는 명단을 출력
      if(in_array($row_user['user_name'],$mentoring)){
    echo '<option value="'.$row_user['user_name'].'">'.$row_user['user_name'].'['.$row_user['user_email'].']</option>';}
    }
    ?>
  </select>
  <select class="w3-input w3-third" name="day" onchange="form.submit()">
    <?php
    echo '<option>요일과 시간을 선택하세요.</option>';
    for($x = 2; $x <= 12; $x++){
      for($y = 0; $y <= 3; $y++){
        if(($y==0)&&(strpos($apply_class,'월'.$x)===false)){echo '<option value="월'.$x.'">월'.$x.'</option>';}
        elseif(($y==1)&&(strpos($apply_class,'화'.$x)===false)){echo '<option value="화'.$x.'">화'.$x.'</option>';}
        elseif(($y==2)&&(strpos($apply_class,'수'.$x)===false)){echo '<option value="수'.$x.'">수'.$x.'</option>';}
        elseif(($y==3)&&(strpos($apply_class,'목'.$x)===false)&&('목'.$x<>'목7')&&('목'.$x<>'목8')&&('목'.$x<>'목9')&&('목'.$x<>'목10')){echo '<option value="목'.$x.'">목'.$x.'</option>';}}}
      ?>
    </select>
</form>
</thead>
</table><br>
  <table class="w3-card-4 w3-bordered w3-centered w3-small">
    <thead>
      <tr>
        <th class="" colspan="2">멘토</th>
        <th class="" colspan="3"><?php echo $_POST['mentor_name'];?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th style="width:10%" class="">수강신청</th>
        <th style="width:20%" class="">교과/과목</th>
        <th style="width:40%" class="">강좌명</th>
        <th style="width:20%" class="">시간</th>
        <th style="width:10%" class="">학습계획서</th>
      </tr>
        <?php
        $result_apply = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season = '".$_COOKIE[Season]."'");
        while( $row_apply = mysqli_fetch_assoc($result_apply)){
        if($row_apply['plan_apv']=="승인"){
        for($i=0;$i<=count($data_plan_apply);$i++)
        {
          if($data_plan_apply[$i]===$row_apply['plan_id']){
          //수강 신청한 결과와 학습계획표를 비교하여 시간이 겹치는지 확인
          $compare_chk = "";
          for($j=0;$j<count($data_apply_name);$j++)
          {
            if($data_apply_name[$j]==$_POST[user_name]){
          $time_plan = explode('/',$row_apply[plan_time]);
          $time_user = explode('/',$data_apply_time[$j]);
          $compare_time = array_intersect($time_plan,$time_user);
          rsort($compare_time);
          if(empty($compare_time[0])==false){
            $compare_chk = "중복";}
          }}
          echo '<tr>';
          //신청
          if($data_user[5]=="승인"){
          echo '<td style="width:10%;">승인완료</td>';
          }
          elseif(empty($compare_chk)==false){
          echo '<td style="width:10%;">시간중복</td>';
          }
          else{
          echo '<form action="index.php?id=Design_Timetable" method="post"><td style="width:10%;"><input type="hidden" name="apv_id" value="신청"><input name="user_grade" value='.$data_user_grade.' type="hidden"><input name="user_name" value='.$_POST[user_name].' type="hidden"><input name="user_email" value='.$_POST[user_email].' type="hidden"><input name="plan_id" value='.$row_apply[plan_id].' type="hidden"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="신청"></td></form>';
          }
          //강좌명 출력
          echo '<td style="width:15%;">'.$row_apply[plan_sub].'</td>';
          //강좌명 출력
          echo '<td style="width:40%;">'.$row_apply[plan_class].'</td>';
          //시간표 출력
          echo '<td style="width:20%;">';
          //선택된 학습계획서의 plan_time를 배열로 나누어 저장 & 정렬
          $time = explode('/',$row_apply[plan_time]);sort($time);
          for($x=0;$x<=count($time);$x++){
          if(empty($time[$x])===false){
            echo $time[$x];}}
          echo '</td>';
          //학습계획서 출력
          echo '<form action="index.php?id=Result_Plan" method="post" target="_blank"><td style="width:15%;"><input type="hidden" name="planId" value="'.$row_apply[plan_id].'"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="보기"></td></form>';
          echo '</tr>';
        }
      }
    }
  }?>
  </tbody></table><br></form>
  <?php
  if($_POST['memo']=='메모 입력'){
    echo '<h6><strong>메모</strong></h6><table class="w3-table-all w3-card-4 w3-bordered w3-striped w3-centered w3-small"><form action="index.php?id=Design_Timetable" method="post"><input name="user_grade" value='.$data_user_grade.' type="hidden"><input name="user_name" value="'.$_POST['user_name'].'" type="hidden"><input name="user_email" value="'.$_POST['user_email'].'" type="hidden"><thead><tr><th class=""><input class="w3-input" type="text" style="width:100%;" name="apply_update_class" required></th><th class="">';
    echo '<select name="apply_update_time">';
    //중복확인
      for($i=0;$i<count($data_apply_name);$i++){
      if($data_apply_name[$i]==$_POST['user_name']){
        $apply_class .= $data_apply_time[$i];
      }}
    //2교시부터 12교시까지 표시
      for($x = 2; $x <= 12; $x++){
        for($y = 0; $y <= 3; $y++){
          if(($y==0)&&(strpos($apply_class,'월'.$x)===false)){echo '<option value="월'.$x.'">월'.$x.'</option>';}
          elseif(($y==1)&&(strpos($apply_class,'화'.$x)===false)){echo '<option value="화'.$x.'">화'.$x.'</option>';}
          elseif(($y==2)&&(strpos($apply_class,'수'.$x)===false)){echo '<option value="수'.$x.'">수'.$x.'</option>';}
          elseif(($y==3)&&(strpos($apply_class,'목'.$x)===false)&&('목'.$x<>'목7')&&('목'.$x<>'목8')&&('목'.$x<>'목9')&&('목'.$x<>'목10')){echo '<option value="목'.$x.'">목'.$x.'</option>';}}}
          echo '</select></th><th class=""><input class="w3-button w3-theme-l4" type="submit" name="submit" value="제출"></th></th></tr></thead></table></form><br>';}?>

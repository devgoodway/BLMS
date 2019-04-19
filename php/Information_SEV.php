<?php
// 권한 관리
require_once 'php/user_chk.php';
//자기평가 등록
if(empty($_POST['Self_Evaluation_Text'])===false){
  $text = addslashes(nl2br($_POST['Self_Evaluation_Text']));
  $sql = "UPDATE bs_apply SET apply_self_evaluation='".$text."' WHERE apply_id='".$_POST['Self_Evaluation_Id']."'AND apply_season='".$_COOKIE['Season']."'";
  $result_self_evaluation = mysqli_query($conn, $sql);
}

// 학습계획서 삭제
if(empty($_POST['planDel'])===false){
  $result_plan_delete = mysqli_query($conn,"DELETE FROM bs_plan WHERE plan_id='".$_POST['planId']."'");
}

// 수강신청 삭제
if(empty($_POST['applyDel'])===false){
  $result_apply_delete = mysqli_query($conn,"DELETE FROM bs_apply WHERE apply_id='".$_POST['planId']."'");
}

//수강신청 목록에서 이름, 강좌명, 학점, 시간을 data_apply에 저장한다.
$i=0;
$result_apply_user = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_season='".$_COOKIE['Season']."'");
while( $row_apply_user = mysqli_fetch_assoc($result_apply_user))
{
  if($row_apply_user[apply_id]==$_POST[Self_Evaluation_Id]){
  $data_apply_self_evaluation_text = $row_apply_user[apply_self_evl];
  $data_apply_self_evaluation_class = $row_apply_user[apply_class];}
  if($row_apply_user[apply_name]==$_COOKIE['Name']){
  $data_apply_id[$i] = $row_apply_user[apply_id];
  $data_apply_name[$i] = $row_apply_user[apply_name];
  $data_apply_class[$i] = $row_apply_user[apply_class];
  $data_apply_point[$i] = $row_apply_user[apply_point];
  $data_apply_time[$i] = $row_apply_user[apply_time];
  $data_apply_self_evaluation[$i] = $row_apply_user[apply_self_evl];
  $i++;}
}

//사용자가 개설한 학습계획서 plan_id를 data_plan_design에 저장
$result_plan_design = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season='".substr($_COOKIE['Season'],0,4)."'");
$i = 0;
while( $row_plan_design = mysqli_fetch_assoc($result_plan_design)){
//사용자가 개설한 학습계획서
if($row_plan_design['plan_email']===$_COOKIE['Email']){
$data_plan_design[$i] = $row_plan_design['plan_id'];$i++;}

//사용자가 신청한 수업
for($j=0;$j<count($data_apply_class);$j++){
  if($data_apply_class[$j]==$row_plan_design['plan_class']){
    $data_apply_plan_id[$j] = $row_plan_design['plan_id'];
  }}}

//사용자의 승인정보를 얻는다.
$result_user = mysqli_query($conn, "SELECT * FROM bs_user WHERE user_season='".$_COOKIE['Season']."'");
while( $row_user = mysqli_fetch_assoc($result_user))
{
  if($row_user[user_email]==$_COOKIE['Email']){
    $data_user_approved = $row_user[user_approved];
  }
}?>
<h4><strong>사용자 정보(학생)</strong></h4>
<table class="w3-table-all w3-card-4 w3-bordered w3-striped w3-centered w3-small">
  <thead>
    <tr>
      <th class="">이름</th>
      <th class=""><?php echo $_COOKIE['Name'];?></th>
      <th class="">이메일</th>
      <th class=""><?php echo $_COOKIE['Email'];?></th>
      <?php
      echo '<form action="index.php" method="get" target="_blank"><th style="width:10%;"><input name="id" value="Result_Timetable" type="hidden"><input type="submit" name="submit" value="시간표"></th></form></th>';?>
    </tr>
  </thead>
</table>
<h6><strong>자기평가결과</strong></h6>
<table class="w3-table-all w3-card-4 w3-bordered w3-striped w3-small">
  <thead>
  <tbody>
    <tr>
      <th class="w3-centered">강좌명</th>
      <th class="w3-centered" colspan="3">내용</th>
    </tr>
    <?php
    for($i=0;$i<count($data_apply_name);$i++){
      if(empty($data_apply_point[$i])===false){
        //강좌명 출력
        echo '<tr><th style="width:40%" class="">'.$data_apply_class[$i].'</th>';
        //내용 출력
        echo '<th style="width:60%" class=""  colspan="3">'.$data_apply_self_evaluation[$i].'</th>';
        $point = $point + $data_apply_point[$i];
        echo '</tr>';}}
        ?>
  </tbody>
</table><br>

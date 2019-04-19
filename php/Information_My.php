<?php
// 권한 관리
require_once 'php/user_chk.php';
//자기평가 등록
if(empty($_POST['Self_Evaluation_Text'])===false){
  $text = addslashes(nl2br($_POST['Self_Evaluation_Text']));
  $sql = "UPDATE bs_apply SET apply_self_evl='".$text."' WHERE apply_id='".$_POST['Self_Evaluation_Id']."'AND apply_season='".$_COOKIE['Season']."'";
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

//사용자가 개설한 학습계획서 plan_id를 data_plan_design에 저장
$result_plan_design = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season='".$_COOKIE['Season']."'");
$i = 0;$k=0;$l=0;
while( $row_plan_design = mysqli_fetch_assoc($result_plan_design)){
//모든 학습계획서의 이름과 아이디 정보를 얻는다.
$data_plan_class[$k] = $row_plan_design['plan_class'];
$data_plan_id[$k] = $row_plan_design['plan_id'];$k++;

//사용자가 개설한 학습계획서
if($row_plan_design['plan_name']===$_COOKIE['Name']){
$data_plan_design[$i] = $row_plan_design['plan_id'];$i++;}

//사용자가 신청한 수업
for($j=0;$j<count($data_apply_class);$j++){
  if($data_apply_class[$j]==$row_plan_design['plan_class']){
    $data_apply_plan_id[$j] = $row_plan_design['plan_id'];
  }}
}

//수강신청 목록에서 이름, 강좌명, 학점, 시간을 data_apply에 저장한다.
$i=0;
$j=0;
$apply_class[0] = "초기값";//수강취소를 위한 초기값 설정
$result_apply_user = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_season='".$_COOKIE['Season']."'");
while( $row_apply_user = mysqli_fetch_assoc($result_apply_user))
{
  if($row_apply_user[apply_id]==$_POST[Self_Evaluation_Id]){
  $data_apply_self_evaluation_text = $row_apply_user[apply_self_evl];
  $data_apply_self_evaluation_class = $row_apply_user[apply_class];}
  if($row_apply_user[apply_email]==$_COOKIE['Email']){
  $data_apply_id[$i] = $row_apply_user[apply_id];
  $data_apply_name[$i] = $row_apply_user[apply_name];
  $data_apply_class[$i] = $row_apply_user[apply_class];
  $data_apply_point[$i] = $row_apply_user[apply_point];
  $data_apply_time[$i] = $row_apply_user[apply_time];
  $data_apply_self_evaluation[$i] = $row_apply_user[apply_self_evl];
  $data_apply_rating[$i] = $row_apply_user[apply_rating];
  $i++;}
  $apply_class[$j] = $row_apply_user[apply_class];
  $j++;
}

//사용자의 승인정보를 얻는다.
$result_user = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season='".substr($_COOKIE['Season'],0,4)."'");//년도를 가져온다.
while( $row_user = mysqli_fetch_assoc($result_user))
{
  if($row_user[user_name]==$_COOKIE['Name']){
    $data_user_grade = $row_user[user_grade];
    $data_user_approved = $row_user["user_".substr($_COOKIE['Season'],14,1)."q"];//쿼터를 가져온다.
  }
}
?>
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
<h4><strong>나의 정보 - <?php echo $_COOKIE['Season'];?></strong></h4>
<table class="w3-card-4 w3-bordered w3-centered w3-small">
    <thead>
      <tr>
          <th >이름</th>
          <td ><?php echo $_COOKIE['Name'];?></td>
          <th>직위</th>
          <td><?php echo $data_user_grade;?></td>
          <th>이메일</th>
          <td><?php echo $_COOKIE['Email'];?></td>
          <?php
          echo '<form action="index.php" method="get" target="_blank"><td style="width:10%;"><input name="id" value="Result_Timetable" type="hidden"><input type="hidden" name="user_name" value="'.$_COOKIE['Name'].'"><input class="w3-theme-l4 w3-button" type="submit" name="submit" value="시간표"></td></form>';?>
      </tr>
  </thead>
</table>
<h6><strong>자기평가</strong></h6>
  <table class=" w3-card-4 w3-bordered w3-centered w3-small">
  <thead>
    <tr>
      <td class="" colspan="3">
        <form action="index.php?id=Information_My" method = "post">
        <select style="width:100%" name="Self_Evaluation_Id" onchange="form.submit()">
          <option><?php echo $data_apply_self_evaluation_class;?></option>
          <?php
        for($i=0;$i<count($data_apply_name);$i++){
          if(empty($data_apply_point[$i])===false){
            echo '<option value="'.$data_apply_id[$i].'">'.$data_apply_class[$i].'</option>';}}
            ?>
        </select>
      </form>
      </td>
      <td  style="width:10%"><form action="index.php?id=Information_SEV" method="post"><input class="w3-button w3-theme-l4 w3-ripple"align=rigth type="submit" name="result" value="결과"></form></td>
    </tr>
    <tr>
      <td class="" colspan="3">
        <form action="index.php?id=Information_My" method="post">
        <?php
          echo '<input name="Self_Evaluation_Id" value="'.$_POST['Self_Evaluation_Id'].'" type="hidden">';
          ?>
        <textarea name="Self_Evaluation_Text" class="w3-input w3-border"><?php $data_apply_self_evaluation_text = str_replace('<br />','',$data_apply_self_evaluation_text);echo $data_apply_self_evaluation_text;?></textarea></td>
      <td  style="width:10%"><input class="w3-button w3-theme-l4 w3-ripple" align=rigth type="submit" name="ev_submit" value="제출"></td>
      </form>
    </tr>
  </thead>

</table>

<h6><strong>수업개설현황</strong></h6>
<table class="w3-card-4 w3-bordered  w3-centered w3-small">
  <tbody>
    <tr>
      <th>강좌명</th>
      <th>교과</th>
      <th>학습계획서</th>
      <th>수정</th>
      <th>삭제</th>
    </tr>
      <?php
      $result_plan_design = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season='".$_COOKIE['Season']."'");
      while( $row_plan_design = mysqli_fetch_assoc($result_plan_design)){
      for($i=0;$i<=count($data_plan_design);$i++)
      {
        if($data_plan_design[$i]===$row_plan_design['plan_id']){
        echo '<tr>';
        //강좌명 출력
        echo '<td style="width:40%;">'.$row_plan_design[plan_class].'</td>';
        //교과명 출력
        echo '<td style="width:20%;">'.$row_plan_design[plan_sub].'</td>';
        //학습계획서 출력
        echo '<form action="index.php?id=Result_Plan" method="post" target="_blank"><td style="width:10%;"><input type="hidden" name="planId" value="'.$row_plan_design[plan_id].'"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="보기"></td></form>';
        //수정
        if($row_plan_design[plan_apv]=="대기중"){
        echo '<form action="index.php?id=Design_Plan_Update" method="post" target="_blank"><td style="width:10%;"><input type="hidden" name="planId" value='.$row_plan_design[plan_id].'><input class="w3-button w3-theme-l4" type="submit" name="submit" value="수정"></td></form>';}
        else{
        echo '<td style="width:10%;">승인완료</td>';
        }
        //삭제
        if($row_plan_design[plan_apv]=="대기중"){
        echo '<form action="index.php?id=Information_My" method="post"><td style="width:10%;"><input name="user_name" value="'.$_COOKIE['Name'].'" type="hidden"><input name="user_email" value="'.$_COOKIE['Email'].'" type="hidden"><input type="hidden" name="planId" value='.$row_plan_design[plan_id].'><input type="hidden" name="planDel" value="삭제"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="삭제"></td></form>';}
        else{
        echo '<td style="width:10%;">승인완료</td>';
        }

        echo '</tr>';
      }
      }
    }
       ?>
     </tbody>
   </table>
<h6><strong>수강등록현황</strong></h6>
<table class=" w3-card-4 w3-bordered  w3-centered w3-small">
  <tdead>
  <tbody>
    <tr>
      <th class="">강좌명</th>
      <th class="">학점</th>
      <th class="">평가</th>
      <th class="">학습계획서</th>
      <th class="">삭제</th>
    </tr>
    <?php
    $apply_point = 0;
    for($i=0;$i<count($data_apply_name);$i++){
      if(empty($data_apply_point[$i])===false){
        //강좌명 출력
        echo '<tr><td style="width:60%" class="">'.$data_apply_class[$i].'</td>';
        //학점 출력
        echo '<td style="width:10%" class="">'.$data_apply_point[$i].'</td>';
        //평가 출력
        echo '<td style="width:10%" class="">'.$data_apply_rating[$i].'</td>';
        //학습계획서 출력
        for($j=0;$j<count($data_plan_class);$j++){
          if($data_apply_class[$i]==$data_plan_class[$j]){
          echo '<form action="index.php?id=Result_Plan" method="post" target="_blank"><td style="width:10%;"><input type="hidden" name="planId" value="'.$data_plan_id[$j].'"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="보기"></td></form>';}}
        //삭제
        if($data_user_approved!="승인"){
        echo '<form action="index.php?id=Information_My" method="post"><td style="width:10%;"><input name="user_name" value="'.$_COOKIE['Name'].'" type="hidden"><input name="user_email" value="'.$_COOKIE['Email'].'" type="hidden"><input type="hidden" name="planId" value='.$data_apply_id[$i].'><input type="hidden" name="applyDel" value="삭제"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="삭제"></td></form>';}
        else{
        echo '<td style="width:10%;">승인완료</td>';
        }
        $apply_point = $apply_point + $data_apply_point[$i];
        echo '</tr>';}}
        echo '<tr><td style="width:40%" class="">합계</td>';
        //학점 출력
        echo '<td style="width:10%" class="" colspan="3">'.$apply_point.'</td>';
        ?>
  </tbody>
</table>
  <h6><strong>메모</strong></h6>
  <table class=" w3-card-4 w3-bordered  w3-centered w3-small">
    <tbody>
      <tr>
        <th class="">내용</th>
        <th class="">시간</th>
        <th class="">삭제</th>
      </tr>
      <?php
      for($i=0;$i<count($data_apply_name);$i++){
        if(empty($data_apply_point[$i])){
          //내용 출력
          echo '<tr><td style="width:60%" class="">'.$data_apply_class[$i].'</td>';
          //시간 출력
          echo '<td style="width:20%" class="">'.$data_apply_time[$i].'</td>';
          //삭제
          if($data_user_approved!="승인"){
          echo '<form action="index.php?id=Information_My" method="post"><td style="width:10%;"><input name="user_name" value="'.$_COOKIE['Name'].'" type="hidden"><input name="user_email" value="'.$_COOKIE['Email'].'" type="hidden"><input type="hidden" name="planId" value='.$data_apply_id[$i].'><input type="hidden" name="applyDel" value="삭제"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="삭제"></td></form>';}
          else{
          echo '<td style="width:20%;">승인완료</td>';
          }
          echo '</tr>';}}?>
    </tbody>
</table>
<br>
</body>
</html>

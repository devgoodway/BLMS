<?php
// 권한 관리
require_once 'php/user_chk.php';

//선택된 학습계획서 정보를 data_plan에 저장
$result_plan = mysqli_query($conn, "SELECT * FROM bs_plan");
$i=0;
while( $row_plan = mysqli_fetch_assoc($result_plan)){
  $row_plan_id[$i]=$row_plan['plan_id'];
  $row_plan_class[$i]=$row_plan['plan_class'];
  $i++;
if($row_plan['plan_id']===$_POST['planId']){
$data_plan = array($row_plan['plan_id'], $row_plan['plan_name'], $row_plan['plan_email'], $row_plan['plan_mentor'], $row_plan['plan_approved'], $row_plan['plan_sub'], $row_plan['plan_class'], $row_plan['plan_point'], $row_plan['plan_classroom'], $row_plan['plan_contents'], $row_plan['plan_time'], $row_plan['plan_created']);}}

//선택된 학습계획서 정보에 있는 멘토의 정보를 data_mentor에 저장
$result_mentor = mysqli_query($conn, "SELECT * FROM bs_user");
$i=0;
while( $row_mentor = mysqli_fetch_assoc($result_mentor)){
if($row_mentor['user_name']===$data_plan[3]){
$data_mentor = array($row_mentor['user_id'], $row_mentor['user_name'], $row_mentor['user_email'], $row_mentor['user_adv'], $row_mentor['user_grade'], $row_mentor['user_created']);}
$apply_name[$i] = $row_mentor['user_name'];
$apply_grade[$i] = $row_mentor['user_grade'];$i++;}

//선택된 학습계획서 정보에 있는 작성자의 정보를 data_user에 저장
$result_user = mysqli_query($conn, "SELECT * FROM bs_user");
while( $row_user = mysqli_fetch_assoc($result_user)){
if($row_user['user_email']===$data_plan[2]){
$data_user = array($row_user['user_id'], $row_user['user_name'], $row_user['user_email'], $row_user['user_adv'], $row_user['user_grade'], $row_user['user_created']);}}

//선택된 학습계획서의 plan_contents를 배열로 나누어 저장
$contents = explode('%^',$data_plan[9]);
//선택된 학습계획서의 plan_time를 배열로 나누어 저장 & 정렬
$time = explode('/',$data_plan[10]);sort($time);
?>
<h4><strong>학습계획서</strong></h4>
<table class="w3-table-all w3-card-4 w3-bordered w3-striped w3-centered w3-small w3-hide-small">
<thead>
  <tr>
    <th><form action="print.php?id=Print_Plan" method="post">
      <select class="plan-form" name="planId" onchange="form.submit()">
        <?php
        array_multisort($row_plan_class,$row_plan_id);
        echo '<option value="">아래에서 선택하세요.</option>';
        //select창을 만든다. 강의명은 '강의명(작성자/멘토)강의실'.
        for($i=0;$i<count($row_plan_id);$i++){
        echo '<option value="'.$row_plan_id[$i].'">'.$row_plan_class[$i].'</option>';}
        ?>
      </select>
    </form>
  </th>
    <th>승인상태</th>
    <td><?php echo $data_plan[4]?></td>
  </tr>
</thead>
</table><br class="w3-hide-small">
<table class="w3-table-all w3-card-4 w3-bordered w3-striped w3-centered w3-small">
  <thead>
  <tr>
    <th class="studyplan_table">작성자</th>
    <td class="studyplan_table"><?php echo $data_user[4]?></td>
    <td class="studyplan_table"><?php echo $data_user[1]?></td>
    <td class="studyplan_table" colspan="3"><?php echo $data_user[2]?></td>
  </tr>
  <tr>
    <th class="studyplan_table">멘토</th>
    <td class="studyplan_table"><?php echo $data_mentor[4]?></td>
    <td class="studyplan_table"><?php echo $data_mentor[1]?></td>
    <td class="studyplan_table" colspan="3"><?php echo $data_mentor[2]?></td>
  </tr>
</thead>
<tbody>
  <tr>
    <th class="studyplan_table">교과/과목</th>
    <td class="studyplan_table"><?php echo $data_plan[5]?></td>
    <th class="studyplan_table">강좌명</th>
    <td class="studyplan_table" colspan="3"><?php echo $data_plan[6]?></td>
  </tr>
  <tr>
    <th class="studyplan_table" rowspan="2">개설배경</th>
    <td class="studyplan_table" colspan="3" rowspan="2"><?php echo $contents[0]?></td>
    <th class="studyplan_table">가치분야</th>
    <td class="studyplan_table"><?php echo $contents[1]?></td>
  </tr>
  <tr>
    <th class="studyplan_table">역량분야</th>
    <td class="studyplan_table"><?php echo $contents[2]?></td>
  </tr>
</tbody>
<tbody>
  <tr>
    <th class="studyplan_table" rowspan="9">학습내용</th>
    <th class="studyplan_table">총괄목표</th>
    <td class="studyplan_table" colspan="4"><?php echo $contents[3]?></td>
  </tr>
  <?php for($i=1;$i<=8;$i++){
    echo '<tr><th class="studyplan_table">'.$i.'주차</th><td class="studyplan_table" colspan="4">'.$contents[$i+3].'</td></tr>';}?>
  <tr>
    <th class="studyplan_table">수강대상</th>
    <td class="studyplan_table"><?php echo $contents[12]?></td>
    <th class="studyplan_table">수준</th>
    <td class="studyplan_table"><?php echo $contents[13]?></td>
    <th class="studyplan_table">신청학점</th>
    <td class="studyplan_table"><?php echo $data_plan[7]?></td>
  </tr>
  <tr>
    <th class="studyplan_table">교재</th>
    <td class="studyplan_table" colspan="5"><?php echo $contents[14]?></td>  </tr>
  <tr>
    <th class="studyplan_table" rowspan="4">평가계획</th>
    <th class="studyplan_table">평가자</th>
    <th class="studyplan_table">비율</th>
    <th class="studyplan_table" colspan="3">평가계획</th>
  </tr>
  <tr>
    <th class="studyplan_table">본인</th>
    <td class="studyplan_table"><?php echo $contents[15]?></td>
    <td class="studyplan_table" colspan="3"><?php echo $contents[16]?></td>
  </tr>
  <tr>
    <th class="studyplan_table">멘토</th>
    <td class="studyplan_table"><?php echo $contents[17]?></td>
    <td class="studyplan_table" colspan="3"><?php echo $contents[18]?></td>
  </tr>
  <tr>
    <th class="studyplan_table">어드바이저</th>
    <td class="studyplan_table"><?php echo $contents[19]?></td>
    <td class="studyplan_table" colspan="3"><?php echo $contents[20]?></td>
  </tr>
  <tr>
    <th class="studyplan_table">등급기준</th>
    <td class="studyplan_table" colspan="5"><?php echo $contents[21]?></td>
  </tr>
</tbody>
</table>
<br>

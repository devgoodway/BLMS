<?php
// 권한 관리
require_once 'php/user_chk.php';

//평가 등록(~2018)
if(empty($_POST['apply_id'])===false && substr($_COOKIE['Season'],0,4)<2019){
  mysqli_query($conn, "UPDATE bs_apply SET apply_mento_evl = '".addslashes(nl2br($_POST['mentoEv']))."', apply_rating='".$_POST['EV']."', apply_created =now() WHERE apply_id='".$_POST['apply_id']."'");
}elseif(empty($_POST['apply_id'])===false && substr($_COOKIE['Season'],0,4)>2018){
  //평가 등록(2019~)
  $result_Ev = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub = '".$_POST[apply_sub]."' AND apply_season = '".$_COOKIE['Season']."' AND apply_email = '".$_POST[apply_email]."'");
  while( $row_Ev = mysqli_fetch_assoc($result_Ev)){
  //같은 쿼터에 같은 교과목인 수업은 등급을 똑같은 값으로 넣는다.
    mysqli_query($conn, "UPDATE bs_apply SET apply_rating='".$_POST['EV']."', apply_created =now() WHERE apply_id='".$row_Ev['apply_id']."'");
  }
  $result_mentoEv = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub = '".$_POST[apply_sub]."' AND apply_season LIKE '%".substr($_COOKIE['Season'],0,4)."%' AND apply_email = '".$_POST[apply_email]."'");
  while( $row_mentoEv = mysqli_fetch_assoc($result_mentoEv)){
  //세특을 교과목명이 같은 값을 똑같은 값으로 넣는다.
    mysqli_query($conn, "UPDATE bs_apply SET apply_mento_evl = '".addslashes(nl2br($_POST['mentoEv']))."', apply_created =now() WHERE apply_id='".$row_mentoEv['apply_id']."'");
  }
}

//선택된 학습계획서 정보를 data_plan에 저장
$result_plan = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season = '".$_COOKIE[Season]."'");
while( $row_plan = mysqli_fetch_assoc($result_plan)){
if($row_plan['plan_id']===$_POST['planId']){
$data_plan = array($row_plan['plan_id'], $row_plan['plan_name'], $row_plan['plan_email'], $row_plan['plan_mentor'], $row_plan['plan_apv'], $row_plan['plan_sub'], $row_plan['plan_class'], $row_plan['plan_point'], $row_plan['plan_classroom'], $row_plan['plan_contents'], $row_plan['plan_time'], $row_plan['plan_created']);}}

//선택된 학습계획서 정보에 있는 멘토의 정보를 data_mentor에 저장
$result_mentor = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".substr($_COOKIE['Season'],0,4)."'");
$i=0;
while( $row_mentor = mysqli_fetch_assoc($result_mentor)){
if($row_mentor['user_name']===$data_plan[3]){
$data_mentor = array($row_mentor['user_id'], $row_mentor['user_name'], $row_mentor['user_email'], $row_mentor['user_adv'], $row_mentor['user_grade'], $row_mentor['user_created']);}
$apply_name[$i] = $row_mentor['user_name'];
$apply_grade[$i] = $row_mentor['user_grade'];$i++;}

//선택된 학습계획서 정보에 있는 작성자의 정보를 data_user에 저장
$result_user = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".substr($_COOKIE['Season'],0,4)."'");
while( $row_user = mysqli_fetch_assoc($result_user)){
if($row_user['user_email']===$data_plan[2]){
$data_user = array($row_user['user_id'], $row_user['user_name'], $row_user['user_email'], $row_user['user_adv'], $row_user['user_grade'], $row_user['user_created']);}}

//선택된 학습계획서의 plan_contents를 배열로 나누어 저장
$contents = explode('%^',$data_plan[9]);
//선택된 학습계획서의 plan_time를 배열로 나누어 저장 & 정렬
$time = explode('/',$data_plan[10]);sort($time);
//선택된 학습계획서의 plan_sub를 배열로 나누어 저장
$sub = explode('/',$data_plan[5]);
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

<h4><strong>학습계획서 및 평가</strong></h4>
<!-- <table class="w3-card-4 w3-bordered w3-centered w3-small w3-hide-small">
<thead>
  <tr>
    <th><form action="index.php?id=Result_Plan_EV" method="post">
      <select class="w3-input plan-form" name="planId" onchange="form.submit()">
        <?php
        echo '<option value="">아래에서 선택하세요.</option>';
        $result_plan_option = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season = '".$_COOKIE[Season]."'");
        while( $row_plan_option = mysqli_fetch_assoc($result_plan_option)){
        //select창을 만든다. 강의명은 '강의명(작성자/멘토)강의실'.
        echo '<option value="'.$row_plan_option['plan_id'].'">'.$row_plan_option['plan_class'].'</option>';}
        ?>
      </select>
    </form>
  </th>
    <th>승인상태</th>
    <td><?php echo $data_plan[4]?></td>
    <td>        <?php
            echo '<form action="print.php?id=Result_Plan_EV" method="post" target="_blank"><input type="hidden" name="planId" value="'.$_POST['planId'].'"><input class="w3-button w3-theme-l3" type="submit" name="인쇄" value="인쇄"></form>';?></td>
  </tr>
</thead>
</table><br> -->
<table class="w3-card-4 w3-bordered w3-centered w3-centered w3-small">
  <thead>
  <tr>
    <th class=" studyplan_table">작성자</th>
    <td class="studyplan_table"><?php echo $data_user[4]?></td>
    <td class="studyplan_table"><?php echo $data_user[1]?></td>
    <td class="studyplan_table" colspan="3"><?php echo $data_user[2]?></td>
  </tr>
  <tr>
    <th class=" studyplan_table">멘토</th>
    <td class="studyplan_table"><?php echo $data_mentor[4]?></td>
    <td class="studyplan_table"><?php echo $data_mentor[1]?></td>
    <td class="studyplan_table" colspan="3"><?php echo $data_mentor[2]?></td>
  </tr>
</thead>
<tbody>
  <tr>
    <th class=" studyplan_table">교과/과목</th>
    <td class="studyplan_table"><?php echo $data_plan[5]?></td>
    <th class=" studyplan_table">강좌명</th>
    <td class="studyplan_table" colspan="3"><?php echo $data_plan[6]?></td>
  </tr>
  <tr>
    <th class=" studyplan_table" rowspan="2">개설배경</th>
    <td class="studyplan_table" colspan="3" rowspan="2"><?php echo $contents[0]?></td>
    <th class=" studyplan_table">가치분야</th>
    <td class="studyplan_table"><?php echo $contents[1]?></td>
  </tr>
  <tr>
    <th class=" studyplan_table">역량분야</th>
    <td class="studyplan_table"><?php echo $contents[2]?></td>
  </tr>
</tbody>
<tbody>
  <tr>
    <th class=" studyplan_table" rowspan="9">학습내용</th>
    <th class=" studyplan_table">총괄목표</th>
    <td class="studyplan_table" colspan="4"><?php echo $contents[3]?></td>
  </tr>
  <?php for($i=1;$i<=8;$i++){
    echo '<tr><th class=" studyplan_table">'.$i.'주차</th><td class="studyplan_table" colspan="4">'.$contents[$i+3].'</td></tr>';}?>
  <tr>
    <th class=" studyplan_table">수강대상</th>
    <td class="studyplan_table"><?php echo $contents[12]?></td>
    <th class=" studyplan_table">수준</th>
    <td class="studyplan_table"><?php echo $contents[13]?></td>
    <th class=" studyplan_table">신청학점</th>
    <td class="studyplan_table"><?php echo $data_plan[7]?></td>
  </tr>
  <tr>
    <th class=" studyplan_table">교재</th>
    <td class="studyplan_table" colspan="5"><?php echo $contents[14]?></td>  </tr>
  <tr>
    <th class=" studyplan_table" rowspan="4">평가계획</th>
    <th class=" studyplan_table">평가자</th>
    <th class=" studyplan_table">비율</th>
    <th class=" studyplan_table" colspan="3">평가계획</th>
  </tr>
  <tr>
    <th class=" studyplan_table">본인</th>
    <td class="studyplan_table"><?php echo $contents[15]?></td>
    <td class="studyplan_table" colspan="3"><?php echo $contents[16]?></td>
  </tr>
  <tr>
    <th class=" studyplan_table">멘토</th>
    <td class="studyplan_table"><?php echo $contents[17]?></td>
    <td class="studyplan_table" colspan="3"><?php echo $contents[18]?></td>
  </tr>
  <tr>
    <th class=" studyplan_table">어드바이저</th>
    <td class="studyplan_table"><?php echo $contents[19]?></td>
    <td class="studyplan_table" colspan="3"><?php echo $contents[20]?></td>
  </tr>
  <tr>
    <th class=" studyplan_table">등급기준</th>
    <td class="studyplan_table" colspan="5"><?php echo $contents[21]?></td>
  </tr>
</tbody>
</table>
<h6><strong>수강명단</strong></h6>
<table class="w3-card-4 w3-bordered w3-centered w3-centered w3-small">
 <tfoot>
 <tr>
   <th class=" studyplan_table" style="width:5%" colspan="2">개설시간</th>
   <td class="studyplan_table" colspan="3"><?php
      $time_sort = explode('(',$data_plan[6]);
      $time_sort = explode(')',$time_sort[1]);
      echo $time_sort[0]; ?>
   </td>
   <th class=" studyplan_table">강의실</th>
   <td class="studyplan_table"><?php echo $data_plan[8]?></td>
 </tr>
 <tr>
   <th class=" studyplan_table" style="width:5%;">번호</th>
   <th class=" studyplan_table" style="width:5%;">학년</th>
   <th class=" studyplan_table" style="width:10%;">이름</th>
   <th class=" studyplan_table" style="width:35%;">자기평가</th>
   <th class=" studyplan_table" style="width:35%;">멘토평가</th>
   <th class=" studyplan_table" style="width:5%;">평가</th>
   <th class=" studyplan_table" style="width:10%;">제출</th>
 </tr>
 <?php
 //선택된 학습계획서 정보에 있는 작성자의 정보를 출력
 $j = 1;
 $i = 0;
 $result_apply = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_season = '".$_COOKIE[Season]."'");
 while( $row_apply = mysqli_fetch_assoc($result_apply)){
   $row_apply_array[$i]=$row_apply;
   $i++;
 }
 // 열 목록 얻기
 foreach ($row_apply_array as $key => $row) {
     $apply_id[$key]  = $row[0];
 }
 //apply_id순서로 정렬
 array_multisort($apply_id, SORT_ASC, $row_apply_array);

//배열의 숫자만큼 반복한다.
 for($k=0;$k<count($row_apply_array);$k++){
   $mentor_evl = $evl_created = '';
   if($row_apply_array[$k]['apply_class']===$data_plan[6]){
     echo '<tr><td class="studyplan_table" style="width:5%">'.$j.'</td>';$j++;
     for($i=0;$i<=count($apply_name);$i++){
       if($apply_name[$i]==$row_apply_array[$k]['apply_name']){
     echo '<td class="studyplan_table" style="width:5%">'.$apply_grade[$i].'</td>';}}
     echo '<td class="studyplan_table" style="width:5%">'.$row_apply_array[$k]['apply_name'];
       //쿼터, 교과, 이메일이 같은 학생에게 빨간색 fas fa-clone 적용<i class="fas fa-clone"></i>
       if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_season = '".$_COOKIE['Season']."' AND apply_sub = '".$row_apply_array[$k][apply_sub]."' AND apply_email = '".$row_apply_array[$k][apply_email]."'"))>1){
         echo ' <i style="color:red;" class="fa fa-clone"></i>';
       }
     echo '</td>';
     echo '<td class="studyplan_table">'.$row_apply_array[$k]['apply_self_evl'].'</td>';
     echo '<form action="index.php?id=Result_Plan_EV" method="post"><input type="hidden" name="planId" value="'.$_POST['planId'].'"><input type="hidden" name="apply_sub" value="'.$data_plan[5].'"><input type="hidden" name="apply_email" value="'.$data_plan[2].'"><input type="hidden" name="apply_id" value="'.$row_apply_array[$k]['apply_id'].'"><td class="studyplan_table">';
     if($row_apply_array[$k]['apply_mento_evl']=='' && substr($_COOKIE['Season'],0,4)>2018){//만약 현재 세특 결과가 없다면 같은 년도 다른 쿼터의 평가 결과를 가져와서 출력
       $result_mentor_evl = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_season LIKE '%".substr($_COOKIE['Season'],0,4)."%' AND apply_sub = '".$row_apply_array[$k][apply_sub]."' AND apply_email = '".$row_apply_array[$k][apply_email]."'");
       while( $row_mentor_evl = mysqli_fetch_assoc($result_mentor_evl)){
         if($row_mentor_evl['apply_mento_evl']!='')
         $mentor_evl = $row_mentor_evl['apply_mento_evl'];
         $evl_created = $row_mentor_evl['apply_created'];
       }
     }else{
       $mentor_evl = $row_apply_array[$k]['apply_mento_evl'];
       $evl_created = $row_apply_array[$k]['apply_created'];
     }
     echo '<textarea style="width:100%;height:200px" class="w3-input" type="text" name="mentoEv">'.str_replace('<br />','',$mentor_evl).'</textarea>';
     echo $evl_created;
     echo '</td><td style="width:5%" class="studyplan_table"><select name="EV"><option value="'.$row_apply_array[$k]['apply_rating'].'">'.$row_apply_array[$k]['apply_rating'].'</option><option value=""></option><option value="F">F</option><option value="P">P</option><option value="C">C</option><option value="B">B</option><option value="A">A</option></select></td>';
     echo '<td class="studyplan_table"><input class="w3-button w3-theme-l3" type="submit" name="제출" value="제출"></form></td></tr>';

   }
 }
 ?>
</tfoot>
</table>
<br>

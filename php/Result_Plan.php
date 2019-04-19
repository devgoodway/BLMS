<?php
// 권한 관리
require_once 'php/user_chk.php';

//plan id를 get으로 받았을 경우 get의 내용을 post로 보낸다.
if($_GET[planId]){$_POST[planId]=$_GET[planId];}

//선택된 학습계획서 정보를 data_plan에 저장
$result_plan = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season = '".$_COOKIE['Season']."' AND plan_id = '".$_POST['planId']."'");
while( $row_plan = mysqli_fetch_assoc($result_plan)){
$data_plan = array($row_plan['plan_id'], $row_plan['plan_name'], $row_plan['plan_email'], $row_plan['plan_mentor'], $row_plan['plan_apv'], $row_plan['plan_sub'], $row_plan['plan_class'], $row_plan['plan_point'], $row_plan['plan_classroom'], $row_plan['plan_contents'], $row_plan['plan_time'], $row_plan['plan_created']);}

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

<h4><strong>학습계획서 결과 - <?php echo $_COOKIE[Season];?></strong></h4>
<table class="w3-card-4 w3-bordered  w3-centered w3-small w3-hide-small">
<thead>
  <tr>
    <th><form action="index.php?id=Result_Plan" method="post">
      <select style="width:100%" class="w3-class w3-hide-small" name="planId" onchange="form.submit()">
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
    <td><?php if($data_plan[4]){echo $data_plan[4];}else{echo "승인상태";}?></td>
    <td><?php
    echo '<form action="index.php?id=Design_Plan_Reuse" method="post" target="_blank"><input type="hidden" name="planId" value='.$data_plan[0].'><input class="w3-button w3-theme-l4 w3-hide-small" type="submit" name="submit" value="복사"></form>';?></td>
    <td>
      <?php
      echo '<form action="print.php?id=Result_Plan" method = "post" target="_blank"><input type="hidden" name="planId" value="'.$_POST['planId'].'"><input class="w3-button w3-theme-l4 w3-hide-small" type="submit" name="submit" value="인쇄"></form>';?></td>
  </tr>
</thead>
</table><br>
<table class="w3-card-4 w3-bordered  w3-centered w3-small">
  <thead>
  <tr>
    <th class=" studyplan_table">작성자</th>
    <td class="studyplan_table"><?php echo $data_user[4]?></td>
    <td class="studyplan_table"><?php echo $data_user[1]?></td>
    <td class="studyplan_table" colspan="3"><?php echo '<a href="mailto:'.$data_user[2].'" target="_blank">'.$data_user[2].'</a>';?></td>
  </tr>
  <tr>
    <th class=" studyplan_table">멘토</th>
    <td class="studyplan_table"><?php echo $data_mentor[4]?></td>
    <td class="studyplan_table"><?php echo $data_mentor[1];?></td>
    <td class="studyplan_table" colspan="3"><?php echo '<a href="mailto:'.$data_mentor[2].'" target="_blank">'.$data_mentor[2].'</a>';?></td>
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
<table class="w3-card-4 w3-bordered  w3-centered w3-small">
 <tfoot>
 <tr>
   <th class=" studyplan_table">개설시간</th>
   <td class="studyplan_table" colspan="3">
     <?php
        // $time_sort = explode('(',$data_plan[6]);
        // $time_sort = explode(')',$time_sort[1]);
        // echo $time_sort[0];
        for($x=0;$x<=count($time);$x++){
        if(empty($time[$x])===false){
          echo $time[$x];}}
     ?>
   </td>
   <th class=" studyplan_table">강의실</th>
   <td class="studyplan_table"><?php echo $data_plan[8]?></td>
 </tr>
 <tr>
   <th class=" studyplan_table">번호</th>
   <th class=" studyplan_table">학년</th>
   <th class=" studyplan_table">이름</th>
   <th class=" studyplan_table" colspan="3">이메일</th>
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
   if($row_apply_array[$k]['apply_class']===$data_plan[6]){
     echo '<tr><td class="studyplan_table">'.$j.'</td>';$j++;
     for($i=0;$i<=count($apply_name);$i++){
       if($apply_name[$i]==$row_apply_array[$k]['apply_name']){
     echo '<td class="studyplan_table">'.$apply_grade[$i].'</td>';}}
     echo '<td class="studyplan_table">'.$row_apply_array[$k][apply_name];
       //쿼터, 교과, 이메일이 같은 학생에게 빨간색 fas fa-clone 적용<i class="fas fa-clone"></i>
       if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_season = '".$_COOKIE['Season']."' AND apply_sub = '".$row_apply_array[$k][apply_sub]."' AND apply_email = '".$row_apply_array[$k][apply_email]."'"))>1){
         echo ' <i style="color:red;" class="fa fa-clone"></i>';
       }
     echo '</td>';
     echo '<td class="studyplan_table" colspan="3"><a href="mailto:'.$row_apply_array[$k][apply_email].'" target="_blank">'.$row_apply_array[$k][apply_email].'</td></tr>';
     //모든 수강 학생의 이메일을 저장 할 변수
     $apply_emails .= $row_apply_array[$k][apply_email].',';
   }
 }
 echo '<tr><td class="studyplan_table" colspan="6"><a href="mailto:'.$apply_emails.'" target="_blank">모든 수강생에게 이메일 보내기</td></tr>';
 ?>
</tfoot>
</table>

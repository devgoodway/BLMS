<?php
// 권한 관리
require_once 'php/user_chk.php';
// 사용자가 어드바이징 하고 있는 user_name을 data_adv에 저장
$result_adv = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_grade LIKE '%학년' AND user_season=".substr($_COOKIE['Season'],0,4));
$i = 0;$j = 0;
while( $row_adv = mysqli_fetch_assoc($result_adv)){
   //전체 학생 명단 배정
  $data_all_name[$j] = $row_adv[user_name];
  $data_all_email[$j] = $row_adv[user_email];
  $j++;
  //어드바이저 명단 배정
if($row_adv['user_adv']===$_COOKIE['Name']){
$data_adv_id[$i] = $row_adv[user_id];
$data_adv_email[$i] = $row_adv[user_email];
$data_adv[$i] = $row_adv[user_name];
$data_adv_approved[$i] = $row_adv['user_'.substr($_COOKIE['Season'],14,1).'q'];$i++;}
}
// post받은 값이 없을 경우 초기값으로 설정한다. 다른 함수들 보다 아래 수식이 선행되어야 한다.
if(empty($_POST[user_name])){
array_multisort($data_adv);
$_POST[user_name] = $data_adv[0];
}

//사용자의 승인정보를 얻는다.
$result_user = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season=".substr($_COOKIE['Season'],0,4));
while( $row_user = mysqli_fetch_assoc($result_user))
{
  if($row_user[user_name]==$_POST[user_name]){
    $data_user_approved = $row_user[user_apv];//승인정보저장
    $data_user_grade = $row_user[user_grade];//직위정보저장
    $data_user_email = $row_user[user_email];//이메일정보저장
  }}

//시간표 승인
if($_POST['adv_apv']=="승인"){
$sql_adv_apv = 'UPDATE bs_users SET user_'.substr($_COOKIE['Season'],14,1).'q="승인" WHERE user_id='.$_POST['adv_id'].' AND user_season='.substr($_COOKIE['Season'],0,4);
mysqli_query($conn, $sql_adv_apv);$_POST['adv_apv'] = NULL;$data_adv_approved[$i]="승인";}

// 수상경력 삭제
if($_POST['awardsDel']==="삭제"){
  $result_awards_delete = mysqli_query($conn,"DELETE FROM bs_awards WHERE awards_id='".$_POST['awardsId']."'");
}

// 자격증 및 인증 취득상황 삭제
if($_POST['certificateDel']==="삭제"){
  $result_certificate_delete = mysqli_query($conn,"DELETE FROM bs_certificate WHERE certificate_id='".$_POST['certificateId']."'");
}

// 봉사활동실적 삭제
if($_POST['volunteerDel']==="삭제"){
  $result_volunteer_delete = mysqli_query($conn,"DELETE FROM bs_volunteer WHERE volunteer_id='".$_POST['volunteerId']."'");
}

// 독서활동상황 삭제
if($_POST['readingDel']==="삭제"){
  $result_reading_delete = mysqli_query($conn,"DELETE FROM bs_reading WHERE reading_id='".$_POST['readingId']."'");
}

//수상경력 등록
if(empty($_POST[awards_award])==false){
$sql_awards = "INSERT INTO bs_awards (awards_email,awards_grade,awards_name,awards_award,awards_rank,awards_date,awards_agency,awards_object,awards_created) VALUES('".$data_user_email."','".$data_user_grade."','".$_POST[user_name]."','".addslashes($_POST[awards_award])."','".addslashes($_POST[awards_rank])."','".addslashes($_POST[awards_date])."','".addslashes($_POST[awards_agency])."','".addslashes($_POST[awards_object])."',now())";
mysqli_query($conn, $sql_awards);}

//자격증 및 인증 취득상황 등록
if(empty($_POST[certificate_division])==false){
$sql_certificate = "INSERT INTO bs_certificate (certificate_email,certificate_grade,certificate_name,certificate_division,certificate_kinds,certificate_number,certificate_date,certificate_agency,certificate_created) VALUES('".$data_user_email."','".$_POST[certificate_grade]."','".$_POST[user_name]."','".addslashes($_POST[certificate_division])."','".addslashes($_POST[certificate_kinds])."','".addslashes($_POST[certificate_number])."','".addslashes($_POST[certificate_date])."','".addslashes($_POST[certificate_agency])."',now())";
mysqli_query($conn, $sql_certificate);}

//봉사활동실적 등록
if(empty($_POST[volunteer_date])==false){
$sql_volunteer = "INSERT INTO bs_volunteer (volunteer_email,volunteer_grade,volunteer_name,volunteer_date,volunteer_place,volunteer_activity,volunteer_time,volunteer_created) VALUES('".$data_user_email."','".$data_user_grade."','".$_POST[user_name]."','".addslashes($_POST[volunteer_date])."','".addslashes($_POST[volunteer_place])."','".addslashes($_POST[volunteer_activity])."','".addslashes($_POST[volunteer_time])."',now())";
mysqli_query($conn, $sql_volunteer);}

//독서활동상황 등록
if(empty($_POST[reading_sub])==false){
$sql_reading = "INSERT INTO bs_reading (reading_email,reading_grade,reading_name,reading_sub,reading_text,reading_created) VALUES('".$data_user_email."','".$data_user_grade."','".$_POST[user_name]."','".addslashes(nl2br($_POST[reading_sub]))."','".addslashes(nl2br($_POST[reading_text]))."',now())";
mysqli_query($conn, $sql_reading);}

//인적사항 업데이트
if(empty($_POST[user_gender])==false){
  $sql_users = "UPDATE bs_users SET user_graduation = '".$_POST[user_graduation]."',user_gender = '".$_POST[user_gender]."',user_rrn = '".$_POST[user_rrn]."',user_adr = '".$_POST[user_adr]."',user_fname = '".$_POST[user_fname]."',user_fbirth = '".addslashes($_POST[user_fbirth])."',user_mname = '".$_POST[user_mname]."',user_mbirth = '".addslashes($_POST[user_mbirth])."',user_pi_remarks = '".addslashes($_POST[user_pi_remarks])."' WHERE user_name = '".$_POST[user_name]."' AND user_season = '".substr($_COOKIE['Season'],0,4)."'";
  $result = mysqli_query($conn, $sql_users);
}

//학적사항 업데이트
if(empty($_POST[user_history])==false){
  $text = addslashes(nl2br($_POST[user_history]));
  $sql_users = "UPDATE bs_users SET user_history = '".$text."',user_ht_remarks = '".addslashes($_POST[user_ht_remarks])."' WHERE user_name = '".$_POST[user_name]."' AND user_season = '".substr($_COOKIE['Season'],0,4)."'";
  $result = mysqli_query($conn, $sql_users);
}

//출결사항 업데이트
if(empty($_POST[user_days])==false){
  $sql_users = "UPDATE bs_users SET user_days = '".$_POST[user_days]."',user_ab_dis = '".$_POST[user_ab_dis]."',user_ab_unauth = '".$_POST[user_ab_unauth]."',user_ab_etc = '".$_POST[user_ab_etc]."',user_late_dis = '".$_POST[user_late_dis]."',user_late_unauth = '".$_POST[user_late_unauth]."',user_late_etc = '".$_POST[user_late_etc]."',user_early_dis = '".$_POST[user_early_dis]."',user_early_unauth = '".$_POST[user_early_unauth]."',user_early_etc = '".$_POST[user_early_etc]."',user_skip_dis = '".$_POST[user_skip_dis]."',user_skip_unauth = '".$_POST[user_skip_unauth]."',user_skip_etc = '".$_POST[user_skip_etc]."',user_att_remarks = '".addslashes($_POST[user_att_remarks])."' WHERE user_name = '".$_POST[user_name]."' AND user_season = '".substr($_COOKIE['Season'],0,4)."'";
  $result = mysqli_query($conn, $sql_users);
}
//진로희망사항 업데이트
if(empty($_POST[user_carrer_hope])==false){
  $sql_users = "UPDATE bs_users SET user_carrer_hope = '".addslashes($_POST[user_carrer_hope])."',user_carrer_reason = '".addslashes($_POST[user_carrer_reason])."' WHERE user_name = '".$_POST[user_name]."' AND user_season = '".substr($_COOKIE['Season'],0,4)."'";
  $result = mysqli_query($conn, $sql_users);
}

//행동특성 및 종합의견 업데이트
if(empty($_POST[user_opinion])==false){
  $sql_users = "UPDATE bs_users SET user_opinion = '".addslashes($_POST[user_opinion])."' WHERE user_name = '".$_POST[user_name]."' AND user_season = '".substr($_COOKIE['Season'],0,4)."'";
  $result = mysqli_query($conn, $sql_users);
}

//인적사항 호출
$result_users = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_name = '".$_POST[user_name]."' AND user_season = '".substr($_COOKIE['Season'],0,4)."'");
while( $row_users = mysqli_fetch_assoc($result_users)){
  $user_graduation = $row_users[user_graduation];
  $user_gender = $row_users[user_gender];
  $user_rrn = $row_users[user_rrn];
  $user_adr = $row_users[user_adr];
  $user_fname = $row_users[user_fname];
  $user_fbirth = $row_users[user_fbirth];
  $user_mname = $row_users[user_mname];
  $user_mbirth = $row_users[user_mbirth];
  $user_pi_remarks = $row_users[user_pi_remarks];
  $user_history = $row_users[user_history];
  $user_ht_remarks = $row_users[user_ht_remarks];
  $user_days = $row_users[user_days];
  $user_ab_dis = $row_users[user_ab_dis];
  $user_ab_unauth = $row_users[user_ab_unauth];
  $user_ab_etc = $row_users[user_ab_etc];
  $user_late_dis = $row_users[user_late_dis];
  $user_late_unauth = $row_users[user_late_unauth];
  $user_late_etc = $row_users[user_late_etc];
  $user_early_dis = $row_users[user_early_dis];
  $user_early_unauth = $row_users[user_early_unauth];
  $user_early_etc = $row_users[user_early_etc];
  $user_skip_dis = $row_users[user_skip_dis];
  $user_skip_unauth = $row_users[user_skip_unauth];
  $user_skip_etc = $row_users[user_skip_etc];
  $user_att_remarks = $row_users[user_att_remarks];
  $user_carrer_hope = $row_users[user_carrer_hope];
  $user_carrer_reason = $row_users[user_carrer_reason];
  $user_opinion = $row_users[user_opinion];
}

//수강신청 목록에서 이름, 강좌명, 학점, 시간을 data_apply에 저장한다.
$i=0;
$result_apply_user = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_season='".$_COOKIE['Season']."'");
while( $row_apply_user = mysqli_fetch_assoc($result_apply_user))
{
  if($row_apply_user[apply_id]==$_POST[Self_Evaluation_Id]){
  $data_apply_self_evaluation_text = $row_apply_user[apply_self_evl];
  $data_apply_self_evaluation_class = $row_apply_user[apply_class];}
  if($row_apply_user[apply_name]==$_POST[user_name]){
  $data_apply_id[$i] = $row_apply_user[apply_id];
  $data_apply_name[$i] = $row_apply_user[apply_name];
  $data_apply_class[$i] = $row_apply_user[apply_class];
  $data_apply_point[$i] = $row_apply_user[apply_point];
  $data_apply_time[$i] = $row_apply_user[apply_time];
  $data_apply_self_evaluation[$i] = $row_apply_user[apply_self_evl];
  $data_apply_mento_evaluation[$i] = $row_apply_user[apply_mento_evl];
  $data_apply_rating[$i] = $row_apply_user[apply_rating];
  $i++;}
}

//사용자가 개설한 학습계획서 plan_id를 data_plan_design에 저장
$result_plan_design = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season='".$_COOKIE['Season']."'");
$i = 0;
while( $row_plan_design = mysqli_fetch_assoc($result_plan_design)){
//사용자가 개설한 학습계획서
if($row_plan_design['plan_name']===$_POST['user_name']){
$data_plan_design[$i] = $row_plan_design['plan_id'];$i++;}

//사용자가 신청한 수업
for($j=0;$j<count($data_apply_class);$j++){
  if($data_apply_class[$j]==$row_plan_design['plan_class']){
    $data_apply_plan_id[$j] = $row_plan_design['plan_id'];
  }}}
?>

<!DOCTYPE html>
<html>
<head>
<!-- 삭제시 팝업을 띄우는 함수 -->
  <script>
function confirmDel() {
        var r = confirm("정말 삭제하시겠습니까?");
        if (r == true) {
        } else {
        return false;
        }
}
</script>
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
<h4><strong>학생 정보 - <?php echo $_COOKIE['Season'];?> [<?php echo $_POST[user_name];?>]</strong></h4>
     <h6><strong>교과 정보</strong></h6>
     <table class=" w3-card-4 w3-bordered w3-centered w3-small">
       <tbody>
   <tr>
     <th style="width:20%;">전교생</th>
     <th style="width:20%;">어드바이저</th>
     <th style="width:15%;">신청현황</th>
     <th style="width:15%;">시간표</th>
     <th style="width:15%;">승인</th>
     <th style="width:15%;">인쇄</th>
   </tr>
   <?php
   //전교생 이름
   echo '<tr><td>';
   echo '<form action="index.php?id=Information_Adv" method="post">';
   echo '<select class="w3-select" name="user_name" onchange="form.submit()">';
      array_multisort($data_all_name);
      echo '<option value="" disabled selected>'.$_POST[user_name].'</option>';
      for($i=0;$i<count($data_all_name);$i++){
        echo '<option value="'.$data_all_name[$i].'">'.$data_all_name[$i].'</option>';}
        echo '</select></form></td>';
   //어드바이저 이름
   echo '<td>';
   echo '<form action="index.php?id=Information_Adv" method="post">';
   echo '<select class="w3-select" name="user_name" onchange="form.submit()">';
      array_multisort($data_adv);
      echo '<option value="" disabled selected>'.$_POST[user_name].'</option>';
      for($i=0;$i<count($data_adv);$i++){
        echo '<option value="'.$data_adv[$i].'">'.$data_adv[$i].'</option>';}
        echo '</select></form></td>';

      for($i=0;$i<count($data_all_name);$i++){
        if($_POST[user_name]===$data_all_name[$i]){
             //학점 신청 현황
             echo '<form action="index.php?id=Information_Adv" method="post"><td style="width:15%;"><input name="user_name" value='.$_POST[user_name].' type="hidden"><input name="user_email" value='.$data_all_email[$i].' type="hidden"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="보기"></td></form>';
             //시간표
             echo '<form action="index.php" method="get"><td style="width:15%;"><input name="id" value="Result_Timetable" type="hidden"><input type="hidden" name="user_name" value="'.$data_all_name[$i].'"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="보기"></td></form>';
            for($j=0;$j<count($data_adv);$j++){
               if($_POST[user_name]===$data_adv[$j]){
                 $k = 1;
                 //승인
                 if($data_adv_approved[$j]!="승인"){
                 echo '<form action="index.php?id=Information_Adv" method="post"><td style="width:10%;"><input type="hidden" name="adv_apv" value="승인"><input name="user_name" value='.$_POST[user_name].' type="hidden"><input name="user_email" value='.$data_adv_email[$j].' type="hidden"><input name="adv_id" value='.$data_adv_id[$j].' type="hidden"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="승인"></td></form>';}
                 elseif($data_adv_approved[$j]=="승인"){
                 echo '<td style="width:10%;">승인완료</td>';}
               }
            }
            if($k!=1){
              echo '<td>권한없음</td>';
            }
             //인쇄
             echo '<form action="print.php?id=Information_Adv" method="post" target="_blank"><input type="hidden" name="user_name" value="'.$_POST['user_name'].'"><input type="hidden" name="user_email" value="'.$_POST['user_email'].'"><td ><button class="w3-button w3-theme-l4" type="submit">인쇄</button></td></form>';}}
             ?>

</tbody>
</table>

<h6><strong>수업개설현황</strong></h6>
<table class=" w3-card-4 w3-bordered w3-centered w3-small">
  <tbody>
    <tr>
      <th class="">강좌명</th>
      <th class="">교과</th>
      <th class="">학습계획서</th>
      <th class="">승인</th>
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
        //승인
        if($row_plan_design[plan_apv]!="승인"){
        echo '<td style="width:10%;">대기중</td>';}
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
   <h6><strong>학점신청현황</strong></h6>
   <table class=" w3-card-4 w3-bordered w3-centered w3-small">
     <tbody>
       <tr>
         <th style="width:60%" class="">강좌명</th>
         <th style="width:10%" class="">학점</th>
         <th style="width:10%" class="">평가</th>
         <th style="width:20%" class="">학습계획서</th>
       </tr>
       <?php
       $point = 0;
       for($i=0;$i<count($data_apply_name);$i++){
         if(empty($data_apply_point[$i])===false){
           //강좌명 출력
           echo '<tr><td style="width:60%" class="">'.$data_apply_class[$i].'</td>';
           //학점 출력
           echo '<td style="width:10%" class="">'.$data_apply_point[$i].'</td>';
           //평가 출력
           echo '<td style="width:10%" class="">'.$data_apply_rating[$i].'</td>';
           //학습계획서 출력
           echo '<td style="width:20%"><form action="index.php?id=Result_Plan" method="post" target="_blank"><input type="hidden" name="planId" value="'.$data_apply_plan_id[$i].'"><input class="w3-button w3-theme-l4"  type="submit" name="submit" value="보기"></form></td>';
           $point = $point + $data_apply_point[$i];
           echo '</tr>';}}
           echo '<td style="width:40%" class="">합계</td>';
           //학점 출력
           echo '<td style="width:10%" colspan="4">'.$point.'</td>';
           ?>
     </tbody>
   </table>

<h6><strong>평가결과</strong></h6>
<table class=" w3-card-4 w3-bordered w3-small">
  <thead>
  <tbody>
    <tr>
      <th class="w3-centered">강좌명</th>
      <th class="w3-centered" colspan="2">자기평가</th>
      <th class="w3-centered" colspan="2">멘토평가</th>
    </tr>
    <?php
    for($i=0;$i<count($data_apply_name);$i++){
      if(empty($data_apply_point[$i])===false){
        //강좌명 출력
        echo '<tr><td style="width:40%" class="">'.$data_apply_class[$i].'</td>';
        //자기평가 출력
        echo '<td style="width:30%"  colspan="2">'.$data_apply_self_evaluation[$i].'</td>';
        //멘토평가 출력
        echo '<td style="width:30%"  colspan="2">'.$data_apply_mento_evaluation[$i].'</td>';
        $point = $point + $data_apply_point[$i];
        echo '</tr>';}}
        ?>
  </tbody>
</table>

<h6><strong>인적사항</strong></h6>
<table class="w3-centered w3-card-4 w3-bordered w3-small">
  <form action="index.php?id=Information_Adv" method="post">
  <tbody>
  <tr>
    <th style="width:20%;" rowspan='2'>학생</th>
    <td style="width:10%;">성명:</td><td><?php echo $_POST[user_name];?></td><td>성별:</td><td><select class="w3-select" name="user_gender" value="<?php echo $user_gender;?>"><option value="<?php echo $user_gender;?>"><?php echo $user_gender;?></option><option value="남">남</option><option value="여">여</option></td><td>주민등록번호:</td><td><input class="w3-input" type="text" name="user_rrn" value="<?php echo $user_rrn;?>" placeholder="010101-3123456"></td>
  </tr>
  <tr>
    <td style="width:10%;">주소:</td><td colspan="5"><input class="w3-input" name="user_adr" value="<?php echo $user_adr;?>" placeholder="(12345) 충청남도 금산군 남일면 별무리 1길 3 별무리학교"></td>
  </tr>
  <tr>
    <th style="width:20%;" rowspan="2">가족 상황</th>
    <td>성명(부):</td><td colspan="2"><input class="w3-input" type="text" name="user_fname" value="<?php echo $user_fname;?>" placeholder="홍길동"></td>
    <td>생년월일(부):</td><td colspan="2"><input class="w3-input" type="text" name="user_fbirth" value="<?php echo $user_fbirth;?>" placeholder="2000년01월01일"></td>
  </tr>
  <tr>
  <td>성명(모):</td><td colspan="2"><input class="w3-input" type="text" name="user_mname" value="<?php echo $user_mname;?>" placeholder="홍길동"></td>
  <td>생년월일(모):</td><td colspan="2"><input class="w3-input" type="text" name="user_mbirth" value="<?php echo $user_mbirth;?>" placeholder="2000년01월01일"></td>
  </tr>
  <tr>
    <th style="width:20%;">특기사항</th><td colspan="3"><input class="w3-input" type="text" name="user_pi_remarks" value="<?php echo $user_pi_remarks;?>" placeholder="형(1),누나(1)"></td><th style="width:20%;">졸업대장번호</th><td><input class="w3-input" type="text" name="user_graduation" value="<?php echo $user_graduation;?>"></td><td><input name="user_name" value="<?php echo $_POST[user_name];?>" type="hidden"><input class="w3-button w3-theme-l3" type="submit" name="입력" value="입력"></td>
  </tr>
  </tbody>
  </form>
</table>
<h6><strong>학적사항</strong></h6>
<table class="w3-centered w3-card-4 w3-bordered w3-small">
  <form action="index.php?id=Information_Adv" method="post">
<tr>
  <td style="text-align:left;"colspan="3"><textarea class="w3-input" name="user_history" placeholder="2017년 02월 03일 별무리중학교 제3학년 졸업"><?php $user_history = str_replace('<br />','',$user_history);echo $user_history;?></textarea>
  </td>
</tr>
<tr>
  <th style="width:20%;">특기사항</th><td><input class="w3-input" type="text" name="user_ht_remarks" value="<?php echo $user_ht_remarks;?>" placeholder="특기사항"></td><td><input name="user_name" value="<?php echo $_POST[user_name];?>" type="hidden"><input class="w3-button w3-theme-l3" type="submit" name="입력" value="입력"></td>
</tr>
</form>
</table>

<h6><strong>출결사항</strong></h6>
<table class="w3-centered w3-card-4 w3-bordered w3-small">
  <form action="index.php?id=Information_Adv" method="post">
<tr>
<th style="width:5%"rowspan="2">학년</th><th rowspan="2">수업<br>일수</th><th colspan="3">결석일수</th><th colspan="3">지각</th><th colspan="3">조퇴</th><th colspan="3">결과</th>
</tr>
<tr>
<th class="">질병</th><th class="">무단</th><th class="">기타</th><th class="">질병</th><th class="">무단</th><th class="">기타</th><th class="">질병</th><th class="">무단</th><th class="">기타</th><th class="">질병</th><th class="">무단</th><th class="">기타</th>
</tr>
<tr>
<td><?php echo $data_user_grade?></td>
<td><input class="w3-input" type="text" name="user_days" value="<?php echo $user_days;?>"></td>
<td><input class="w3-input" type="text" name="user_ab_dis" value="<?php echo $user_ab_dis;?>"></td>
<td><input class="w3-input" type="text" name="user_ab_unauth" value="<?php echo $user_ab_unauth;?>"></td>
<td><input class="w3-input" type="text" name="user_ab_etc" value="<?php echo $user_ab_etc;?>"></td>
<td><input class="w3-input" type="text" name="user_late_dis" value="<?php echo $user_late_dis;?>"></td>
<td><input class="w3-input" type="text" name="user_late_unauth" value="<?php echo $user_late_unauth;?>"></td>
<td><input class="w3-input" type="text" name="user_late_etc" value="<?php echo $user_late_etc;?>"></td>
<td><input class="w3-input" type="text" name="user_early_dis" value="<?php echo $user_early_dis;?>"></td>
<td><input class="w3-input" type="text" name="user_early_unauth" value="<?php echo $user_early_unauth;?>"></td>
<td><input class="w3-input" type="text" name="user_early_etc" value="<?php echo $user_early_etc;?>"></td>
<td><input class="w3-input" type="text" name="user_skip_dis" value="<?php echo $user_skip_dis;?>"></td>
<td><input class="w3-input" type="text" name="user_skip_unauth" value="<?php echo $user_skip_unauth;?>"></td>
<td><input class="w3-input" type="text" name="user_skip_etc" value="<?php echo $user_skip_etc;?>"></td>
</tr>
<tr>
<th class=""style="width:5%">특기<br>사항</th>
<td colspan="12"><textarea class="w3-input" name="user_att_remarks"><?php echo $user_att_remarks;?></textarea></td>
<td><input name="user_name" value="<?php echo $_POST[user_name];?>" type="hidden"><input class="w3-button w3-theme-l3" type="submit" name="입력" value="입력"></td>
</tr>
</form>
</table>

<h6><strong>수상경력</strong></h6>
<table class="w3-centered w3-card-4 w3-bordered w3-small">
  <form action="index.php?id=Information_Adv" method="post">
<tr>
<th class=""style="width:40%;">수상명</th><th class="">등위</th><th class="">수상년월일</th><th class="">수여기관</th><th class="">참가대상</th><th class="">등록/삭제</th>
</tr>
<tr>
<form action="index.php?id=Information_Adv" method="post">
<td style="width:40%;"><input class="w3-input" type="text" name="awards_award"></td>
<td><input class="w3-input" type="text" name="awards_rank"></td>
<td><input class="w3-input" type="text" name="awards_date"></td>
<td><input class="w3-input" type="text" name="awards_agency"></td>
<td><input class="w3-input" type="text" name="awards_object"></td>
<td><input name="user_name" value="<?php echo $_POST[user_name];?>" type="hidden"><input class="w3-button w3-theme-l3" type="submit" name="제출" value="등록"></td>
</form>
</tr>
<?php
$result_awards = mysqli_query($conn, "SELECT * FROM bs_awards WHERE awards_name = '".$_POST[user_name]."'");
while($row_awards = mysqli_fetch_assoc($result_awards)){
  echo '<tr><form action="index.php?id=Information_Adv" onsubmit="return confirmDel()" method="post">';
  echo '<td style="width:40%;">'.$row_awards['awards_award'].'</td>';
  echo '<td>'.$row_awards['awards_rank'].'</td>';
  echo '<td>'.$row_awards['awards_date'].'</td>';
  echo '<td>'.$row_awards['awards_agency'].'</td>';
  echo '<td>'.$row_awards['awards_object'].'</td>';
  echo '<td><input type="hidden" name="awardsId" value="'.$row_awards['awards_id'].'"><input type="hidden" name="awardsDel" value="삭제"><input name="user_name" value="'.$_POST[user_name].'" type="hidden"><input class="w3-button w3-theme-l4" type="submit" name="삭제" value="삭제"></td></form></tr>';
} ?>
</table>

<h6><strong>자격증 및 인증 취득상황</strong></h6>
<table class="w3-centered w3-card-4 w3-bordered w3-small">
  <form action="index.php?id=Information_Adv" method="post">
<tr>
<th class=""style="width:10%;">구분</th><th class="">명칭 또는 종류</th><th class="">번호 또는 내용</th><th class="">취득연월일</th><th class="">발급기관</th><th class="">등록/삭제</th>
</tr>
<tr>
<td style="width:10%;"><input class="w3-input" type="text" name="certificate_division"></td>
<td><input class="w3-input" type="text" name="certificate_kinds"></td>
<td><input class="w3-input" type="text" name="certificate_number"></td>
<td><input class="w3-input" type="text" name="certificate_date"></td>
<td><input class="w3-input" type="text" name="certificate_agency"></td>
<td><input name="user_name" value="<?php echo $_POST[user_name];?>" type="hidden"><input class="w3-button w3-theme-l3" type="submit" name="제출" value="등록"></td>
</tr>
</form>
<?php
$result_certificate = mysqli_query($conn, "SELECT * FROM bs_certificate WHERE certificate_name = '".$_POST[user_name]."'");
while($row_certificate = mysqli_fetch_assoc($result_certificate)){
  echo '<tr><form action="index.php?id=Information_Adv" onsubmit="return confirmDel()" method="post">';
  echo '<td style="width:10%;">'.$row_certificate['certificate_division'].'</td>';
  echo '<td>'.$row_certificate['certificate_kinds'].'</td>';
  echo '<td>'.$row_certificate['certificate_number'].'</td>';
  echo '<td>'.$row_certificate['certificate_date'].'</td>';
  echo '<td>'.$row_certificate['certificate_agency'].'</td>';
  echo '<td><input type="hidden" name="certificateId" value="'.$row_certificate['certificate_id'].'"><input type="hidden" name="certificateDel" value="삭제"><input class="w3-button w3-theme-l4" type="submit" name="삭제" value="삭제"></td></form></tr>';
} ?>
</table>

<h6><strong>봉사활동실적</strong></h6>
<table class="w3-centered w3-card-4 w3-bordered w3-small">
  <form action="index.php?id=Information_Adv" method="post">
<tr>
<th class=""style="width:10%;" rowspan="2">학년</th><th class=""colspan="6">봉사활동실적</th>
</tr>
<tr>
<th class="">일자 또는 기간</th><th class="">장소 또는 주관기관명</th><th class="">활동내용</th><th class="">시간</th><th class="">등록/삭제</th>
</tr>
<tr>
<form action="index.php?id=Information_Adv" method="post">
<td style="width:10%;"><?php echo $data_user_grade?></td>
<td><input class="w3-input" type="text" name="volunteer_date" ></td>
<td><input class="w3-input" type="text" name="volunteer_place"></td>
<td><input class="w3-input" type="text" name="volunteer_activity"></td>
<td><input class="w3-input" type="text" name="volunteer_time"></td>
<td><input name="user_name" value="<?php echo $_POST[user_name];?>" type="hidden"><input class="w3-button w3-theme-l3" type="submit" name="등록" value="등록"></td></form>
</tr>
<?php
$result_volunteer = mysqli_query($conn, "SELECT * FROM bs_volunteer WHERE volunteer_name = '".$_POST[user_name]."' ORDER BY volunteer_grade,volunteer_date,volunteer_id");
while($row_volunteer = mysqli_fetch_assoc($result_volunteer)){
  $sum = $sum+$row_volunteer['volunteer_time'];
  echo '<tr><form action="index.php?id=Information_Adv" onsubmit="return confirmDel()" method="post">';
  echo '<td style="width:10%;">'.$row_volunteer['volunteer_grade'].'</td>';
  echo '<td>'.$row_volunteer['volunteer_date'].'</td>';
  echo '<td>'.$row_volunteer['volunteer_place'].'</td>';
  echo '<td style="width:40%;">'.$row_volunteer['volunteer_activity'].'</td>';
  echo '<td>'.$row_volunteer['volunteer_time'].'</td>';
  echo '<td><input type="hidden" name="volunteerId" value="'.$row_volunteer['volunteer_id'].'"><input type="hidden" name="volunteerDel" value="삭제"><input name="user_name" value="'.$_POST[user_name].'" type="hidden"><input class="w3-button w3-theme-l4" type="submit" name="삭제" value="삭제"></td></form></tr>';
} ?>
</form>
</table>

<h6><strong>독서활동상황</strong></h6>
<table class="w3-centered w3-card-4 w3-bordered w3-small">
  <form action="index.php?id=Information_Adv" method="post">
<tr>
<th class=""style="width:10%;">학년</th><th class="">과목 또는 영역</th><th class="">독서활동 상황</th><th class="">등록/삭제</th>
</tr>
<tr>
<form action="index.php?id=Information_Adv" method="post">
<td style="width:10%;"><?php echo $data_user_grade?></td>
<td><input class="w3-input" type="text" name="reading_sub"></td>
<td><textarea class="w3-input" name="reading_text"></textarea></td>
<td><input name="user_name" value="<?php echo $_POST[user_name];?>" type="hidden"><input class="w3-button w3-theme-l3" type="submit" name="등록" value="등록"></td>
</form>
</tr>
<?php
$result_reading = mysqli_query($conn, "SELECT * FROM bs_reading WHERE reading_grade = '".$data_user_grade."' AND reading_name = '".$_POST[user_name]."'");
while($row_reading = mysqli_fetch_assoc($result_reading)){
  echo '<tr><form action="index.php?id=Information_Adv" method="post">';
  echo '<td style="width:10%;">'.$data_user_grade.'</td>';
  echo '<td style="width:40%;">'.$row_reading['reading_sub'].'</td>';
  echo '<td>'.$row_reading['reading_text'].'</td>';
  echo '<td><input type="hidden" name="readingId" value="'.$row_reading['reading_id'].'"><input type="hidden" name="readingDel" value="삭제"><input name="user_name" value='.$_POST[user_name].' type="hidden"><input class="w3-button w3-theme-l4" type="submit" name="삭제" value="삭제"></td></form></tr>';
} ?>
</form>
</table>

<h6><strong>진로희망사항</strong></h6>
<table class="w3-centered w3-card-4 w3-bordered w3-small">
  <form action="index.php?id=Information_Adv" method="post">
<tr>
<th class=""style="width:10%;">학년</th><th class="">진로희망</th><th class="">희망사유</th><th class="">입력</th>
</tr>
<tr>
<td style="widtd:10%;"><?php echo $data_user_grade?></td>
<td><input class="w3-input" type="text" name="user_carrer_hope" value="<?php echo $user_carrer_hope;?>"></td>
<td><input class="w3-input" type="text" name="user_carrer_reason" value="<?php echo $user_carrer_reason;?>"></td>
<td><input name="user_name" value="<?php echo $_POST[user_name];?>" type="hidden"><input class="w3-button w3-theme-l3" type="submit" name="입력" value="입력"></td>
</tr>
</form>
</table>

<h6><strong>행동특성 및 종합의견</strong></h6>
<table class="w3-centered w3-card-4 w3-bordered w3-small">
  <form action="index.php?id=Information_Adv" method="post">
<tr>
<th class=""style="width:10%;">학년</th><th class="">담임의견란</th><th class="">입력</th>
</tr>
<tr>
<td style="widtd:10%;"><?php echo $data_user_grade?></td>
<td><textarea class="w3-input" name="user_opinion"><?php echo $user_opinion;?></textarea></td>
<td><input name="user_name" value="<?php echo $_POST[user_name];?>" type="hidden"><input class="w3-button w3-theme-l3" type="submit" name="입력" value="입력"></td>
</tr>
</form>
</table>

<br>
</body>
</html>

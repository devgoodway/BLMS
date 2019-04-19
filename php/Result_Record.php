<?php
// 권한 관리
require_once 'php/user_chk.php';

//인적사항 호출
$i = 0;
$result_users = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_email = '".$_POST[user_email]."' ORDER BY user_grade DESC");
while( $row_users = mysqli_fetch_assoc($result_users)){
  $user_graduation[$i] = $row_users[user_graduation];
  $user_season[$i] = $row_users[user_season];
  $user_email[$i] = $row_users[user_email];
  $user_grade[$i] = $row_users[user_grade];
  $user_name[$i] = $row_users[user_name];
  $user_adv[$i] = $row_users[user_adv];
  $user_gender[$i] = $row_users[user_gender];
  $user_rrn[$i] = $row_users[user_rrn];
  $user_adr[$i] = $row_users[user_adr];
  $user_fname[$i] = $row_users[user_fname];
  $user_fbirth[$i] = $row_users[user_fbirth];
  $user_mname[$i] = $row_users[user_mname];
  $user_mbirth[$i] = $row_users[user_mbirth];
  $user_pi_remarks[$i] = $row_users[user_pi_remarks];
  $user_history[$i] = $row_users[user_history];
  $user_ht_remarks[$i] = $row_users[user_ht_remarks];
  $user_days[$i] = $row_users[user_days];
  $user_ab_dis[$i] = $row_users[user_ab_dis];
  $user_ab_unauth[$i] = $row_users[user_ab_unauth];
  $user_ab_etc[$i] = $row_users[user_ab_etc];
  $user_late_dis[$i] = $row_users[user_late_dis];
  $user_late_unauth[$i] = $row_users[user_late_unauth];
  $user_late_etc[$i] = $row_users[user_late_etc];
  $user_early_dis[$i] = $row_users[user_early_dis];
  $user_early_unauth[$i] = $row_users[user_early_unauth];
  $user_early_etc[$i] = $row_users[user_early_etc];
  $user_skip_dis[$i] = $row_users[user_skip_dis];
  $user_skip_unauth[$i] = $row_users[user_skip_unauth];
  $user_skip_etc[$i] = $row_users[user_skip_etc];
  $user_att_remarks[$i] = $row_users[user_att_remarks];
  $user_carrer_hope[$i] = $row_users[user_carrer_hope];
  $user_carrer_reason[$i] = $row_users[user_carrer_reason];
  $user_opinion[$i] = $row_users[user_opinion];
  $i++;
}?>

<head>
<style>
/* @page a4sheet { size: 21.0cm 29.7cm }
.a4 { page: a4sheet; page-break-after: always } */

table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 5px;
}

th {
    background-color: #f2f2f2;
}

#content {
    display: table;
}

#pageFooter {
    display: table-footer-group;
}
#pageFooter:after {
    counter-increment: page;
    content:"Page " counter(page);
    left: 0;
    top: 100%;
    white-space: nowrap;
    z-index: 20px;
    -moz-border-radius: 5px;
    -moz-box-shadow: 0px 0px 4px #222;
    background-image: -moz-linear-gradient(top, #eeeeee, #cccccc);
    background-image: -moz-linear-gradient(top, #eeeeee, #cccccc);
  }

</style>
</head>
<body>
  <table id="result-menu" class="w3-card-4 w3-bordered w3-centered w3-hide-small">
    <td style="width:90%;">
    <form action="index.php?id=Result_Record" method="post">
      <input type="hidden" name="view_area" value="10">
      <input type="hidden" name="view_area_limit" value="13">
      <select class="w3-select" name="user_email" onchange="form.submit()"><option></option>
        <?php
        $result_users = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".substr($_COOKIE['Season'],0,4)."' AND user_grade LIKE '%학년' ORDER BY user_grade, user_name");
        while($row_users = mysqli_fetch_assoc($result_users)){
          echo '<option value="'.$row_users[user_email].'">'.$row_users[user_grade].'/'.$row_users[user_name].'</option>';
        }?>
      </select>
    </form></td>
    <td style="width:10%">
    <form action="print.php?id=Result_Record" method="post" target="_blank">
      <input type="hidden" name="view_area" value="<?php echo $_POST[view_area];?>">
      <input type="hidden" name="view_area_limit" value="<?php echo $_POST[view_area_limit];?>">
      <input type="hidden" name="user_name" value="<?php echo $user_name[0];?>">
      <input type="hidden" name="user_email" value="<?php echo $_POST['user_email'];?>">
      <input type="hidden" name="submit" value="<?php echo $_POST[submit];?>">
      <input class="w3-button w3-theme-l4" type="submit" name="인쇄" value="인쇄"></form>
  </td>
  <td style="width:10%">
    <form action="index.php?id=Result_Record" method="post">
      <input type="hidden" name="user_email" value="<?php echo $_POST['user_email'];?>">
      <input type="hidden" name="view_area" value="10">
      <input type="hidden" name="view_area_limit" value="13">
      <input class="w3-button w3-theme-l4" type="submit" name="submit" value="전체보기"></form>
  </td>
  <td style="width:10%">
    <form action="index.php?id=Result_Record" method="post">
      <input type="hidden" name="user_email" value="<?php echo $_POST['user_email'];?>">
      <input type="hidden" name="view_area" value="10">
      <input type="hidden" name="view_area_limit" value="13">
      <input type="hidden" name="use_submit" value="F">
      <input class="w3-button w3-theme-l4" type="submit" name="submit" value="전체보기(제출용)"></form>
  </td>
  <td style="width:10%">
    <form action="index.php?id=Result_Record" method="post">
      <input type="hidden" name="user_email" value="<?php echo $_POST['user_email'];?>">
      <input type="hidden" name="view_area" value="<?php if($user_grade[0]=='12학년'&&$user_season[0]=='2018')$str_grade = explode('학년',$user_grade[0]); else $str_grade = explode('학년',$user_grade[1]); echo $str_grade[0];?>">
      <input type="hidden" name="view_area_limit" value="<?php $str_grade[0] = $str_grade[0]+1;echo $str_grade[0];?>">
      <input class="w3-button w3-theme-l4" type="submit" name="submit" value="현재보기"></form>
</td>
</table>
<h4><strong><center>학교생활세부사항기록부(학교생활기록부Ⅱ)</center></strong></h4>
  <div style="width: 75%; float: left;">
    <table  class="w3-centered w3-small">
  <tbody>
    <tr>
      <th style="width:30%;">졸업 대장 번호</th>
      <?php echo '<td colspan="2">'.$user_graduation[0].'</td>'; ?>
    </tr>
    <tr>
      <th>학년/구분</th>
      <th>학번</th>
      <th>담임 성명</th>
    </tr>
    <?php
    $result_users = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_email = '".$_POST[user_email]."' ORDER BY user_grade");
    while($row_users = mysqli_fetch_assoc($result_users)){
      $user_number = explode('@',$row_users[user_email]);
      echo '<tr><td>'.$row_users[user_grade].'</td>';
      echo '<td>'.$user_number[0].'</td>';
      echo '<td>'.$row_users[user_adv].'</td></tr>';
    }?>
  </tbody>

    </table>
  </div>
  <div style="width: 20%; height:145px; float: right;">
    <img src="photo/<?php echo $user_number[0];?>.jpg" width="100%"; height="100%" alt="">
  </div><br><br><br><br><br><br><br>
  <h6><strong>1.인적사항</strong></h6>
  <table class="w3-centered w3-small">
    <tbody>
    <tr>
      <th style="width:20%;">학&emsp;생</th>
      <td style="text-align:left;">
        성명: <?php echo $user_name[0];?> 성별: <?php echo $user_gender[0];?> 주민등록번호 : <?php echo $user_rrn[0];?><br>
        주소: <?php echo $user_adr[0];?>
      </td>
    </tr>
    <tr>
      <th style="width:20%;">가족 &emsp;부<br>상황 &emsp;모</div>
      </th>
      <td style="text-align:left;">
        성명: <?php echo $user_fname[0];?> 생년월일: <?php echo $user_fbirth[0];?><br>
        성명: <?php echo $user_mname[0];?> 생년월일: <?php echo $user_mbirth[0];?>
      </td>
    </tr>
    <tr>
      <th style="width:20%;">특기사항</th><td style="text-align:left;"><?php echo $user_pi_remarks[0];?></td>
    </tr>
    </tbody>
  </table>
  <h6><strong>2.학적사항</strong></h6>
<table class="w3-centered w3-small">
  <tr>
    <td style="text-align:left;"colspan="2">
        <?php echo $user_history[0];?>
    </td>
  </tr>
  <tr>
    <th style="width:20%;">특기사항</th>
    <td>
        <?php echo $user_ht_remarks[0];?>
    </td>
  </tr>
</table>

<h6><strong>3.출결사항</strong></h6>
<table class="w3-centered w3-small">
<tr>
  <th rowspan="2">학년</th><th rowspan="2">수업일수</th><th colspan="3">결석일수</th><th colspan="3">지각</th><th colspan="3">조퇴</th><th colspan="3">결과</th><th rowspan="2">특기사항</th>
</tr>
<tr>
<th>질병</th><th>무단</th><th>기타</th><th>질병</th><th>무단</th><th>기타</th><th>질병</th><th>무단</th><th>기타</th><th>질병</th><th>무단</th><th>기타</th>
</tr>
<?php
for($j=$i;$j>0;$j--){
  echo '<tr><td>'.$user_grade[$j-1].'</td>';
  echo '<td>'.$user_days[$j-1].'</td>';
  echo '<td>'.$user_ab_dis[$j-1].'</td>';
  echo '<td>'.$user_ab_unauth[$j-1].'</td>';
  echo '<td>'.$user_ab_etc[$j-1].'</td>';
  echo '<td>'.$user_late_dis[$j-1].'</td>';
  echo '<td>'.$user_late_unauth[$j-1].'</td>';
  echo '<td>'.$user_late_etc[$j-1].'</td>';
  echo '<td>'.$user_early_dis[$j-1].'</td>';
  echo '<td>'.$user_early_unauth[$j-1].'</td>';
  echo '<td>'.$user_early_etc[$j-1].'</td>';
  echo '<td>'.$user_skip_dis[$j-1].'</td>';
  echo '<td>'.$user_skip_unauth[$j-1].'</td>';
  echo '<td>'.$user_skip_etc[$j-1].'</td>';
  echo '<td>'.$user_att_remarks[$j-1].'</td></tr>';
}?>
</table>

<h6><strong>4.수상경력</strong></h6>
<table class="w3-centered w3-small">
<tr>
<th style="width:40%;">수상명</th><th>등위</th><th>수상년월일</th><th>수여기관</th><th>참가대상</th>
</tr>
<?php
$result_awards = mysqli_query($conn, "SELECT * FROM bs_awards WHERE awards_email = '".$_POST[user_email]."' ORDER BY awards_id");
while($row_awards = mysqli_fetch_assoc($result_awards)){
  echo '<tr><td style="width:40%;">'.$row_awards[awards_award].'</td>';
  echo '<td>'.$row_awards[awards_rank].'</td>';
  echo '<td>'.$row_awards[awards_date].'</td>';
  echo '<td>'.$row_awards[awards_agency].'</td>';
  echo '<td>'.$row_awards[awards_object].'</td></tr>';
}?>
</table>

<h6><strong>5. 자격증 및 인증 취득상황</strong></h6>
<table class="w3-centered w3-small">
<tr>
<th style="width:10%;">구분</th><th>명칭 또는 종류</th><th>번호 또는 내용</th><th>취득연월일</th><th>발급기관</th>
</tr>
<?php
$result_certificate = mysqli_query($conn, "SELECT * FROM bs_certificate WHERE certificate_email = '".$_POST[user_email]."' ORDER BY certificate_id");
while($row_certificate = mysqli_fetch_assoc($result_certificate)){
  echo '<tr><td style="width:10%;">'.$row_certificate[certificate_division].'</td>';
  echo '<td>'.$row_certificate[certificate_kinds].'</td>';
  echo '<td>'.$row_certificate[certificate_number].'</td>';
  echo '<td>'.$row_certificate[certificate_date].'</td>';
  echo '<td>'.$row_certificate[certificate_agency].'</td></tr>';
}?>
</table>

<h6><strong>6. 진로희망사항</strong></h6>
<table class="w3-centered w3-small">
<tr>
<th style="width:10%;">학년</th><th style="width:30%;">진로희망</th><th>희망사유</th>
</tr>
<?php
for($k=$i;$k>0;$k--){
  echo '<tr><td style="width:10%";>'.$user_grade[$k-1].'</td>';
  echo '<td style="width:30%;">'.$user_carrer_hope[$k-1].'</td>';
  echo '<td style="text-align:left;">'.$user_carrer_reason[$k-1].'</td></tr>';
}?>
</table>

<h6><strong>7. 창의적 체험활동 상황</strong></h6>
<table class="w3-centered w3-small">
<tr>
<th style="width:10%;" rowspan="2">학년</th><th colspan="3">창의적 체험활동 상황</th>
</tr>
<tr>
<th>영역</th><th>시간</th><th style="width:60%">특기사항</th>
</tr>
<?php
//10학년
for($grade_cnt=$_POST[view_area];$grade_cnt<$_POST[view_area_limit];$grade_cnt++)
if(in_array($grade_cnt.'학년',$user_grade)){
//자율활동
$sum=0;$text = '';
$result_common = mysqli_query($conn, "SELECT * FROM bs_common WHERE common_grade = '".$grade_cnt."학년' AND common_area = '자율활동' AND common_email = '".$_POST[user_email]."' ORDER BY common_id");
echo '<tr><td rowspan="4">'.$grade_cnt.'학년</td><td>자율활동</td>';
while($row_common = mysqli_fetch_assoc($result_common)){
  if(($row_common[common_time]!=NULL)&&($row_common[common_text]!=NULL)){
  $text = $text.$row_common[common_text]."<br>";
  $sum = $sum + $row_common[common_time];}
}
echo '<td>'.$sum.'</td><td style="text-align:left;">'.$text.'</td></tr>';

//동아리활동
$sum=0;$text = '';
$result_common = mysqli_query($conn, "SELECT * FROM bs_common WHERE common_grade = '".$grade_cnt."학년' AND common_area = '동아리활동' AND common_email = '".$_POST[user_email]."' ORDER BY common_id");
echo '<tr><td>동아리활동</td>';
while($row_common = mysqli_fetch_assoc($result_common)){
  if(($row_common[common_time]!=NULL)&&($row_common[common_text]!=NULL)){
  $text = $text.$row_common[common_text]."<br>";
  $sum = $sum + $row_common[common_time];}
}
echo '<td>'.$sum.'</td><td style="text-align:left;">'.$text.'</td></tr>';

//봉사활동
$sum=0;$text = '';
$result_common = mysqli_query($conn, "SELECT * FROM bs_common WHERE common_grade = '".$grade_cnt."학년' AND common_area = '봉사활동' AND common_email = '".$_POST[user_email]."' ORDER BY common_id");
echo '<tr><td>봉사활동</td>';
while($row_common = mysqli_fetch_assoc($result_common)){
  if(($row_common[common_time]!=NULL)&&($row_common[common_text]!=NULL)){
  $text = $text.$row_common[common_text]."<br>";
  $sum = $sum + $row_common[common_time];}
}
if($sum==0){
echo '<td style="background-color:gray"></td><td style="text-align:left;">'.$text.'</td></tr>';
}else{
echo '<td>'.$sum.'</td><td style="text-align:left;">'.$text.'</td></tr>';}

//진로활동
$sum=0;$text = '';
$result_common = mysqli_query($conn, "SELECT * FROM bs_common WHERE common_grade = '".$grade_cnt."학년' AND common_area = '진로활동' AND common_email = '".$_POST[user_email]."' ORDER BY common_id");
echo '<tr><td>진로활동</td>';
while($row_common = mysqli_fetch_assoc($result_common)){
  if(($row_common[common_time]!=NULL)&&($row_common[common_text]!=NULL)){
  $text = $text.$row_common[common_text]."<br>";
  $sum = $sum + $row_common[common_time];}
}
echo '<td>'.$sum.'</td><td style="text-align:left;">'.$text.'</td></tr>';
}?>

</table>
<br>
<table class="w3-centered w3-small">
<tr>
<th style="width:10%;" rowspan="2">학년</th><th colspan="5">봉사활동실적</th>
</tr>
<tr>
<th>일자 또는 기간</th><th>장소 또는 주관기관명</th><th style="width:30%;">활동내용</th><th>시간</th><th>누계시간</th>
</tr>
<?php
$sum = 0;
$result_volunteer = mysqli_query($conn, "SELECT * FROM bs_volunteer WHERE volunteer_email = '".$_POST[user_email]."' ORDER BY volunteer_grade,volunteer_date,volunteer_id");
while($row_volunteer = mysqli_fetch_assoc($result_volunteer)){
  $sum = $sum + $row_volunteer[volunteer_time];
  echo '<tr><td>'.$row_volunteer[volunteer_grade].'</td>';
  echo '<td>'.$row_volunteer[volunteer_date].'</td>';
  echo '<td>'.$row_volunteer[volunteer_place].'</td>';
  echo '<td>'.$row_volunteer[volunteer_activity].'</td>';
  echo '<td>'.$row_volunteer[volunteer_time].'</td>';
  echo '<td>'.$sum.'</td></tr>';
}?>
</table>

<h6><strong>8. 교과학습발달상황</strong></h6>
<?php
//10,11,12학년
//교과
for($grade_cnt=$_POST[view_area];$grade_cnt<$_POST[view_area_limit];$grade_cnt++)
if(in_array($grade_cnt.'학년',$user_grade)){
  if($grade_cnt!=$_POST[view_area]){echo '<br>';}
echo '<h7>['.$grade_cnt.'학년]</h7>';
echo '<table class="w3-centered w3-small"><tr><th style="width:15%;" rowspan="2">교과</th><th style="width:15%;" rowspan="2">과목</th><th colspan="2">1쿼터</th><th colspan="2">2쿼터</th><th colspan="2">3쿼터</th><th colspan="2">4쿼터</th><th rowspan="2">비고</th></tr><tr><th>단위수</th><th>성취도</th><th>단위수</th><th>성취도</th><th>단위수</th><th>성취도</th><th>단위수</th><th>성취도</th></tr>';
$result_curriculum = mysqli_query($conn, "SELECT curriculum_cur FROM bs_curriculum WHERE NOT curriculum_cur='역량' AND NOT curriculum_cur='철학' GROUP BY curriculum_cur ORDER BY curriculum_id");
  while($row_curriculum = mysqli_fetch_assoc($result_curriculum)){//교과 가져오기
    if($_POST[use_submit]){//F학점을 제외한 결과 출력
    $result_apply = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub LIKE '".$row_curriculum[curriculum_cur]."%' AND NOT apply_rating = 'F' AND NOT apply_rating = '' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' GROUP BY apply_sub ORDER BY apply_sub");
    }else{
    $result_apply = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub LIKE '".$row_curriculum[curriculum_cur]."%' AND NOT apply_rating = '' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' GROUP BY apply_sub ORDER BY apply_sub");}
    while($row_apply = mysqli_fetch_assoc($result_apply)){//과목 가져오기
      $curriculum_sub = explode('/',$row_apply[apply_sub]);
      echo '<tr><td>'.$row_curriculum[curriculum_cur].'</td>';
      echo '<td>'.$curriculum_sub[1].'</td>';
      if($_POST[use_submit]){//F학점을 제외한 결과 출력
      $result_apply_data = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub = '".$row_apply[apply_sub]."' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' AND NOT apply_rating = 'F'  AND NOT apply_rating = '' ORDER BY apply_season");
      }else{
      $result_apply_data = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub = '".$row_apply[apply_sub]."' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' AND NOT apply_rating = '' ORDER BY apply_season");}$l = 0;
      unset($apply_data_season);unset($apply_data_point);unset($apply_data_rating);
      while($row_apply_data = mysqli_fetch_assoc($result_apply_data)){//쿼터별 단위수 / 성취도 가져오기
        if($apply_data_season[$l-1]!=$row_apply_data[apply_season]){
        $apply_data_season[$l] = $row_apply_data[apply_season];
        $apply_data_point[$l] = $row_apply_data[apply_point];
        $apply_data_rating[$l] = $row_apply_data[apply_rating];
        $l++;}
        else{
        $apply_data_point[$l-1] = $apply_data_point[$l-1] + $row_apply_data[apply_point];
        if(strnatcmp($apply_data_rating[$l-1],$row_apply_data[apply_rating])<=0){
          $apply_data_rating[$l-1] = $row_apply_data[apply_rating];}
        }
      }
      for($k=1;$k<5;$k++){
      $chk = 0; $season = $k.'쿼터';
      for($i=0;$i<$l;$i++){//쿼터별로 배치하기
        if(strpos($apply_data_season[$i],$season) !== false){
          echo '<td>'.$apply_data_point[$i].'</td><td>'.$apply_data_rating[$i].'</td>';
          $chk++;
        }
      }
      if($chk == 0){echo '<td></td><td></td>';}
    }
      echo '<td></td></tr>';//비고
    }
  }
  echo '</table>';
//세부능력 및 특기사항
  echo '<br><table class="w3-centered w3-small"><tr><th style="width:20%;">과목</th><th>세부능력 및 특기사항</th></tr>';
  $result_curriculum = mysqli_query($conn, "SELECT curriculum_cur FROM bs_curriculum WHERE NOT curriculum_cur='역량' AND NOT curriculum_cur='철학' GROUP BY curriculum_cur ORDER BY curriculum_id");
    while($row_curriculum = mysqli_fetch_assoc($result_curriculum)){//교과 가져오기
      if($_POST[use_submit]){//F학점을 제외한 결과 출력
      $result_apply = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub LIKE '".$row_curriculum[curriculum_cur]."%' AND NOT apply_rating = ''  AND NOT apply_rating = 'F' AND NOT apply_mento_evl = '' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' GROUP BY apply_sub ORDER BY apply_sub");
      }else{
      $result_apply = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub LIKE '".$row_curriculum[curriculum_cur]."%' AND NOT apply_rating = '' AND NOT apply_mento_evl = '' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' GROUP BY apply_sub ORDER BY apply_sub");}
      while($row_apply = mysqli_fetch_assoc($result_apply)){//과목 가져오기
        $curriculum_sub = explode('/',$row_apply[apply_sub]);
        echo '<tr><td>'.$curriculum_sub[1].'</td>';
        if($_POST[use_submit]){
        $result_apply_data = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub = '".$row_apply[apply_sub]."' AND NOT apply_rating = 'F' AND NOT apply_rating = '' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' ORDER BY apply_season");$apply_data_mento_evl = '';
        }else{
        $result_apply_data = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub = '".$row_apply[apply_sub]."' AND NOT apply_rating = '' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' ORDER BY apply_season");$apply_data_mento_evl = '';}
        while($row_apply_data = mysqli_fetch_assoc($result_apply_data)){
          //멘토평가 가져오기
          if(substr($row_apply_data[apply_season],0,4)<2019){
            $apply_data_mento_evl = $apply_data_mento_evl.' '.$row_apply_data[apply_mento_evl];}
          else{
            $apply_data_mento_evl = $row_apply_data[apply_mento_evl];}
          }
          echo '<td style="text-align:left;">'.$apply_data_mento_evl.'</td>';
        }
        echo '</tr>';
      }
  echo '</table><br>';
//교양교과
  echo '<h7><교양교과></h7>';
  echo '<table class="w3-centered w3-small"><tr><th style="width:20%;" rowspan="2">교과</th><th rowspan="2">과목</th><th colspan="2">1쿼터</th><th colspan="2">2쿼터</th><th colspan="2">3쿼터</th><th colspan="2">4쿼터</th><th rowspan="2">비고</th></tr><tr><th>단위수</th><th>성취도</th><th>단위수</th><th>성취도</th><th>단위수</th><th>성취도</th><th>단위수</th><th>성취도</th></tr>';
  $result_curriculum = mysqli_query($conn, "SELECT curriculum_cur FROM bs_curriculum WHERE curriculum_cur='역량' OR curriculum_cur='철학' GROUP BY curriculum_cur ORDER BY curriculum_id");
    while($row_curriculum = mysqli_fetch_assoc($result_curriculum)){//교과 가져오기
      if($_POST[use_submit]){//F학점을 제외한 결과 출력
      $result_apply = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub LIKE '".$row_curriculum[curriculum_cur]."%' AND NOT apply_rating = 'F' AND NOT apply_rating = '' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' GROUP BY apply_sub ORDER BY apply_sub");
      }else{
      $result_apply = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub LIKE '".$row_curriculum[curriculum_cur]."%' AND NOT apply_rating = '' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' GROUP BY apply_sub ORDER BY apply_sub");}
      while($row_apply = mysqli_fetch_assoc($result_apply)){//과목 가져오기
        $curriculum_sub = explode('/',$row_apply[apply_sub]);
        echo '<tr><td>'.$row_curriculum[curriculum_cur].'</td>';
        echo '<td>'.$curriculum_sub[1].'</td>';
        if($_POST[use_submit]){//F학점을 제외한 결과 출력
        $result_apply_data = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub = '".$row_apply[apply_sub]."' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' AND NOT apply_rating = 'F'  AND NOT apply_rating = '' ORDER BY apply_season");
        }else{
        $result_apply_data = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub = '".$row_apply[apply_sub]."' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' AND NOT apply_rating = '' ORDER BY apply_season");}$l = 0;unset($apply_data_season);unset($apply_data_point);unset($apply_data_rating);
        while($row_apply_data = mysqli_fetch_assoc($result_apply_data)){//쿼터별 단위수 / 성취도 가져오기
          $apply_data_season[$l] = $row_apply_data[apply_season];
          $apply_data_point[$l] = $row_apply_data[apply_point];
          $apply_data_rating[$l] = $row_apply_data[apply_rating];
          $l++;
        }
        for($k=1;$k<5;$k++){
        $chk = 0; $season = $k.'쿼터';
        for($i=0;$i<$l;$i++){//쿼터별로 배치하기
          if(strpos($apply_data_season[$i],$season) !== false){
            echo '<td>'.$apply_data_point[$i].'</td><td>'.$apply_data_rating[$i].'</td>';
            $chk++;
          }
        }
        if($chk == 0){echo '<td></td><td></td>';}
      }
        echo '<td></td></tr>';
      }
    }
    echo '</table>';
  //세부능력 및 특기사항
    echo '<br><table class="w3-centered w3-small"><tr><th style="width:20%;">과목</th><th>세부능력 및 특기사항</th></tr>';
    $result_curriculum = mysqli_query($conn, "SELECT curriculum_cur FROM bs_curriculum WHERE curriculum_cur='역량' OR curriculum_cur='철학' GROUP BY curriculum_cur ORDER BY curriculum_id");
      while($row_curriculum = mysqli_fetch_assoc($result_curriculum)){//교과 가져오기
        if($_POST[use_submit]){
        $result_apply = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub LIKE '".$row_curriculum[curriculum_cur]."%' AND NOT apply_rating = 'F' AND NOT apply_rating = '' AND NOT apply_mento_evl = '' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' GROUP BY apply_sub ORDER BY apply_sub");
        }else{
        $result_apply = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub LIKE '".$row_curriculum[curriculum_cur]."%' AND NOT apply_rating = '' AND NOT apply_mento_evl = '' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' GROUP BY apply_sub ORDER BY apply_sub");}
        while($row_apply = mysqli_fetch_assoc($result_apply)){//과목 가져오기
          $curriculum_sub = explode('/',$row_apply[apply_sub]);
          echo '<tr><td>'.$curriculum_sub[1].'</td>';
          if($_POST[use_submit]){
          $result_apply_data = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub = '".$row_apply[apply_sub]."' AND NOT apply_rating = 'F' AND NOT apply_rating = '' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' ORDER BY apply_season");
          }else{
          $result_apply_data = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_sub = '".$row_apply[apply_sub]."' AND NOT apply_rating = '' AND apply_email = '".$user_email[0]."' AND apply_grade = '".$grade_cnt."학년' ORDER BY apply_season");}$apply_data_mento_evl = '';
          while($row_apply_data = mysqli_fetch_assoc($result_apply_data)){
            //멘토평가 가져오기
            if(substr($row_apply_data[apply_season],0,4)<2019){
              $apply_data_mento_evl = $apply_data_mento_evl.' '.$row_apply_data[apply_mento_evl];}
            else{
              $apply_data_mento_evl = $row_apply_data[apply_mento_evl];}}
            echo '<td style="text-align:left;">'.$apply_data_mento_evl.'</td>';
          }
          echo '</tr>';
        }
    echo '</table>';
}
 ?>

 <h6><strong>9. 독서활동상황</strong></h6>
 <table class="w3-centered w3-small">
 <tr>
 <th style="width:10%;">학년</th><th style="width:15%;">과목 또는 영역</th><th style="width:75%;">독서활동 상황</th>
 </tr>
 <?php
 if($_POST[submit]=='현재보기'){
 $result_reading = mysqli_query($conn, "SELECT * FROM bs_reading WHERE reading_grade = '".$_POST[view_area]."학년' AND reading_email = '".$_POST[user_email]."' ORDER BY reading_grade,reading_id");
 }else{
   $result_reading = mysqli_query($conn, "SELECT * FROM bs_reading WHERE reading_email = '".$_POST[user_email]."' ORDER BY reading_grade,reading_id");}
 while($row_reading = mysqli_fetch_assoc($result_reading)){
   echo '<tr><td>'.$row_reading[reading_grade].'</td>';
   echo '<td>'.$row_reading[reading_sub].'</td>';
   echo '<td style="text-align:left;">'.$row_reading[reading_text].'</td></tr>';
 }?>
 </table>

<h6><strong>10. 행동특성 및 종합의견</strong></h6>
<table class="w3-centered w3-small">
<tr>
<th style="width:10%;">학년</th><th>담임의견란</th>
</tr>
<tr>
<?php
if($_POST[submit]=='현재보기'){
  for($i=0;$i<3;$i++){
    $temp = explode('학년',$user_grade[$i]);
    if($temp[0]==$_POST[view_area])
    echo '<tr><td>'.$user_grade[$i].'</td><td style="text-align:left;">'.$user_opinion[$i].'</td></tr>';}}
else{
for($grade_cnt=$_POST[view_area_limit];$grade_cnt>$_POST[view_area];$grade_cnt--)
  if($user_grade[$grade_cnt-11])
  echo '<tr><td>'.$user_grade[$grade_cnt-11].'</td><td style="text-align:left;">'.$user_opinion[$grade_cnt-11].'</td></tr>';}?>
</table>
<br>
<h2><strong><center>별 무 리 학 교 장</center></strong></h2>
<br>
</body>

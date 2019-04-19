<?php
// 권한 관리
require_once 'php/user_chk.php';

//Season을 가장 마지막으로 설정
$result_season = mysqli_query($conn, "SELECT admin_season FROM bs_admin ORDER BY admin_season ASC");//내림차순으로 정렬
while( $row_season = mysqli_fetch_assoc($result_season))
{$_COOKIE['Season'] = $row_season['admin_season'];}

//교과명 특수문자 에러 체크
$special_pattern = "/[_\/()[]]/";

if(preg_match($special_pattern,$_POST['plan_class'])){
$msg = "교과명에 _/() 특수문자는 사용할 수 없습니다.";
echo("<script>alert('$msg');history.back();</script>");
exit;
}

//쿼터를 유지하기 위한 코드
$season_chk = '';
if($season_chk==''){
$season_chk = $_COOKIE['Season'];}

//시즌 유지
if(empty($_POST['plan_season'])==false){
$_COOKIE['Season'] = $_POST['plan_season'];}

//교육과정 호출
$i=0;
$result_curriculum = mysqli_query($conn, "SELECT * FROM bs_curriculum WHERE curriculum_apv = 'Y'");
while( $row_curriculum = mysqli_fetch_assoc($result_curriculum)){
$data_curriculum[$i] = array($row_curriculum['curriculum_id'], $row_curriculum['curriculum_cur'], $row_curriculum['curriculum_sub'], $row_curriculum['curriculum_text']);$i++;
}

if(empty($_POST['plan_name']) === false){
//시간표 DB에 자동입력
for($i=0;$i<=47;$i++){
// 중복 관리(강의실이 중복되어있으면 이전 페이지로 이동)
$plan_dual_key = 0;
$result_plan_dual = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season = '".$_COOKIE[Season]."' AND plan_classroom = '".$_POST['plan_classroom']."'");//강의실과 시간이 같은 파일을 불러온다.
while($row_plan_dual = mysqli_fetch_assoc($result_plan_dual))
{
  if($_POST['plan_classroom']==''||$_POST['plan_classroom']=='없음'){
      $plan_dual_key = 0;}
  else if(strpos($row_plan_dual[plan_time],$_POST['plan_time'.$i.''])!==false){$plan_dual_key = 1;}
}
if($plan_dual_key==1){echo("<script>alert('사용자가 작성하는 사이에 강의실이 중복 되었습니다. 강의실을 다시 선택하여 입력해주세요.');history.back();</script>");exit();}
if($_POST['plan_time'.$i.'']){
$temp_time[$i] = "".$_POST['plan_time'.$i.'']."";}}

//시간을 등록하지 않고 제출한 문서 체크
if(!$temp_time){
$msg = "시간을 입력해주세요!";
echo("<script>alert('$msg');history.back();</script>");
exit();
}

$time = implode("/",$temp_time);
//월화수목 순서로 입력 및 시간표시 간소화
$day = array("월","화","수","목");
for($i=0;$i<=47;$i++){
  if(strrpos($temp_time[$i],'월')!==false){
  if(strlen($temp_time[$i])>4){$day[0] .= substr($temp_time[$i],-2);}
  else{$day[0] .= substr($temp_time[$i],-1);}
  }
  if(strrpos($temp_time[$i],'화')!==false){
  if(strlen($temp_time[$i])>4){$day[1] .= substr($temp_time[$i],-2);}
  else{$day[1] .= substr($temp_time[$i],-1);}
  }
  if(strrpos($temp_time[$i],'수')!==false){
  if(strlen($temp_time[$i])>4){$day[2] .= substr($temp_time[$i],-2);}
  else{$day[2] .= substr($temp_time[$i],-1);}
  }
  if(strrpos($temp_time[$i],'목')!==false){
  if(strlen($temp_time[$i])>4){$day[3] .= substr($temp_time[$i],-2);}
  else{$day[3] .= substr($temp_time[$i],-1);}
  }
}
//요일값이 있을때만 추가
for($j=0;$j<count($day);$j++){
  if(strlen($day[$j])>3){$time_str = $time_str.$day[$j];}}

//결과값을 입력. 중복 제거!!
$result = mysqli_query($conn, "INSERT INTO bs_plan (plan_season,plan_name,plan_email,plan_mentor,plan_apv,plan_sub,plan_class,plan_point,plan_classroom,plan_contents,plan_time,plan_created)
  SELECT '".$_POST['plan_season']."','".$_POST['plan_name']."','".$_POST['plan_email']."','".$_POST['plan_mentor']."','대기중','".$_POST['plan_curriculum']."/".$_POST['plan_subject']."',
  '".addslashes($_POST['plan_class'])."_".$_POST['plan_name']."/".$_POST['plan_mentor']."(".$time_str.")"."[".$_POST['plan_classroom']."]"."','".$_POST['plan_point']."','".$_POST['plan_classroom']."',
'".addslashes($_POST['plan_background'])."%^".$_POST['plan_value']."%^".$_POST['plan_ability']."%^".addslashes($_POST['plan_goal'])."%^".addslashes($_POST['plan_1w'])."%^".addslashes($_POST['plan_2w'])."%^".addslashes($_POST['plan_3w'])."%^".addslashes($_POST['plan_4w'])."%^".addslashes($_POST['plan_5w'])."%^".addslashes($_POST['plan_6w'])."%^".addslashes($_POST['plan_7w'])."%^".addslashes($_POST['plan_8w'])."%^".$_POST['plan_object']."%^".$_POST['plan_level']."%^".addslashes($_POST['plan_book'])."%^".$_POST['plan_self_ratio']."%^".addslashes($_POST['plan_self_evaluation'])."%^".$_POST['plan_mentor_ratio']."%^".addslashes($_POST['plan_mentor_evaluation'])."%^".$_POST['plan_adv_ratio']."%^".addslashes($_POST['plan_adv_evaluation'])."%^".addslashes($_POST['plan_criteria'])."',
'".$time."',now() FROM DUAL WHERE NOT EXISTS (SELECT * FROM bs_plan WHERE plan_season='".$_POST['plan_season']."' AND plan_email='".$_POST['plan_email']."' AND plan_class=
'".$_POST['plan_class']."_".$_POST['plan_name']."/".$_POST['plan_mentor']."(".$time_str.")"."[".$_POST['plan_classroom']."]"."' AND plan_sub='".$_POST['plan_curriculum']."/".$_POST['plan_subject']."')");}

//재사용하고자 하는 학습계획서 내용을 출력
if(empty($_POST['planId']) === false){
$result_plan_update = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_id = '".$_POST['planId']."'");
while( $row_plan = mysqli_fetch_assoc($result_plan_update)){
$data_plan = array($row_plan['plan_id'], $row_plan['plan_name'], $row_plan['plan_email'], $row_plan['plan_mentor'], $row_plan['plan_apv'], $row_plan['plan_sub'], $row_plan['plan_class'], $row_plan['plan_point'], $row_plan['plan_classroom'], $row_plan['plan_contents'], $row_plan['plan_time'], $row_plan['plan_created']);
}

//선택된 학습계획서의 plans_class를 나누어 저장
$plan_class = explode('_',$data_plan[6]);
//선택된 학습계획서의 plan_contents를 배열로 나누어 저장
$contents = explode('%^',$data_plan[9]);
//선택된 학습계획서의 plan_time를 배열로 나누어 저장 & 정렬
$plan_time = explode('/',$data_plan[10]);sort($plan_time);
//선택된 학습계획서의 plan_sub를 배열로 나누어 저장
$sub = explode('/',$data_plan[5]);}

//교과목명이 같은 수업이 있으면 해당 멘토와 교무부장에게 이메일 발송
$result = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season = '".$_POST['plan_season']."' AND plan_sub = '".$_POST['plan_curriculum']."/".$_POST['plan_subject']."'");
if($_POST[before][5]!=$data_plan[5] && mysqli_num_rows($result)>1){//교과명이 달라졌을 경우만
/* 다중 수신자 */
$to = '<YOUR_MASTER2_EMAIL>';
$class = '';
while( $row = mysqli_fetch_assoc($result)){
  $result_plan_mentor = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".substr($_COOKIE['Season'],0,4)."' AND user_name = '".$row['plan_mentor']."'");
    $class .= '<p><a href="'.$root_url.'index.php?id=Result_Plan&planId='.$row[plan_id].'"><h4 style="color:blue">'.$_COOKIE[Season].'  '.$row[plan_class].'</h4></a></p>';
  while( $row_plan_mentor = mysqli_fetch_assoc($result_plan_mentor)){
    $to .= ','.$row_plan_mentor[user_email];
  }
}
// 제목
$subject = '[BLMS] 이미 개설하신 '.$_POST['plan_curriculum']."/".$_POST['plan_subject'].'과(와) 동일한 수업이 개설되었습니다.';

// 메세지
$message = "<h2>".$_POST['plan_curriculum']."/".$_POST['plan_subject']."</h2>".$class;

// HTML 메일을 보내려면, Content-type 헤더를 설정해야 합니다.
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// 추가 헤더
// $headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
// $headers .= 'From: Birthday Reminder <birthday@example.com>' . "\r\n";
// $headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
// $headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";

// 메일 보내기
mail($to, $subject, $message, $headers);}
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


<h4><strong>학습계획서 설계 - <?php echo $_COOKIE[Season]; ?></strong></h4>
<h6><strong>시간표 선택</strong></h6>
<form action="index.php?id=Design_Plan_Reuse&season=<?php echo $season_chk;?>" method="post">
  <input class="w3-input w3-white" type="hidden" name="planId" value="<?php echo $_POST['planId'];?>">
<select class="w3-select" name="classroom" onchange="form.submit()" required>
  <option value="">강의실을 선택하고 아래에서 시간표를 확인하세요.</option>
  <option value="없음">없음</option>
  <?php
  $result_classroom = mysqli_query($conn, "SELECT * FROM bs_classroom ORDER BY classroom_name");
  while( $row_classroom = mysqli_fetch_assoc($result_classroom)){
    echo '<option value="'.$row_classroom[classroom_name].'">'.$row_classroom[classroom_name].'</option>';
  }
  ?>
</select>
</form><br>
<form action="index.php?id=Design_Plan_Reuse&season=<?php echo $season_chk;?>" method="post">
<table class="w3-card-4 w3-bordered w3-centered w3-small">
<tbody>
   <tr>
     <th class="studyplan_table" colspan="4">시간표</th>
     <th class="studyplan_table">강의실</th>
     <th class="studyplan_table">
     <?php
     echo '<input class="w3-theme-light w3-input" style="width:100%;border:0;text-align:center;" type="text" name="plan_classroom" value="'.$_POST[classroom].'" readonly>';
     ?>
     </th>
   </tr>
   <tr>
     <th class="studyplan_table" colspan="2">교시</th>
     <th class="studyplan_table">월</th>
     <th class="studyplan_table">화</th>
     <th class="studyplan_table">수</th>
     <th class="studyplan_table">목</th>
   </tr>
<!-- 시간표 만들기 -->
   <?php
   if(empty($_POST[classroom])===false){
   $i=0;
   $result_plan = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season = '".$_COOKIE[Season]."'");
   while( $row_plan = mysqli_fetch_assoc($result_plan)){
       if((strpos($row_plan['plan_classroom'],$_POST[classroom])!==false)||(strpos($_POST[classroom],$row_plan['plan_classroom'])!==false)){
         $time[$i] = $row_plan[plan_time];
         $mentor[$i] = $row_plan[plan_mentor];
         $class[$i] = $row_plan[plan_class];
         $i++;
       }
     }

   $chk_cnt = 0;//빈칸의 갯수를 새는 변수
   //2교시부터 12교시까지 표시
   for($x = 2; $x <= 12; $x++){
     echo '<tr><th class="studyplan_table" colspan="2">';
     //11,12교시는 저녁활동으로 표시
     if($x>10){echo '저녁활동'.($x-10);}
     else{echo $x;}
     echo '교시</th>';
     //요일과 교시 표시
     for($y = 0; $y <= 3; $y++){
       if($y==0){$day = '월'.$x;}
       elseif($y==1){$day = '화'.$x;}
       elseif($y==2){$day = '수'.$x;}
       else{$day = '목'.$x;}

     echo '<td class="studyplan_table">';
     //선택할 수 없는 칸 지정(목78910(자치,동아리 시간 제외))
     $chk = 0;
     for($i=0;$i<count($time);$i++){
       if(strpos($time[$i],$day)!==false){
         $chk = 1;
         $mentor_chk = $mentor[$i];
         $class_chk = $class[$i];
       }
     }
     if($_POST[classroom]=="없음"){
       if(($_POST[classroom]<>"")&&($chk_cnt <> 23)&&($chk_cnt <> 27)&&($chk_cnt <> 31)&&($chk_cnt <> 35)){
     echo '<input class="w3-input" style="width:100%;" type="checkbox" name="plan_time'.$chk_cnt.'" value="'.$day.'"></td>';}}
     elseif($chk==1){
     echo $class_chk;
     }
     elseif(($_POST[classroom]<>"")&&($chk_cnt <> 23)&&($chk_cnt <> 27)&&($chk_cnt <> 31)&&($chk_cnt <> 35))
     {
     echo '<input class="w3-input" style="width:100%;" type="checkbox" name="plan_time'.$chk_cnt.'" value="'.$day.'"></td>';}
     $chk_cnt++;}
     echo '</tr>';}}
   ?>
</tbody>
</table>
<?php
if(empty($_POST[classroom])){
echo '<h6><strong><font color="red"><center>시간표를 먼저 선택하세요. <br>제출하지 않은 학습계획서 내용은 저장되지 않습니다.</center></font></strong></h6>';}
?>
<h6><strong>학습계획서 작성</strong></h6>
  <table class="w3-card-4 w3-bordered w3-centered w3-small">
    <thead>
  <tr>
    <th class="studyplan_table">작성자</th>
    <td class="studyplan_table"><input class="w3-input" style="width:100%;border:0;text-align:center;" type="text" name="plan_name" value="<?php echo $_COOKIE[Name];?>" readonly></td>
    <td class="studyplan_table" colspan="2"><input class="w3-input" style="width:100%;border:0;text-align:center;" type="text" name="plan_email"  value="<?php echo $_COOKIE[Email];?>" readonly></td>
    <th class="studyplan_table">멘토</th>
    <td class="studyplan_table">
      <select class="w3-white w3-select" name="plan_mentor" required>
        <?php
          $result_user_mentor_grade = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".substr($_COOKIE['Season'],0,4)."'");
          echo '<option value="">';
          while( $row_user = mysqli_fetch_assoc($result_user_mentor_grade)){
            if($row_user['user_grade']==="교사"){
          echo '<option value="'.$row_user['user_name'].'">'.$row_user['user_name'].'['.$row_user['user_email'].']</option>';}
          }
          //시즌 정보를 보낸다.
          echo '<input name="plan_season" value="'.$season_chk.'" type="hidden">';
          ?>
      </select>
    </td>
  </tr>
</thead>
<tbody>
  <tr>
    <th class="studyplan_table">교과/과목</th>
    <td class="studyplan_table">
      <select class="w3-white w3-select" id="plan_curriculum_id" name="plan_curriculum" onchange="changeSub()" required>
            <option value=""></option>
            <?php
              echo '<option value="'.$sub[0].'" selected>'.$sub[0];
            for($i=0;$i<count($data_curriculum);$i++){
              if(in_array($data_curriculum[$i][1],$temp)!=TRUE){
            echo '<option value="'.$data_curriculum[$i][1].'">'.$data_curriculum[$i][1];}
            $temp[$i] = $data_curriculum[$i][1];}?>
          </select></td>
    <td class="studyplan_table">
     <select class="w3-white w3-select" id="plan_subject_id" name="plan_subject" onchange="overlap()" required>
       <option value="<?php echo $sub[1]?>" selected><?php echo $sub[1]?>
      </select>
    </td>
    <script>
    function overlap(){
      var chk = 0;
      var temp='같은 교과/과목으로 개설된 수업\n';
      var sub = document.getElementById('plan_curriculum_id').value + '/' + document.getElementById('plan_subject_id').value;
      var overlap_sub = <?php echo json_encode($overlap_sub)?>;
      var overlap_class = <?php echo json_encode($overlap_class)?>;
      for(var i=0;i<overlap_sub.length;i++){
        if(overlap_sub[i]==sub){
          temp = temp + overlap_class[i] + '\n';chk=1;
        }
      }
      if(chk==0){
        alert('개설된 수업 없음');
      }else
      {
        alert(temp);
      }
    }
    function changeSub(){
      var temp = document.getElementById("plan_curriculum_id").value;
      var data_curriculum = <?php echo json_encode($data_curriculum)?>;
      var x = "<option></option>";
      var sub = '<?php echo $sub[1]?>';
      for(var i=0;i<data_curriculum.length;i++){
        if(data_curriculum[i][1]==temp){
          x = x + '<option value="' + data_curriculum[i][2] + '">' + data_curriculum[i][2];}
      }
      document.getElementById("plan_subject_id").innerHTML = x;
    }
    </script>
    <th class=" studyplan_table">강좌명</th>
    <td class="studyplan_table" colspan="3"><input class="w3-input w3-white" style="width:100%;" type="text" name="plan_class" value="<?php echo $plan_class[0]?>" placeholder='특수문자 _/()을 사용하지 마세요.' required></td>
  </tr>
  <tr>
    <th class=" studyplan_table" rowspan="2">개설배경</th>
    <td class="studyplan_table" colspan="3" rowspan="2"><textarea class="w3-input w3-white" name="plan_background" style="width:100%;height:100;border:1;overflow:visible;text-overflow:ellipsis;" required><?php echo $contents[0]?></textarea></td>
    <th class=" studyplan_table">가치분야</th>
    <td class="studyplan_table">
      <select class="w3-input w3-white" name="plan_value">
        <option value="" <?php if($contents[1]==""){echo selected;}?>></option>
        <option value="공동체"<?php if($contents[1]=="공동체"){echo selected;}?>>공동체</option>
        <option value="제자도"<?php if($contents[1]=="제자도"){echo selected;}?>>제자도</option>
        <option value="샬롬"<?php if($contents[1]=="샬롬"){echo selected;}?>>샬롬</option>
        <option value="소명"<?php if($contents[1]=="소명"){echo selected;}?>>소명</option>
      </select>
    </td>
  </tr>
  <tr>
    <th class=" studyplan_table">역량분야</th>
    <td class="studyplan_table">
      <select class="w3-input w3-white" name="plan_ability">
        <option value="" <?php if($contents[2]==""){echo selected;}?>></option>
        <option value="사고력" <?php if($contents[2]=="사고력"){echo selected;}?>>사고력</option>
        <option value="의사소통" <?php if($contents[2]=="의사소통"){echo selected;}?>>의사소통</option>
        <option value="자기관리" <?php if($contents[1]=="자기관리"){echo selected;}?>>자기관리</option>
      </select>
    </td>
  </tr>
</tbody>
<tbody>
  <tr>
    <th class=" studyplan_table" rowspan="9">학습내용</th>
    <th class=" studyplan_table">총괄목표</th>
    <td class="studyplan_table" colspan="4"><textarea class="w3-input w3-white" name="plan_goal" style="width:100%;height:100;border:1;overflow:visible;text-overflow:ellipsis;" required><?php echo $contents[3]?></textarea></td>
  </tr>
  <?php for($i=1;$i<=8;$i++){
    echo '<tr><th class=" studyplan_table">'.$i.'주차</th><td class="studyplan_table" colspan="4"><textarea class="w3-input w3-white" name="plan_'.$i.'w" style="width:100%;height:100;border:1;overflow:visible;text-overflow:ellipsis;" required>'.$contents[$i+3].'</textarea></td></tr>';}?>
  <tr>
    <th class=" studyplan_table">수강대상</th>
    <td class="studyplan_table">
      <select class="w3-input w3-white" name="plan_object">
        <option value="전학년"<?php if($contents[12]=="전학년"){echo selected;}?>>전학년</option>
        <option value="10학년"<?php if($contents[12]=="10학년"){echo selected;}?>>10학년</option>
        <option value="11학년"<?php if($contents[12]=="11학년"){echo selected;}?>>11학년</option>
        <option value="12학년"<?php if($contents[12]=="12학년"){echo selected;}?>>12학년</option>
        <option value="교사"<?php if($contents[12]=="교사"){echo selected;}?>>교사</option>
        <option value="스터디 그룹"<?php if($contents[12]=="스터디 그룹"){echo selected;}?>>스터디 그룹</option>
        <option value="개인"<?php if($contents[12]=="개인"){echo selected;}?>>개인</option>
      </select></td>
    <th class=" studyplan_table">수준</th>
    <td class="studyplan_table">
      <select class="w3-input w3-white" name="plan_level">
        <option value="하" <?php if($contents[13]=="하"){echo selected;}?>>하</option>
        <option value="중하" <?php if($contents[13]=="중하"){echo selected;}?>>중하</option>
        <option value="중" <?php if($contents[13]=="중"){echo selected;}?>>중</option>
        <option value="중상" <?php if($contents[13]=="중상"){echo selected;}?>>중상</option>
        <option value="상" <?php if($contents[13]=="상"){echo selected;}?>>상</option>
      </select>
    </td>
    <th class=" studyplan_table">신청학점</th>
    <td class="studyplan_table"><input class="w3-input w3-white" value="<?php echo $data_plan[7]?>" style="width:100%;" type="number" name="plan_point" required></td>
  </tr>
  <tr>
    <th class=" studyplan_table">교재</th>
    <td class="studyplan_table" colspan="5"><input class="w3-input w3-white" value="<?php echo $contents[14]?>" style="width:100%;" type="text" name="plan_book" required></td>
  </tr>
  <tr>
    <th class=" studyplan_table" rowspan="4">평가계획</th>
    <th class=" studyplan_table">평가자</th>
    <th class=" studyplan_table">비율</th>
    <th class=" studyplan_table" colspan="3">평가계획</th>
  </tr>
  <tr>
    <th class=" studyplan_table">본인</th>
    <td class="studyplan_table">
      <select class="w3-input w3-white" name="plan_self_ratio">
        <option value="0%" <?php if($contents[15]=="0%"){echo selected;}?>>0%</option>
        <option value="10%" <?php if($contents[15]=="10%"){echo selected;}?>>10%</option>
        <option value="20%" <?php if($contents[15]=="20%"){echo selected;}?>>20%</option>
        <option value="30%" <?php if($contents[15]=="30%"){echo selected;}?>>30%</option>
        <option value="40%" <?php if($contents[15]=="40%"){echo selected;}?>>40%</option>
        <option value="50%" <?php if($contents[15]=="50%"){echo selected;}?>>50%</option>
        <option value="60%" <?php if($contents[15]=="60%"){echo selected;}?>>60%</option>
        <option value="70%" <?php if($contents[15]=="70%"){echo selected;}?>>70%</option>
        <option value="80%" <?php if($contents[15]=="80%"){echo selected;}?>>80%</option>
        <option value="90%" <?php if($contents[15]=="90%"){echo selected;}?>>90%</option>
        <option value="100%" <?php if($contents[15]=="100%"){echo selected;}?>>100%</option>
      </select>
    </td>
    <td class="studyplan_table" colspan="3"><input class="w3-input w3-white" value="<?php echo $contents[16]?>" style="width:100%;" type="text" name="plan_self_evaluation"></td>
  </tr>
  <tr>
    <th class=" studyplan_table">멘토</th>
    <td class="studyplan_table">
      <select class="w3-input w3-white" name="plan_mentor_ratio">
        <option value="100%" <?php if($contents[17]=="100%"){echo selected;}?>>100%</option>
        <option value="90%" <?php if($contents[17]=="90%"){echo selected;}?>>90%</option>
        <option value="80%" <?php if($contents[17]=="80%"){echo selected;}?>>80%</option>
        <option value="70%" <?php if($contents[17]=="70%"){echo selected;}?>>70%</option>
        <option value="60%" <?php if($contents[17]=="60%"){echo selected;}?>>60%</option>
        <option value="50%" <?php if($contents[17]=="50%"){echo selected;}?>>50%</option>
        <option value="40%" <?php if($contents[17]=="40%"){echo selected;}?>>40%</option>
        <option value="30%" <?php if($contents[17]=="30%"){echo selected;}?>>30%</option>
        <option value="20%" <?php if($contents[17]=="20%"){echo selected;}?>>20%</option>
        <option value="10%" <?php if($contents[17]=="10%"){echo selected;}?>>10%</option>
        <option value="0%" <?php if($contents[17]=="0%"){echo selected;}?>>0%</option>
      </select>
    </td>
    <td colspan="3"><input class="w3-input w3-white" value="<?php echo $contents[18]?>" style="width:100%;" type="text" name="plan_mentor_evaluation" required></td>
  </tr>
  <tr>
    <th class=" studyplan_table">어드바이저</th>
    <td class="studyplan_table">
      <select class="w3-input w3-white" name="plan_adv_ratio">
        <option value="0%" <?php if($contents[19]=="0%"){echo selected;}?>>0%</option>
        <option value="10%" <?php if($contents[19]=="10%"){echo selected;}?>>10%</option>
        <option value="20%" <?php if($contents[19]=="20%"){echo selected;}?>>20%</option>
        <option value="30%" <?php if($contents[19]=="30%"){echo selected;}?>>30%</option>
        <option value="40%" <?php if($contents[19]=="40%"){echo selected;}?>>40%</option>
        <option value="50%" <?php if($contents[19]=="50%"){echo selected;}?>>50%</option>
        <option value="60%" <?php if($contents[19]=="60%"){echo selected;}?>>60%</option>
        <option value="70%" <?php if($contents[19]=="70%"){echo selected;}?>>70%</option>
        <option value="80%" <?php if($contents[19]=="80%"){echo selected;}?>>80%</option>
        <option value="90%" <?php if($contents[19]=="90%"){echo selected;}?>>90%</option>
        <option value="100%" <?php if($contents[19]=="100%"){echo selected;}?>>100%</option>
      </select>
    </td>
    <td class="studyplan_table" colspan="3"><input class="w3-input w3-white" value="<?php echo $contents[20]?>" style="width:100%;" type="text" name="plan_adv_evaluation"></td>
  </tr>
  <tr>
    <th class=" studyplan_table">등급기준</th>
    <td class="studyplan_table" colspan="5">
      <select class="w3-white w3-select" name="plan_criteria" required>
        <option><?php echo $contents[21]?></option>
        <option>P – 학점 이수함(Pass) : 100-60 F – 학점 이수 하지 못함(Fail): 59점 이하</option>
        <option>A – 매우 뛰어남(Outstanding) : 100-90 B - 우수함(Competent) : 89-80 C - 최소기준충족(Minimal): 79-60 F – 학점 이수 하지 못함: 59점 이하</option>
      </select>
    </td>
  </tr>
<tr>
   <th class=" studyplan_table" align=rigth colspan="6" >
   <?php
   for ($i=0; $i <sizeof($data_plan) ; $i++) {
     echo '<input type="hidden" name="before[]" value="'.$data_plan[$i].'">';
   }
    ?><input class="w3-input" align=rigth type="submit" name="submit" value="제출">
   </th>
  </tr>
</tbody>
</table>
</form>
<br>

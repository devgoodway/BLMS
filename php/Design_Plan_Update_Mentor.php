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
  $result_plan_dual = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season = '".$_COOKIE[Season]."' AND plan_classroom = '".$_POST['plan_classroom']."' NOT plan_name = '".$_COOKIE[Name]."'");//강의실과 시간이 같은 파일을 불러온다.
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
  if(!$_POST[planTime]){
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

//time_str 만들기
   $time_sort = explode('(',$_POST[planClassName]);
   $time_sort = explode(')',$time_sort[1]);
   $time_str = $time_sort[0];

$sql = "UPDATE bs_plan SET plan_name='".$_POST['plan_name']."',plan_email='".$_POST['plan_email']."',plan_mentor='".$_POST['plan_mentor']."',plan_sub='".$_POST['plan_curriculum']."/".$_POST['plan_subject']."',plan_class='".addslashes($_POST['plan_class'])."_".$_POST['plan_name']."/".$_POST['plan_mentor']."(".$time_str.")"."[".$_POST['plan_classroom']."]"."',plan_point='".$_POST['plan_point']."',plan_classroom='".$_POST['plan_classroom']."',plan_contents='".addslashes($_POST['plan_background'])."%^".$_POST['plan_value']."%^".$_POST['plan_ability']."%^".addslashes($_POST['plan_goal'])."%^".addslashes($_POST['plan_1w'])."%^".addslashes($_POST['plan_2w'])."%^".addslashes($_POST['plan_3w'])."%^".addslashes($_POST['plan_4w'])."%^".addslashes($_POST['plan_5w'])."%^".addslashes($_POST['plan_6w'])."%^".addslashes($_POST['plan_7w'])."%^".addslashes($_POST['plan_8w'])."%^".$_POST['plan_object']."%^".$_POST['plan_level']."%^".addslashes($_POST['plan_book'])."%^".$_POST['plan_self_ratio']."%^".addslashes($_POST['plan_self_evaluation'])."%^".$_POST['plan_mentor_ratio']."%^".addslashes($_POST['plan_mentor_evaluation'])."%^".$_POST['plan_adv_ratio']."%^".addslashes($_POST['plan_adv_evaluation'])."%^".addslashes($_POST['plan_criteria'])."',plan_time='".$_POST['planTime']."' WHERE plan_id='".$_POST['planId']."'";

$result = mysqli_query($conn, $sql);
}

//수정하고자 하는 학습계획서 내용을 출력
if(empty($_POST['planId']) === false){
$result_plan_update = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season = '".$_COOKIE[Season]."'");
while( $row_plan = mysqli_fetch_assoc($result_plan_update)){
if($row_plan['plan_id']===$_POST['planId']){
$data_plan = array($row_plan['plan_id'], $row_plan['plan_name'], $row_plan['plan_email'], $row_plan['plan_mentor'], $row_plan['plan_apv'], $row_plan['plan_sub'], $row_plan['plan_class'], $row_plan['plan_point'], $row_plan['plan_classroom'], $row_plan['plan_contents'], $row_plan['plan_time'], $row_plan['plan_created']);
}

}

//선택된 학습계획서의 plans_class를 나누어 저장
$plan_class = explode('_',$data_plan[6]);
//선택된 학습계획서의 plan_contents를 배열로 나누어 저장
$contents = explode('%^',$data_plan[9]);
$contents_before = explode('%^',$_POST[before][9]);
//선택된 학습계획서의 plan_time를 배열로 나누어 저장 & 정렬
$plan_time = explode('/',$data_plan[10]);sort($plan_time);
//선택된 학습계획서의 plan_sub를 배열로 나누어 저장
$sub = explode('/',$data_plan[5]);}

//apply를 수정하기 위한 코드(apply_sub	apply_class	apply_point	apply_time)
if($_POST[before]){
mysqli_query($conn, "UPDATE bs_apply SET apply_sub='".$data_plan[5]."',apply_class='".$data_plan[6]."',apply_point='".$data_plan[7]."',apply_time='".$data_plan[10]."',apply_created
=now() WHERE apply_season='".$_COOKIE[Season]."' AND apply_class='".$_POST[before][6]."'");

//메일을 보내기 위한 코드
/* 다중 수신자 */
$to = $data_plan[2];
$result_apply_email = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_season='".$_COOKIE[Season]."' AND apply_class='".$data_plan[6]."'");
while( $row_apply_email = mysqli_fetch_assoc($result_apply_email)){
  $to .= ','.$row_apply_email[apply_email];
}

// 제목
$subject = '[BLMS] '.$_POST['plan_curriculum']."/".$_POST['plan_subject'].' ▷ '.$_POST['plan_class'].'이 수정되었습니다.';

// 메세지
$message = '<html><body>
  <p><a href="'.$root_url.'index.php?id=Result_Plan&planId='.$_POST[planId].'"><h2 style="color:blue">'.$_COOKIE[Season].' '.$data_plan[6].'</h2></a></p>
  <p><h2 style="color:blue">※ 변경된 사항</h2></p>';
    if($data_plan[6] != $_POST[before][6]){$message .= '<p><h3>◆ 강좌명</h3></p>'.$_POST[before][6].' ▶ '.$data_plan[6];}
    if($data_plan[5] != $_POST[before][5]){$message .= '<p><h3>◆ 교과/과목</h3></p>'.$_POST[before][5].' ▶ '.$data_plan[5];}
    if($data_plan[9] != $_POST[before][9]){
      $message .= '<p><h3>◆ 내용</h3></p>';
      for ($i=0; $i < sizeof($contents); $i++) {
        if($contents_before[$i] != $contents[$i]){
      $message .= $contents_before[$i].' ▶ '.$contents[$i].'<br>';}
      }
     }
$message .= '</body></html>';

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

//교과목명이 같은 수업이 있으면 해당 멘토와 교무부장에게 이메일 발송
$result_ovl = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season = '".$_POST['plan_season']."' AND plan_sub = '".$_POST['plan_curriculum']."/".$_POST['plan_subject']."'");
if($_POST[before][5]!=$data_plan[5] && mysqli_num_rows($result_ovl)>1){//교과명이 달라졌을 경우만
/* 다중 수신자 */
$to_ovl = '';//<YOUR_MASTER2_EMAIL>
$class_ovl = '';
while( $row_ovl = mysqli_fetch_assoc($result_ovl)){
  $result_plan_mentor = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".substr($_COOKIE['Season'],0,4)."' AND user_name = '".$row_ovl['plan_mentor']."'");
    $class_ovl .= '<p><a href="'.$root_url.'index.php?id=Result_Plan&planId='.$row_ovl[plan_id].'"><h4 style="color:blue">'.$_COOKIE[Season].'  '.$row_ovl[plan_class].'</h4></a></p>';
  while( $row_plan_mentor_ovl = mysqli_fetch_assoc($result_plan_mentor_ovl)){
    $to_ovl .= ','.$row_plan_mentor_ovl[user_email];
  }
}
// 제목
$subject_ovl = '[BLMS]'.$_POST['plan_curriculum']."/".$_POST['plan_subject']." ▶ ".addslashes($_POST['plan_class'])."_".$_POST['plan_name']."/".$_POST['plan_mentor']."(".$time_str.")"."[".$_POST['plan_classroom']."]"."이 수정되었습니다. 수업을 확인하세요.";

// 메세지
$message_ovl = "<h2>".$_POST['plan_curriculum']."/".$_POST['plan_subject']."</h2>".$class_ovl;

// HTML 메일을 보내려면, Content-type 헤더를 설정해야 합니다.
$headers_ovl  = 'MIME-Version: 1.0' . "\r\n";
$headers_ovl .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// 추가 헤더
// $headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
// $headers .= 'From: Birthday Reminder <birthday@example.com>' . "\r\n";
// $headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
// $headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";

// 메일 보내기
mail($to_ovl, $subject_ovl, $message_ovl, $headers_ovl);}
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


<h4><strong>학습계획서 수정(멘토) - <?php echo $_COOKIE[Season]; ?></strong></h4>
<div class="w3-yellow w3-card-4">
<h6><strong><font color="blue"><center>멘토가 직접 학습계획서를 수정 할 수 있는 페이지 입니다. <br>신중하게 수정하시고 수정한 내용은 수강한 학생들에게 이메일로 발송됩니다.<br>시간표와 강의실은 바꿀 수 없으니 승인취소 이후에 수정하시기 바랍니다.</center></font></strong></h6>
</div>
<form action="index.php?id=Design_Plan_Update_Mentor&season=<?php echo $season_chk;?>" method="post">
<table class="w3-card-4 w3-bordered w3-centered w3-small">
 <thead>
   <tr>
     <th style="width:10%">강좌명</th>
     <td class=" studyplan_table" colspan="2">
       <?php
        echo $data_plan[6].'<input type="hidden" name="planClassName" value="'.$data_plan[6].'">';
        ?>
     </td>
     <th style="width:10%">시간표</th>
     <td class=" studyplan_table" colspan="2">
       <?php
        echo $data_plan[10].'<input type="hidden" name="planTime" value="'.$data_plan[10].'">';
        ?>
     </td>
     <th style="width:10%">강의실</th>
     <td class=" studyplan_table" colspan="2">
       <?php
        echo $data_plan[8].'<input type="hidden" name="plan_classroom" value="'.$data_plan[8].'">';
        ?></td>
   </tr>
</table>
<br>
  <table class="w3-card-4 w3-bordered w3-centered w3-small">
    <tbody>
  <tr>
    <th class=" studyplan_table">작성자</th>
    <td class="studyplan_table"><input class="w3-input w3-white" style="width:100%;border:0;text-align:center;" type="text" name="plan_name" value="<?php echo $data_plan[1];?>" readonly></td>
    <td class="studyplan_table" colspan="2"><input class="w3-input w3-white" style="width:100%;border:0;text-align:center;" type="text" name="plan_email" value="<?php echo $data_plan[2];?>" readonly></td>
    <th class=" studyplan_table">멘토</th>
    <td class="studyplan_table">
      <select class="w3-input w3-white" name="plan_mentor" required>
        <?php
          $result_user_mentor_grade = mysqli_query($conn, "SELECT * FROM bs_users  WHERE user_season = '".substr($_COOKIE['Season'],0,4)."'");
          echo '<option value="">';
          while( $row_user = mysqli_fetch_assoc($result_user_mentor_grade)){
            if($row_user['user_grade']==="교사"){
              if($row_user['user_name']===$data_plan[3]){
              echo '<option value="'.$row_user['user_name'].'" selected>'.$row_user['user_name'].'['.$row_user['user_email'].']</option>';
              }
              else{
                echo '<option value="'.$row_user['user_name'].'">'.$row_user['user_name'].'['.$row_user['user_email'].']</option>';}}}
                //시즌 정보를 보낸다.
                echo '<input name="plan_season" value="'.$season_chk.'" type="hidden">';
          ?>
      </select>
    </td>
  </tr>
</tbody>
<tbody>
  <tr>
    <th class=" studyplan_table">교과/과목</th>
    <td class="studyplan_table">
      <select class="w3-input w3-white" id="plan_curriculum_id" name="plan_curriculum" onchange="changeSub()" required>
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
     <input class="w3-theme-l3 w3-input" type="hidden" name="planId" value="<?php echo $_POST[planId]?>">
     <?php
     for ($i=0; $i <sizeof($data_plan) ; $i++) {
       echo '<input type="hidden" name="before[]" value="'.$data_plan[$i].'">';
     }
      ?>
     <input class="w3-input" align=rigth type="submit" name="submit" value="제출">
   </th>
</tr>
</tbody>
</table>
</form>
<br>

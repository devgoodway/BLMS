<?php
// 권한 관리
require_once 'php/user_chk.php';
//공통활동 등록
if(empty($_POST[common_sub])==false){//공통활동 입력이 있는지 확인
  $result_common = mysqli_query($conn, "SELECT * FROM bs_common WHERE common_sub = '".$_POST[common_sub]."' AND common_season='".substr($_COOKIE['Season'],0,4)."'");
  $row_common = mysqli_fetch_assoc($result_common);
  echo $row_common[common_id];
  if(empty($row_common[common_id])==true){//공통활동 등록된 DB안에 같은 활동명이 있는지 확인
    $result_users = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_grade LIKE '%학년' AND user_season='".substr($_COOKIE['Season'],0,4)."'");
      while( $row_users = mysqli_fetch_assoc($result_users)){
      $sql_common = "INSERT INTO bs_common (common_season,common_email,common_grade,common_name,common_sub,common_area,common_time,common_text,common_created)VALUES('".substr($_COOKIE['Season'],0,4)."','".$row_users[user_email]."','".$row_users[user_grade]."','".$row_users[user_name]."','".$_POST[common_sub]."','".addslashes($_POST[common_area])."','".$_POST[common_time]."','".addslashes(nl2br($_POST[common_text]))."',now())";
      mysqli_query($conn, $sql_common);}
  }else{
    echo '<h6><strong><font color="red"><center>이미 등록한 활동명이 있습니다. 다른 활동명으로 등록해주세요.</center></font></strong></h6>';
  }
}

//공통활동 삭제
if(empty($_POST[common_single_del])==false){
  $result_common_delete = mysqli_query($conn,"DELETE FROM bs_common WHERE common_id ='".$_POST[common_single_del]."' AND common_season='".substr($_COOKIE['Season'],0,4)."'");
}

//공통활동 수정
if(empty($_POST[common_single_update])==false){
  $common_time_update = $_POST['common_time_update'];
  $common_text_update = $_POST['common_text_update'];
  $common_id_update = $_POST['common_id_update'];
  for ($i=0; $i < count($common_id_update); $i++) {
    if($common_id_update[$i]==$_POST[common_single_update]){
  $result_common_update = mysqli_query($conn,"UPDATE bs_common SET common_time='".$common_time_update[$i]."',common_text='".addslashes(nl2br($common_text_update[$i]))."' WHERE common_id = '".$common_id_update[$i]."'");}
  }}

//공통활동 전체삭제
if((empty($_POST[common_del])==false)&&($_COOKIE['Email']=='<YOUR_MASTER_EMAIL>')){
  $result_common_delete = mysqli_query($conn,"DELETE FROM bs_common WHERE common_sub='".$_POST['common_sub_update']."' AND common_season='".substr($_COOKIE['Season'],0,4)."'");
}

//공통활동 전체수정
if((empty($_POST[common_update])==false)&&($_COOKIE['Email']=='<YOUR_MASTER_EMAIL>')){
  $common_time_update = $_POST['common_time_update'];
  $common_text_update = $_POST['common_text_update'];
  $common_id_update = $_POST['common_id_update'];
  for ($i=0; $i < count($common_id_update); $i++) {
  $result_common_update = mysqli_query($conn,"UPDATE bs_common SET common_time='".$common_time_update[$i]."',common_text='".addslashes($common_text_update[$i])."' WHERE common_id = '".$common_id_update[$i]."'");
}}

//학습계획서 승인
if($_POST['apvId']=="승인"){
  $sql_apv = 'UPDATE bs_plan SET plan_apv="승인" WHERE plan_id='.$_POST[plan_id];
  mysqli_query($conn, $sql_apv);$_POST['apvId'] = NULL;$row_plan_mentor[plan_apv]="승인";}

//학습계획서 승인취소
if($_POST['apvId']=="승인취소"){
  $sql_apv = 'UPDATE bs_plan SET plan_apv="대기중" WHERE plan_id='.$_POST[plan_id];
  mysqli_query($conn, $sql_apv);$_POST['apvId'] = NULL;$row_plan_mentor[plan_apv]="대기중";}

  //사용자가 개설한 학습계획서 plan_id를 data_plan_design에 저장
  $result_plan_design = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season='".$_COOKIE['Season']."'");
  $i = 0;$k=0;$l=0;
  while( $row_plan_design = mysqli_fetch_assoc($result_plan_design)){
  //모든 학습계획서의 이름과 아이디 정보를 얻는다.
  $data_plan_class[$k] = $row_plan_design['plan_class'];
  $data_plan_id[$k] = $row_plan_design['plan_id'];$k++;

  //사용자가 멘토링 하고있는 학습계획서 plan_id를 data_plan에 저장
  if($row_plan_design['plan_mentor']===$_COOKIE['Name']){
  $data_plan[$l] = $row_plan_design['plan_id'];$l++;}

  //사용자가 개설한 학습계획서
  if($row_plan_design['plan_name']===$_COOKIE['Name']){
  $data_plan_design[$i] = $row_plan_design['plan_id'];$i++;}

  //사용자가 신청한 수업
  for($j=0;$j<count($data_apply_class);$j++){
    if($data_apply_class[$j]==$row_plan_design['plan_class']){
      $data_apply_plan_id[$j] = $row_plan_design['plan_id'];
    }}
  }

  // 사용자가 어드바이징 하고 있는 user_name을 data_adv에 저장
  $result_adv = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season=".substr($_COOKIE['Season'],0,4));
  $i = 0;
  while( $row_adv = mysqli_fetch_assoc($result_adv)){
  if($row_adv['user_email']===$_POST['user_email']){
  $data_adv_id[$i] = $row_adv[user_id];
  $data_adv_email[$i] = $row_adv[user_email];
  $data_adv[$i] = $row_adv[user_name];
  $data_adv_approved[$i] = $row_adv['user_'.substr($_COOKIE['Season'],14,1).'q'];$i++;}
  }

  //수강신청 목록에서 이름, 강좌명, 학점, 시간을 data_apply에 저장한다.
  $i=0;
  $j=0;
  $apply_class[0] = "초기값";//수강취소를 위한 초기값 설정
  $result_apply_user = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_season='".$_COOKIE['Season']."'");
  while( $row_apply_user = mysqli_fetch_assoc($result_apply_user))
  {
    if($row_apply_user[apply_id]==$_POST[Self_Evaluation_Id]){
    $data_apply_self_evaluation_text = $row_apply_user[apply_self_evaluation];
    $data_apply_self_evaluation_class = $row_apply_user[apply_class];}
    if($row_apply_user[apply_email]==$_COOKIE[Email]){
    $data_apply_id[$i] = $row_apply_user[apply_id];
    $data_apply_name[$i] = $row_apply_user[apply_name];
    $data_apply_class[$i] = $row_apply_user[apply_class];
    $data_apply_point[$i] = $row_apply_user[apply_point];
    $data_apply_time[$i] = $row_apply_user[apply_time];
    $data_apply_self_evaluation[$i] = $row_apply_user[apply_self_evaluation];
    $data_apply_rating[$i] = $row_apply_user[apply_rating];
    $i++;}
    $apply_class[$j] = $row_apply_user[apply_class];
    $j++;
  }

  //사용자의 승인정보를 얻는다.
  $result_user = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season=".substr($_COOKIE['Season'],0,4));
  while( $row_user = mysqli_fetch_assoc($result_user))
  {
    if($row_user[user_email]==$_COOKIE[Email]){
      $data_user_approved = $row_user[user_apv];
    }
  }
?>
<!-- 삭제시 팝업을 띄우는 함수 -->
  <script>
function confirmDel() {
        var r = confirm("정말 실행하시겠습니까?");
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
<body>
  <h4><strong>교과 정보 - <?php echo $_COOKIE['Season'];?></strong></h4>
   <h6><strong>멘토링 현황</strong></h6>
   <table class="w3-card-4 w3-bordered w3-centered w3-small">
     <tbody>
    <tr>
      <th>강좌명</th>
      <th>교과</th>
      <th>학점</th>
      <th>평가</th>
      <th>수정(멘토)</th>
        <?php
        echo '<form action="print.php?id=System_Mentor" method="post" target="_blank"><input type="hidden" name="plan_mentor_email" value="'.$_COOKIE['Email'].'"><input type="hidden" name="plan_mentor" value="'.$_COOKIE['Name'].'"><th><button class="w3-button w3-theme-dark" type="submit">인쇄</button></th></form>';?>
    </tr>
    <?php
    $mentor_point = 0;
    $result_plan_mentor = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season='".$_COOKIE['Season']."'");
    while( $row_plan_mentor = mysqli_fetch_assoc($result_plan_mentor)){
      for($i=0;$i<=count($data_plan);$i++)
      {
        if($data_plan[$i]===$row_plan_mentor['plan_id']){
          echo '<tr>';
          //강좌명 출력
          echo '<td style="width:40%;">'.$row_plan_mentor[plan_class].'</td>';
          //교과명 출력
          echo '<td style="width:20%;">'.$row_plan_mentor[plan_sub].'</td>';
          //학점 출력
          echo '<td style="width:10%;">'.$row_plan_mentor[plan_point].'</td>';
          //학습계획서 및 평가 출력
          echo '<form action="index.php?id=Result_Plan_EV" method="post" target="_blank"><td style="width:15%;"><input type="hidden" name="planId" value="'.$row_plan_mentor[plan_id].'"><input class="w3-button  w3-theme-l4" type="submit" name="submit" value="보기"></td></form>';
          //수정
          echo '<form action="index.php?id=Design_Plan_Update_Mentor" method="post"><td style="width:10%;"><input name="planId" value='.$row_plan_mentor[plan_id].' type="hidden"><input class="w3-button w3-theme-light" type="submit" name="submit" value="수정"></td></form>';
          if($row_plan_mentor[plan_apv]=="대기중"){
            echo '<form action="index.php?id=Information_Mnt" method="post"><td style="width:10%;"><input type="hidden" name="apvId" value="승인"><input name="user_name" value='.$_COOKIE['Name'].' type="hidden"><input name="user_email" value='.$_COOKIE['Email'].' type="hidden"><input name="plan_id" value='.$row_plan_mentor[plan_id].' type="hidden"><input class="w3-button w3-theme-light" type="submit" name="submit" value="승인"></td></form>';}
          elseif(in_array($row_plan_mentor[plan_class],$apply_class)===false){
            echo '<form action="index.php?id=Information_Mnt" method="post"><th style="width:10%;"><input type="hidden" name="apvId" value="승인취소"><input name="user_name" value='.$_COOKIE['Name'].' type="hidden"><input name="user_email" value='.$_COOKIE['Email'].' type="hidden"><input name="plan_id" value='.$row_plan_mentor[plan_id].' type="hidden"><input class="w3-button w3-theme-light" type="submit" name="submit" value="승인취소"></th></form>';}
          elseif($row_plan_mentor[plan_apv]=="승인"){
            echo '<td style="width:10%;">승인완료</td>';
            }
                  echo '</tr>';
                  $mentor_point = $mentor_point + $row_plan_mentor[plan_point];
                }
              }
            }
            echo '<tr><td style="width:40%" class="">합계</td>';
            //학점 출력
            echo '<td style="width:10%" class="" colspan="4">'.$mentor_point.'</td>';
            ?>
          </tbody>
        </table>

    <h6><strong>공통활동 등록</strong></h6>
    <table class="w3-card-4 w3-bordered w3-centered w3-small">
      <tbody>
     <tr>
       <th>영역</th>
       <th>시간</th>
       <th>활동명</th>
       <th>특기사항</th>
       <th>등록</th>
         </tr>
     <tr>
       <form action="index.php?id=Information_Mnt" method="post">
       <td style="width:15%"><select class="w3-select" name="common_area">
         <option value="자율활동">자율활동</option>
         <option value="동아리활동">동아리활동</option>
         <option value="봉사활동">봉사활동</option>
         <option value="진로활동">진로활동</option>
       </select></td>
       <td style="width:10%"><input class="w3-input" name="common_time" type="text"></td>
       <td style="width:15%"><input class="w3-input" name="common_sub" type="text"></td>
       <td style="width:50%"><textarea class="w3-input" name="common_text"></textarea></td>
       <td style="width:10%"><button class="w3-button w3-theme-dark" type="submit">등록</button></td>
       </form>
     </tr>
       </tbody>
     </table>

     <h6><strong>공통활동 수정</strong></h6>
     <table class="w3-card-4 w3-bordered w3-centered w3-small">
       <tbody>
      <tr>
        <th>학년</th>
        <th>이름</th>
        <th>영역</th>
        <th>활동명</th>
        <th>시간</th>
        <th>특기사항</th>
        <th>수정</th>
        <!-- <th>삭제</th> -->
          </tr>
      <tr>
      <form action="index.php?id=Information_Mnt" method="post">
        <select class="w3-select" name="common_sub_update" onchange="form.submit()"><option><strong>이곳에서 공통활동을 선택하세요!</strong></option>
          <?php
          $result_common = mysqli_query($conn, "SELECT * FROM bs_common WHERE common_season = '".substr($_COOKIE['Season'],0,4)."' GROUP BY common_sub ORDER BY common_area, common_sub");
          while($row_common = mysqli_fetch_assoc($result_common)){
            echo '<option value="'.$row_common[common_sub].'">'.$row_common[common_area].'>>'.$row_common[common_sub].'</option>';
          }?>
        </select>
      </form>
        <form action="index.php?id=Information_Mnt" onsubmit="return confirmDel()" method="post">
        <?php
        $i = 0; //데이터 번호
        $result_common = mysqli_query($conn, "SELECT * FROM bs_common WHERE common_sub = '".$_POST[common_sub_update]."' AND common_season = '".substr($_COOKIE['Season'],0,4)."' ORDER BY common_grade, common_email");
        while($row_common = mysqli_fetch_assoc($result_common)){
          echo '<input name="common_id_update[]" type="hidden" value="'.$row_common[common_id].'">';
          echo '<input name="common_sub_update" type="hidden" value="'.$row_common[common_sub].'">';
          echo '<tr><td style="width:10%">'.$row_common[common_grade].'</td>';
          echo '<td style="width:10%">'.$row_common[common_name].'</td>';
          echo '<td style="width:10%">'.$row_common[common_area].'</td>';
          echo '<td style="width:15%">'.$row_common[common_sub].'</td>';
          echo '<td style="width:10%"><input class="w3-input" name="common_time_update[]" type="text" value="'.$row_common[common_time].'"></td>';
          echo '<td style="width:50%"><textarea class="w3-input" name="common_text_update[]">'.str_replace('<br />','',$row_common[common_text]).'</textarea></td>';
          echo '<td style="width:15%"><button class="w3-button w3-theme-dark" name="common_single_update" value="'.$row_common[common_id].'" type="submit">수정</button></td></tr>';
          // echo '<td style="width:15%"><button class="w3-button w3-theme-dark" name="common_single_del" value="'.$row_common[common_id].'" type="submit">삭제</button></td>';
        }
        ?>
        <br>
        <?php
        echo '<table class="w3-card-4 w3-bordered w3-centered w3-small"><tr>';
        echo '<td><button class="w3-button w3-theme-dark" name="common_update" value="수정" type="submit">전체수정</button></td><td>';
        echo '<button class="w3-button w3-theme-dark" name="common_del" value="삭제" type="submit">전체삭제</button>';
        echo '</td></tr></table>';
         ?>
        </form>
      </tr>
        </tbody>
      </table>
        <br>

<?php
// 권한 관리(권한이 없으면 메인화면으로 이동)
require_once 'php/user_chk.php';
//get으로 season
if(isset($_GET[Season])){
  $_COOKIE[Season] = $_GET[Season];
}

//승인취소
if($_POST[planClass]){
  //수강신청된 항목 중에 성적이 입력되어있는 것이 있으면 실행 중지
  $result_cnt = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_season = '".$_COOKIE[Season]."' AND apply_class = '".$_POST[planClass]."' AND NOT apply_rating = ''");
  $result = mysqli_num_rows($result_cnt);
  if($result==0){
  //수강 등록 된 항목 삭제
  mysqli_query($conn, "DELETE FROM bs_apply WHERE apply_season = '".$_COOKIE[Season]."' AND apply_class = '".$_POST[planClass]."'");
  //승인 취소
  mysqli_query($conn, "UPDATE bs_plan SET plan_apv = '대기중' WHERE plan_season = '".$_COOKIE[Season]."' AND plan_class = '".$_POST[planClass]."'");
    echo '<div class="w3-yellow w3-card-4"><h6><strong><font color="blue"><center>'.$_POST[planClass].'수업이 승인 취소 되었습니다. 기존에 수강신청했던 내용들은 모두 삭제되었으니 다시 등록하도록 안내해주시기 바랍니다.</center></font></strong></h6></div>';
  }else{
    echo '<div class="w3-yellow w3-card-4"><h6><strong><font color="blue"><center>'.$_POST[planClass].'수업에 '.$result.'건의 학점이 등록되어있습니다. 학점이 등록되어있으면 승인취소를 할 수 없습니다. 학점 등록 사항을 수정하도록 안내해주세요.</center></font></strong></h6></div>';
  }
}

//교사 명단을 출력한다.
$list_teacher;$i=0;
$result_user = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season =".substr($_COOKIE['Season'],0,4));
while( $row_user = mysqli_fetch_assoc($result_user)){
  if($row_user['user_grade']==="교사"){
    $list_teacher[$i] = $row_user['user_name'];
    $i++;
  }
}

$result_plan = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season ='".$_COOKIE['Season']."'");
$i = 0;
while( $row_plan = mysqli_fetch_assoc($result_plan)){
  //선택된 학습계획서의 plan_contents를 배열로 나누어 저장
$contents = explode('%^',$row_plan['plan_contents']);
//선택된 학습계획서의 plan_time를 배열로 나누어 저장 & 정렬
$time[$i] = explode('/',$row_plan['plan_time']);
//교과 & 과목으로 구분
$setCurriculum = explode('/',$row_plan['plan_sub']);

 sort($time[$i]);
 $class_time = "";
 for($x=0;$x<=count($time[$i]);$x++){
 if(empty($time[$i][$x])===false){
 $class_time.= $time[$i][$x];}}

$data_plan[$i] = array($row_plan['plan_id'], $setCurriculum[0], $setCurriculum[1], $row_plan['plan_class'], $row_plan['plan_mentor'], $contents[12], $contents[13], $row_plan['plan_point'], $contents[0], $row_plan['plan_apv']);
$i++;}
// 열 목록 얻기
foreach ($data_plan as $key => $row) {
    $curriculum[$key]  = $row[1];
    $subject[$key]  = $row[2];
    $class[$key] = $row[3];
    $mentor[$key] = $row[4];
}

// volume 내림차순, edition 오름차순으로 데이터를 정렬
// 공통 키를 정렬하기 위하여 $data를 마지막 인수로 추가
array_multisort($curriculum, SORT_ASC, $subject, SORT_ASC, $mentor, SORT_ASC, $class, SORT_ASC, $data_plan);
?>

<!-- 수정시 팝업을 띄우는 함수 -->
  <script>
function confirmDel() {
        var r = confirm("정말 실행하시겠습니까?");
        if (r == true) {
        } else {
        return false;
        }
}
</script>
<body>
    <h5><strong>학습계획서 관리 - <?php echo $_COOKIE[Season];?></strong></h5>
    <div class="w3-responsive">
      <h6><strong>교사개설과목</strong></h6>
        <table style="table-layout:fixed;" class="w3-card-4 w3-bordered w3-centered w3-small">
          <thead>
            <tr>
              <th style="width:5%;">번호</th>
              <th style="width:5%;">교과</th>
              <th style="width:10%;">과목</th>
              <th style="width:30%;">강의</th>
              <th style="width:10%;">멘토</th>
              <th style="width:5%;">학점</th>
              <th style="width:10%;">학습계획서</th>
              <th style="width:10%;">승인취소</th>
              <th style="width:10%;">수정</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $j=0;
            $cnt_cur=0;$cnt_sub=0;
            $temp_cur = "";$temp_sub = "";
            for($i=0;$i<count($data_plan);$i++){
              $temp_name = explode("_",$data_plan[$i][3]);
              $temp_name = explode("/",$temp_name[1]);
              if(in_array($temp_name[0],$list_teacher)){
                if($data_plan[$i][9]==="승인"){
                $j++;
                echo '<tr>';
                echo '<td style="width:5%;">'.$j.'</td>';
                echo '<td style="width:10%;">'.$data_plan[$i][1].'</td>';
                echo '<td style="width:5%;">'.$data_plan[$i][2].'</td>';
                echo '<td style="width:20%;">'.$data_plan[$i][3].'</td>';
                echo '<td style="width:5%;">'.$data_plan[$i][4].'</td>';
                echo '<td style="width:5%;">'.$data_plan[$i][7].'</td>';
                echo '<form action="index.php?id=Result_Plan" method="post" target="_blank"><td style="width:15%;"><input type="hidden" name="planId" value="'.$data_plan[$i][0].'"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="보기"></td></form>';
                echo '<form action="index.php?id=Admin_Apv" onsubmit="return confirmDel()" method="post" target="_blank"><td style="width:15%;"><input type="hidden" name="planClass" value="'.$data_plan[$i][3].'"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="승인취소"></td></form>';
                echo '<form action="index.php?id=Design_Plan_Update_Admin" method="post" target="_blank"><td style="width:15%;"><input type="hidden" name="planId" value="'.$data_plan[$i][0].'"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="수정"></td></form>';
                echo '</tr>';}}}?>
          </tbody>
    </table><br>
    <h6><strong>학생개설과목</strong></h6>
      <table style="table-layout:fixed;" class="w3-card-4 w3-bordered w3-centered w3-small">
        <thead>
          <tr>
            <th style="width:5%;">번호</th>
            <th style="width:5%;">교과</th>
            <th style="width:10%;">과목</th>
            <th style="width:30%;">강의</th>
            <th style="width:10%;">멘토</th>
            <th style="width:5%;">학점</th>
            <th style="width:10%;">학습계획서</th>
            <th style="width:10%;">승인취소</th>
            <th style="width:10%;">수정</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $j=0;
          $cnt_cur=0;$cnt_sub=0;
          $temp_cur = "";$temp_sub = "";
          for($i=0;$i<count($data_plan);$i++){
            $temp_name = explode("_",$data_plan[$i][3]);
            $temp_name = explode("/",$temp_name[1]);
            if(in_array($temp_name[0],$list_teacher)===false){
              $j++;
              echo '<tr>';
              echo '<td style="width:5%;">'.$j.'</td>';
              echo '<td style="width:10%;">'.$data_plan[$i][1].'</td>';
              echo '<td style="width:5%;">'.$data_plan[$i][2].'</td>';
              echo '<td style="width:20%;">'.$data_plan[$i][3].'</td>';
              echo '<td style="width:5%;">'.$data_plan[$i][4].'</td>';
              echo '<td style="width:5%;">'.$data_plan[$i][7].'</td>';
              echo '</td>';
              echo '<form action="index.php?id=Result_Plan" method="post" target="_blank"><td style="width:15%;"><input type="hidden" name="planId" value="'.$data_plan[$i][0].'"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="보기"></td></form>';
              echo '<form action="index.php?id=Admin_Apv" onsubmit="return confirmDel()" method="post" target="_blank"><td style="width:15%;"><input type="hidden" name="planClass" value="'.$data_plan[$i][3].'"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="승인취소"></td></form>';
              echo '<form action="index.php?id=Design_Plan_Update_Admin" method="post" target="_blank"><td style="width:15%;"><input type="hidden" name="planId" value="'.$data_plan[$i][0].'"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="수정"></td></form>';
              echo '</tr>';}}?>
        </tbody>
  </table><br>
    </div>

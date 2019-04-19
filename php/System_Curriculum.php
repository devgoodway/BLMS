<?php
//DB
require_once("db_info.php");
?>


<!DOCTYPE html>
<html>
<head>
<title>BLMS</title>
<!-- favicon -->
<link rel="shortcut icon" href="<?php echo $root_url; ?>img/favicon11.ico">
<!--META-->
<meta charset="utf-8">
<!--CSS-->
<link rel="stylesheet" href="<?php echo $root_url; ?>css/w3_4_1.css">
<link rel="stylesheet" href="<?php echo $root_url; ?>css/w3-theme-blue-grey.css">
<link rel="stylesheet" href="<?php echo $root_url; ?>css/table_ver3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
<!--JAVA SCRIPT-->
<script type="text/javascript" src="<?php echo $root_url; ?>js/menu.js"></script>
<script type="text/javascript" src="<?php echo $root_url; ?>js/open_middle_page.js"></script>
<script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
<script src="https://apis.google.com/js/client.js?onload=checkAuth"></script>
<!--PHP-->
<?php
//get으로 season
if(isset($_GET[Season])){
  $_COOKIE[Season] = $_GET[Season];
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

</head>
<body>
    <h3><strong><center><?php echo $_COOKIE[Season];?> 개설교과목록</center></strong></h3>
    <div class="w3-responsive">
      <h5><strong>교사개설과목</strong></h5>
        <table style="table-layout:fixed;" class="w3-card-4 w3-bordered w3-centered w3-small">
          <thead>
            <tr>
              <th style="width:5%;">번호</th>
              <th style="width:5%;">교과</th>
              <th style="width:10%;">과목</th>
              <th style="width:20%;">강의</th>
              <th style="width:5%;">멘토</th>
              <th style="width:5%;">대상</th>
              <th style="width:5%;">수준</th>
              <th style="width:5%;">학점</th>
              <th style="width:25%;">학습방법</th>
              <th style="width:15%;">학습계획서</th>
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
                echo '<td style="width:5%;">'.$data_plan[$i][5].'</td>';
                echo '<td style="width:5%;">'.$data_plan[$i][6].'</td>';
                echo '<td style="width:5%;">'.$data_plan[$i][7].'</td>';
                echo '<td style="width:25%;">'.$data_plan[$i][8].'</td>';
                echo '<form action="'.$root_url.'index.php?id=Result_Plan" method="post" target="_blank"><td style="width:15%;"><input type="hidden" name="planId" value="'.$data_plan[$i][0].'"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="보기"></td></form>';
                echo '</tr>';}}}?>
          </tbody>
    </table><br>
    <h5><strong>학생개설과목</strong></h5>
      <table style="table-layout:fixed;" class="w3-card-4 w3-bordered w3-centered w3-small">
        <thead>
          <tr>
            <th style="width:5%;">번호</th>
            <th style="width:5%;">교과</th>
            <th style="width:10%;">과목</th>
            <th style="width:20%;">강의</th>
            <th style="width:5%;">멘토</th>
            <th style="width:5%;">대상</th>
            <th style="width:5%;">수준</th>
            <th style="width:5%;">학점</th>
            <th style="width:25%;">학습방법</th>
            <th style="width:15%;">학습계획서</th>
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
              echo '<td style="width:5%;">'.$data_plan[$i][5].'</td>';
              echo '<td style="width:5%;">'.$data_plan[$i][6].'</td>';
              echo '<td style="width:5%;">'.$data_plan[$i][7].'</td>';
              echo '</td>';
              echo '<td style="width:25%;">'.$data_plan[$i][8].'</td>';
              echo '<form action="'.$root_url.'index.php?id=Result_Plan" method="post" target="_blank"><td style="width:15%;"><input type="hidden" name="planId" value="'.$data_plan[$i][0].'"><input class="w3-button w3-theme-l4" type="submit" name="submit" value="보기"></td></form>';
              echo '</tr>';}}?>
        </tbody>
  </table><br>
    </div>
</body>
</html>

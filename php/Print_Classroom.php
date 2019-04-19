<?php
// 권한 관리
require_once 'php/user_chk.php';

//사용자 함수 추가 - 시간별 강의실 사용 여부를 확인해주시는 함수
function Classroom_Making($data_plan_class, $data_plan_time, $post_plan_classroom, $data_plan_classroom_all, $Classroom_All, $day){
$Classroom_All_Temp = $Classroom_All;
  for($i=0;$i<count($data_plan_class);$i++){
    if(strpos($data_plan_time[$i],$day)){
      if("사용중인 강의실"===$post_plan_classroom){
        if($data_plan_classroom_all[$i]!=="없음"){
        echo $data_plan_classroom_all[$i].'<br>';}}
      elseif("사용가능한 강의실"===$post_plan_classroom){
        $classroom = explode("/",$data_plan_classroom_all[$i]);
        for($j=0;$j<count($Classroom_All_Temp);$j++){
          if($Classroom_All_Temp[$j]==$data_plan_classroom_all[$i]){
            $Classroom_All_Temp[$j] = "";
          }elseif($Classroom_All_Temp[$j]==$classroom[0]){
            $Classroom_All_Temp[$j] = "";
          }elseif($Classroom_All_Temp[$j]==$classroom[1]){
            $Classroom_All_Temp[$j] = "";
          }
        }
      }elseif("전체"===$post_plan_classroom){
          echo $data_plan_class[$i].'<br>';
      }
        else{
          echo $data_plan_class[$i];
        }
      }
    }
      if("사용가능한 강의실"===$post_plan_classroom){
        for($k=0;$k<count($Classroom_All_Temp);$k++){
          echo $Classroom_All_Temp[$k].'<br>';
        }
      }
}

//강의실명 배열에 추가
$Classroom_All = array("101","102","101/102","103","104","103/104","201","202","201/202","203","204","203/204","205","206","205/206","207","208","207/208","세미나1","세미나2","세미나3","세미나4","메이커스페이스","어린이집","음악실1","음악실2","미술실","별동천 카페","강당","방송실","회의실");

//사용자 정보를 data_plan에 저장
$result_plan = mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_season = '".$_COOKIE[Season]."'");
$i = 0; $j = 0;
while( $row_plan = mysqli_fetch_assoc($result_plan)){
if(($row_plan['plan_classroom']===$_POST['plan_classroom'])||('전체'===$_POST['plan_classroom'])||('사용중인 강의실'===$_POST['plan_classroom'])||('사용가능한 강의실'===$_POST['plan_classroom'])){
$data_plan_classroom = $_POST['plan_classroom'];
$data_plan_classroom_all[$i] = $row_plan['plan_classroom'];
$data_plan_class[$i] = $row_plan['plan_class'];
$data_plan_time[$i] = $row_plan['plan_time'];$i++;}
if(in_array($row_plan['plan_classroom'],$plan_classroom)==false){
$plan_classroom[$j] = $row_plan['plan_classroom'];$j++;}}
?>

<h4><strong>강의실(<?php echo $data_plan_classroom; ?>) - <?php echo $_COOKIE[Season];?></strong></h4>
<table class="w3-table-all w3-card-4 w3-bordered w3-striped w3-centered w3-small w3-hide-small">
  <thead>
<form action="print.php?id=Print_Classroom" method="post">
  <select class="w3-hide-small plan-form" name="plan_classroom" onchange="form.submit()"><option>아래에서 강의실을 선택하세요.</option>
    <?php
      echo '<option value="전체">전체</option>';
      echo '<option value="사용중인 강의실">사용중인 강의실</option>';
      echo '<option value="사용가능한 강의실">사용가능한 강의실</option>';
    for($i=0;$i<=count($plan_classroom);$i++){
      if(($plan_classroom[$i]<>"없음")&&($plan_classroom[$i]<>"")){
      echo '<option value="'.$plan_classroom[$i].'">'.$plan_classroom[$i].'</option>';}}
     ?>
  </select>
</form>
</thead>
</table><br>
<table class="w3-table-all w3-card-4 w3-bordered w3-striped w3-centered w3-tiny">
  <thead>
    <tr>
      <th class="timetable_table">강의실</th>
      <th class="timetable_table" colspan="5"><?php echo $data_plan_classroom; ?></th>
      <th>
      <?php
      echo '<form action="print.php?id=Print_Classroom" method="post">';
      echo '<input name="plan_classroom" value="'.$_POST['plan_classroom'].'" type="hidden"><button type="submit">인쇄</button></form>';
       ?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th class="timetable_table">시간</th>
      <th class="timetable_table">구분</th>
      <th class="timetable_table">월</th>
      <th class="timetable_table">화</th>
      <th class="timetable_table">수</th>
      <th class="timetable_table">목</th>
      <th class="timetable_table">금</th>
    </tr>
    <tr>
      <th class="timetable_table">07:00-07:30</th>
      <th class="timetable_table">아침활동</th>
      <td align=center class="timetable_table" colspan="5">아침활동</td>
    </tr>
    <tr>
      <th class="timetable_table">07:30-08:30</th>
      <th class="timetable_table">아침식사</th>
      <td align=center class="timetable_table" colspan="5">아침식사</td>
    </tr>
    <tr>
      <th class="timetable_table">08:30-09:00</th>
      <th class="timetable_table">아침독서</th>
      <td align=center class="timetable_table">아침독서</td>
      <td align=center class="timetable_table">아침독서</td>
      <td align=center class="timetable_table">예배</td>
      <td align=center class="timetable_table">아침독서</td>
      <td align=center class="timetable_table">묵상</td>
    </tr>
    <tr>
      <th class="timetable_table">09:00-09:35</th>
      <th class="timetable_table">1교시</th>
      <td align=center class="timetable_table">묵상</td>
      <td align=center class="timetable_table">묵상</td>
      <td align=center class="timetable_table">예배</td>
      <td align=center class="timetable_table">묵상</td>
      <td align=center class="timetable_table">어·미</td>
    </tr>
    <tr>
      <th class="timetable_table">09:45-10:30</th>
      <th class="timetable_table">2교시</th>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "월2"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "화2"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "수2"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "목2"); ?></td>
      <td align=center class="timetable_table">어·미</td>
    </tr>
    <tr>
      <th class="timetable_table">10:35-11:20</th>
      <th class="timetable_table">3교시</th>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "월3"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "화3"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "수3"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "목3"); ?></td>
      <td align=center class="timetable_table">양육</td>
    </tr>
    <tr>
      <th class="timetable_table">11:25-12:10</th>
      <th class="timetable_table">4교시</th>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "월4"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "화4"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "수4"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "목4"); ?></td>
      <td align=center class="timetable_table">양육</td>
    </tr>
    <tr>
      <th class="timetable_table">12:10-13:10</th>
      <th class="timetable_table">점심식사</th>
      <td align=center class="timetable_table" colspan="5">점심식사</td>
    </tr>
    <tr>
      <th class="timetable_table">13:10-13:55</th>
      <th class="timetable_table">5교시</th>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "월5"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "화5"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "수5"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "목5"); ?></td>
      <td align=center class="timetable_table"></td>
    </tr>
    <tr>
      <th class="timetable_table">14:00-14:45</th>
      <th class="timetable_table">6교시</th>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "월6"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "화6"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "수6"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "목6"); ?></td>
      <td align=center class="timetable_table"></td>
    </tr>
    <tr>
      <th class="timetable_table">14:50-15:35</th>
      <th class="timetable_table">7교시</th>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "월7"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "화7"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "수7"); ?></td>
      <td align=center class="timetable_table">자치</td>
      <td align=center class="timetable_table"></td>
    </tr>
    <tr>
      <th class="timetable_table">15:40-16:25</th>
      <th class="timetable_table">8교시</th>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "월8"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "화8"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "수8"); ?></td>
      <td align=center class="timetable_table">자치</td>
      <td align=center class="timetable_table"></td>
    </tr>
    <tr>
      <th class="timetable_table">16:30-17:15</th>
      <th class="timetable_table">9교시</th>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "월9"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "화9"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "수9"); ?></td>
      <td align=center class="timetable_table">동아리</td>
      <td align=center class="timetable_table"></td>
    </tr>
    <tr>
      <th class="timetable_table">17:20-18:05</th>
      <th class="timetable_table">10교시</th>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "월10"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "화10"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "수10"); ?></td>
      <td align=center class="timetable_table">동아리</td>
      <td align=center class="timetable_table"></td>
    </tr>
    <tr>
      <th class="timetable_table">18:05-18:55</th>
      <th class="timetable_table">저녁식사</th>
      <td align=center class="timetable_table" colspan="4">저녁식사</td>
      <td align=center class="timetable_table"></td>
    </tr>
    <tr>
      <th class="timetable_table" rowspan="2">19:00-21:00</th>
      <th class="timetable_table" rowspan="2">저녁활동</th>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "월11"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "화11"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "수11"); ?></td>
      <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "목11"); ?></td>
      <td align=center class="timetable_table"></td>
    </tr>
    <tr>
    <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "월12"); ?></td>
    <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "화12"); ?></td>
    <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "수12"); ?></td>
    <td align=center class="timetable_table"><?php Classroom_Making($data_plan_class, $data_plan_time, $_POST['plan_classroom'], $data_plan_classroom_all, $Classroom_All, "목12"); ?></td>
      <td align=center class="timetable_table"></td>
    </tr>
    <tr>
      <th class="timetable_table">21:00-21:10</th>
      <th class="timetable_table">청소시간</th>
      <td align=center class="timetable_table" colspan="4">청소시간</td>
      <td align=center class="timetable_table"></td>
    </tr>
    <tr>
      <th class="timetable_table">21:10-21:30</th>
      <th class="timetable_table">저널및반성</th>
      <td align=center class="timetable_table" colspan="4">저널및반성</td>
      <td align=center class="timetable_table"></td>
    </tr>
  </table>
  <br>

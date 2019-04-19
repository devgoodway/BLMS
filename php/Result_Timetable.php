<?php
// 권한 관리
require_once 'php/user_chk.php';

//사용자 정보를 data_apply에 저장
$result_apply = mysqli_query($conn, "SELECT * FROM bs_apply WHERE apply_season = '".$_COOKIE['Season']."'");
$i = 0; $j = 0;
while( $row_apply = mysqli_fetch_assoc($result_apply)){
if($row_apply['apply_name']===$_GET['user_name']){
$data_apply_name = $row_apply['apply_name'];
$data_apply_email = $row_apply['apply_email'];
$data_apply_class[$i] = $row_apply['apply_class'];
$data_apply_time[$i] = $row_apply['apply_time'];$i++;}
if(in_array($row_apply['apply_name'],$apply_name)==false){
$apply_name[$j] = $row_apply['apply_name'];$j++;}}

function ReturnPlanId($conn,$apply_class,$time){//학습계획서를 불러오는 양식을 반환
$i = 0;
$result_plan = mysqli_query($conn, "SELECT plan_id FROM bs_plan WHERE plan_season = '".$_COOKIE['Season']."' AND plan_class = '".$apply_class."'");
while( $row_plan = mysqli_fetch_assoc($result_plan)){//현재 수강 명단을 출력
  $result_apply = mysqli_query($conn, "SELECT apply_name FROM bs_apply WHERE apply_season = '".$_COOKIE['Season']."' AND apply_class = '".$apply_class."'");
  while( $row_apply = mysqli_fetch_assoc($result_apply)){
    if($apply_name == NULL){$apply_name = $row_apply['apply_name'];
    }else{$apply_name = $apply_name."/".$row_apply['apply_name'];}
  $i++;
  }
  $apply_name = $apply_name."(".$i.")";
  $id = $row_plan['plan_id'];}
  if($id==0){
    $id = $apply_class;
  }else{
  $id = '<form name="Plan'.$id.$time.'" action="index.php?id=Result_Plan" method="post" target="_blank"><input type="hidden" name="planId" value="'.$id.'"></form><a href="#"  style="text-decoration:none" title="'.$apply_name.'" onclick="javascript:document.Plan'.$id.$time.'.submit();">'.$apply_class.'</a>';}
  return $id;}
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

<h4><strong>시간표 결과 - <?php echo $_COOKIE[Season]?></strong></h4>
<table class="w3-card-4 w3-bordered w3-centered w3-small w3-hide-small">
  <thead>
  <form action="index.php">
    <input name="id" value="Result_Timetable" type="hidden">
    <select style="width:100%" class="w3-class w3-hide-small" name="user_name" onchange="form.submit()"><option>아래에서 이름을 선택하세요.</option>
      <?php
      sort($apply_name);
      for($i=0;$i<=count($apply_name);$i++){
        if($apply_name[$i]<>"없음"){
        echo '<option value="'.$apply_name[$i].'">'.$apply_name[$i].'</option>';}}
       ?>
    </select>
  </form>
</thead></table><br>
  <table class="w3-card-4 w3-bordered w3-centered w3-small">
    <thead>
      <tr>
        <th class="timetable_table">이름/이메일</th>
        <th class="timetable_table" colspan="3"><?php echo $data_apply_name; ?></th>
        <th class="timetable_table" colspan="2"><?php echo $data_apply_email; ?></th>
        <th class="timetable_table">
        <?php
        echo '<form action="print.php" target="_blank">';
        echo '<input name="id" value="Result_Timetable" type="hidden">';
        echo '<input name="user_name" value="'.$_GET['user_name'].'" type="hidden"><input name="user_email" value="'.$_POST['user_email'].'" type="hidden"><input class="w3-button w3-theme-l4 w3-hide-small" type="submit" name="submit" value="인쇄"></form>';
         ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class=" timetable_table">시간</th>
        <th class=" timetable_table">구분</th>
        <th class=" timetable_table">월</th>
        <th class=" timetable_table">화</th>
        <th class=" timetable_table">수</th>
        <th class=" timetable_table">목</th>
        <th class=" timetable_table">금</th>
      </tr>
      <tr>
        <th class=" timetable_table">07:00-07:30</th>
        <th class=" timetable_table">아침활동</th>
        <td align=center class="timetable_table" colspan="5">아침활동</td>
      </tr>
      <tr>
        <th class=" timetable_table">07:30-08:30</th>
        <th class=" timetable_table">아침식사</th>
        <td align=center class="timetable_table" colspan="5">아침식사</td>
      </tr>
      <tr>
        <th class=" timetable_table">08:30-09:00</th>
        <th class=" timetable_table">아침독서</th>
        <td align=center class="timetable_table">체크인</td>
        <td align=center class="timetable_table">아침독서</td>
        <td align=center class="timetable_table">예배</td>
        <td align=center class="timetable_table">아침독서</td>
        <td align=center class="timetable_table">아침독서</td>
      </tr>
      <tr>
        <th class=" timetable_table">09:00-09:35</th>
        <th class=" timetable_table">1교시</th>
        <td align=center class="timetable_table">묵상</td>
        <td align=center class="timetable_table">묵상</td>
        <td align=center class="timetable_table">예배</td>
        <td align=center class="timetable_table">묵상</td>
        <td align=center class="timetable_table">묵상</td>
      </tr>
      <tr>
        <th class=" timetable_table">09:45-10:30</th>
        <th class=" timetable_table">2교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월2")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"mon2");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화2")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"tue2");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수2")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"wed2");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"목2")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"thu2");}} ?></td>
        <td align=center class="timetable_table">재량</td>
      </tr>
      <tr>
        <th class=" timetable_table">10:35-11:20</th>
        <th class=" timetable_table">3교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월3")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"mon3");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화3")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"tue3");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수3")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"wed3");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"목3")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"thu3");}} ?></td>
        <td align=center class="timetable_table">재량</td>
      </tr>
      <tr>
        <th class=" timetable_table">11:25-12:10</th>
        <th class=" timetable_table">4교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월4")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"mon4");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화4")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"tue4");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수4")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"wed4");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"목4")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"thu4");}} ?></td>
        <td align=center class="timetable_table">체크아웃</td>
      </tr>
      <tr>
        <th class=" timetable_table">12:10-13:10</th>
        <th class=" timetable_table">점심식사</th>
        <td align=center class="timetable_table" colspan="5">점심식사</td>
      </tr>
      <tr>
        <th class=" timetable_table">13:10-13:55</th>
        <th class=" timetable_table">5교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월5")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"mon5");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화5")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"tue5");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수5")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"wed5");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"목5")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"thu5");}} ?></td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <th class=" timetable_table">14:00-14:45</th>
        <th class=" timetable_table">6교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월6")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"mon6");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화6")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"tue6");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수6")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"wed6");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"목6")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"thu6");}} ?></td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <th class=" timetable_table">14:50-15:35</th>
        <th class=" timetable_table">7교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월7")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"mon7");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화7")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"tue7");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수7")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"wed7");}} ?></td>
        <td align=center class="timetable_table">자치</td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <th class=" timetable_table">15:40-16:25</th>
        <th class=" timetable_table">8교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월8")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"mon8");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화8")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"tue8");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수8")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"wed8");}} ?></td>
        <td align=center class="timetable_table">자치</td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <th class=" timetable_table">16:30-17:15</th>
        <th class=" timetable_table">9교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월9")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"mon9");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화9")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"tue9");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수9")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"wed9");}} ?></td>
        <td align=center class="timetable_table">동아리</td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <th class=" timetable_table">17:20-18:05</th>
        <th class=" timetable_table">10교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월10")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"mon10");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화10")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"tue10");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수10")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"wed10");}} ?></td>
        <td align=center class="timetable_table">동아리</td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <th class=" timetable_table">18:05-18:55</th>
        <th class=" timetable_table">저녁식사</th>
        <td align=center class="timetable_table" colspan="4">저녁식사</td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <th class=" timetable_table" rowspan="2">19:00-21:00</th>
        <th class=" timetable_table" rowspan="2">저녁활동</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월11")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"mon11");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화11")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"tue11");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수11")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"wed11");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"목11")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"thu11");}} ?></td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월12")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"mon12");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화12")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"tue12");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수12")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"wed12");}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"목12")!==false){echo ReturnPlanId($conn,$data_apply_class[$i],"thu12");}} ?></td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <th class=" timetable_table">21:00-21:20</th>
        <th class=" timetable_table">저널및반성</th>
        <td align=center class="timetable_table" colspan="4">저널및반성</td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <th class=" timetable_table">21:20-21:30</th>
        <th class=" timetable_table">청소시간</th>
        <td align=center class="timetable_table" colspan="4">청소시간</td>
        <td align=center class="timetable_table"></td>
      </tr>
    </table>
    <br>

<?php
// 권한 관리
require_once 'php/user_chk.php';

//사용자 정보를 data_apply에 저장
$result_apply = mysqli_query($conn, "SELECT * FROM bs_apply");
$i = 0; $j = 0;
while( $row_apply = mysqli_fetch_assoc($result_apply)){
if($row_apply['apply_name']===$_POST['user_name']){
$data_apply_name = $row_apply['apply_name'];
$data_apply_email = $row_apply['apply_email'];
$data_apply_class[$i] = $row_apply['apply_class'];
$data_apply_time[$i] = $row_apply['apply_time'];$i++;}
if(in_array($row_apply['apply_name'],$apply_name)==false){
$apply_name[$j] = $row_apply['apply_name'];$j++;}}
?>

<h4><strong>시간표</strong></h4>
<table class="w3-table-all w3-card-4 w3-bordered w3-striped w3-centered w3-small w3-hide-small">
  <thead>
  <form action="print.php?id=Print_Timetable" method="post">
    <select class="w3-hide-small plan-form" name="user_name" onchange="form.submit()"><option>아래에서 이름을 선택하세요.</option>
      <?php
      for($i=0;$i<=count($apply_name);$i++){
        if($apply_name[$i]<>"없음"){
        echo '<option value="'.$apply_name[$i].'">'.$apply_name[$i].'</option>';}}
       ?>
    </select>
  </form>
</thead></table><br>
  <table class="w3-table-all w3-card-4 w3-bordered w3-striped w3-centered w3-tiny">
    <thead>
      <tr>
        <th class="timetable_table">이름/이메일</th>
        <th class="timetable_table" colspan="3"><?php echo $data_apply_name; ?></th>
        <th class="timetable_table" colspan="3"><?php echo $data_apply_email; ?></th>
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
        <td align=center class="timetable_table">체크인</td>
        <td align=center class="timetable_table">아침독서</td>
        <td align=center class="timetable_table">예배</td>
        <td align=center class="timetable_table">아침독서</td>
        <td align=center class="timetable_table">아침독서</td>
      </tr>
      <tr>
        <th class="timetable_table">09:00-09:35</th>
        <th class="timetable_table">1교시</th>
        <td align=center class="timetable_table">묵상</td>
        <td align=center class="timetable_table">묵상</td>
        <td align=center class="timetable_table">예배</td>
        <td align=center class="timetable_table">묵상</td>
        <td align=center class="timetable_table">묵상</td>
      </tr>
      <tr>
        <th class="timetable_table">09:45-10:30</th>
        <th class="timetable_table">2교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월2")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화2")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수2")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"목2")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table">재량</td>
      </tr>
      <tr>
        <th class="timetable_table">10:35-11:20</th>
        <th class="timetable_table">3교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월3")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화3")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수3")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"목3")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table">재량</td>
      </tr>
      <tr>
        <th class="timetable_table">11:25-12:10</th>
        <th class="timetable_table">4교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월4")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화4")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수4")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"목4")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table">체크아웃</td>
      </tr>
      <tr>
        <th class="timetable_table">12:10-13:10</th>
        <th class="timetable_table">점심식사</th>
        <td align=center class="timetable_table" colspan="5">점심식사</td>
      </tr>
      <tr>
        <th class="timetable_table">13:10-13:55</th>
        <th class="timetable_table">5교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월5")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화5")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수5")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"목5")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <th class="timetable_table">14:00-14:45</th>
        <th class="timetable_table">6교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월6")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화6")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수6")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"목6")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <th class="timetable_table">14:50-15:35</th>
        <th class="timetable_table">7교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월7")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화7")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수7")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table">자치</td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <th class="timetable_table">15:40-16:25</th>
        <th class="timetable_table">8교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월8")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화8")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수8")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table">자치</td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <th class="timetable_table">16:30-17:15</th>
        <th class="timetable_table">9교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월9")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화9")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수9")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table">동아리</td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <th class="timetable_table">17:20-18:05</th>
        <th class="timetable_table">10교시</th>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월10")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화10")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수10")!==false){echo $data_apply_class[$i];}} ?></td>
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
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월11")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화11")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수11")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"목11")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"></td>
      </tr>
      <tr>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"월12")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"화12")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"수12")!==false){echo $data_apply_class[$i];}} ?></td>
        <td align=center class="timetable_table"><?php for($i=0;$i<count($data_apply_class);$i++){if(strpos($data_apply_time[$i],"목12")!==false){echo $data_apply_class[$i];}} ?></td>
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

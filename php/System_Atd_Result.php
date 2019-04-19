<?php
// 권한 관리
require_once 'php/user_chk.php';

//변수초기화
$cnt_grade10 = $cnt_grade11 = $cnt_grade12 = $cnt_teacher = 0;
$i=$j=$k=$l=0;
$chk_grade10=$chk_grade11=$chk_grade12=$chk_grade_teacher=[];
$atd_option=$atd_name=$atd_contents=[];
$chk_array_size = 0;

//시즌
if(isset($_GET[season_atd])==false){
  $_GET[season_atd] = substr($_COOKIE['Season'],0,4);
}

//출석부 등록
if ($_POST[submit]=='제출') {
  for ($m=0; $m < count($_POST[chk]); $m++) {
    $chk_name .= $_POST[chk][$m].'/';
  }
  if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_atd_chk WHERE atd_chk_day = '".$_GET[seek_day]."' AND atd_chk_option = '".$_GET[contents]."' AND atd_chk_atd = '".$_GET[atd]."'"))){
    mysqli_query($conn, "UPDATE bs_atd_chk SET atd_chk_author	 = '".$name."', atd_chk_day = '".$_GET[seek_day]."', atd_chk_option = '".$_GET[contents]."', atd_chk_atd = '".$_GET[atd]."', atd_chk_name = '".$chk_name."', atd_chk_text = '".$_POST[text]."', atd_chk_created = now() WHERE atd_chk_day = '".$_GET[seek_day]."' AND atd_chk_option = '".$_GET[contents]."' AND atd_chk_atd = '".$_GET[atd]."'");
  }else{
    mysqli_query($conn,"INSERT INTO bs_atd_chk (atd_chk_author,atd_chk_day,atd_chk_option,atd_chk_atd,atd_chk_name,atd_chk_text,atd_chk_created) SELECT '".$name."','".$_GET[seek_day]."','".$_GET[contents]."','".$_GET[atd]."','".$chk_name."','".$_POST[text]."',now()");
  }
}
//학년별 사용자 정보 가져오기
$result_user = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".$_GET[season_atd]."' ORDER BY user_name");
while( $row_user = mysqli_fetch_assoc($result_user)){
  if($row_user[user_grade]=='10학년'){
    $chk_grade10[$i] = $row_user[user_name];$i++;
  }if($row_user[user_grade]=='11학년'){
    $chk_grade11[$j] = $row_user[user_name];$j++;
  }if($row_user[user_grade]=='12학년'){
    $chk_grade12[$k] = $row_user[user_name];$k++;
  }if($row_user[user_grade]=='교사'){
    $chk_grade_teacher[$l] = $row_user[user_name];$l++;
  }
}
//제일 큰 배열의 사이즈를 구한다.
$chk_array_size = count($chk_grade10);
if($chk_array_size<count($chk_grade11)){
  $chk_array_size = count($chk_grade11);
}
if($chk_array_size<count($chk_grade12)){
  $chk_array_size = count($chk_grade12);
}
if($chk_array_size<count($chk_grade_teacher)){
  $chk_array_size = count($chk_grade_teacher);
}

//정원
$grade10 = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".$_GET[season_atd]."' AND user_grade = '10학년'"));
$grade11 = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".$_GET[season_atd]."' AND user_grade = '11학년'"));
$grade12 = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".$_GET[season_atd]."' AND user_grade = '12학년'"));
$grade_teacher = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".$_GET[season_atd]."' AND user_grade = '교사'"));
 ?>
<h4><strong>출결 정보</strong></h4>
<!-- Top container -->
<div class="w3-bar w3-theme-l5">
  <a href="index.php?id=System_Atd_Reg" class="w3-bar-item w3-button w3-mobile">등록</a>
  <a href="index.php?id=System_Atd_Result" class="w3-bar-item w3-button w3-mobile">확인</a>
</div>

<h5><strong>확인</strong></h5>
<h6><strong>일정 확인</strong></h6>
<form action="index.php" method="get">
<input class="w3-input" type="hidden" name="id" value="System_Atd_Result">
<input class="w3-input" type="hidden" name="season_atd" value="<?php echo $_GET[season_atd];?>">
<table style="width:100%">
  <tr>
    <th>구분</th>
      <td><select style="width:100%" class="w3-input" name="atd" required>
        <option><?php
        if(isset($_GET[atd]))
          echo $_GET[atd];
        else{
          $_GET[atd]='결석';
          echo '결석';}?>
        </option>
        <option>결석</option>
        <option>출석</option>
      </td>
    <th>일정</th>
      <td>
        <input class="w3-input" type="text" id="datepicker" name = "seek_day" value="<?php
        if($_GET[seek_day]){
          echo $_GET[seek_day];
        }else{
          $_GET[seek_day]=date("Y-m-d");
          echo date("Y-m-d");
        }
        ?>" required>
      </td>
      <td>
        <input class="w3-input" type="submit" name="submit" value="확인">
      </td>
  </tr>
</table>
</form>
<h6><strong>결과 <?php if($_GET[seek_day])echo '['.$_GET[seek_day].']';?></strong></h6>
<table style="width:100%">
  <tr>
    <th>정원</th>
    <td colspan="3">
      <?php echo '10학년('.$grade10.') / 11학년('.$grade11.') / 12학년('.$grade12.') / 교사('.$grade_teacher.') ['.($grade10+$grade11+$grade12+$grade_teacher).']';?>
    </td>
  </tr>
  <tr>
    <th>시간</th>
    <th>
      <?php
        if ($_GET[atd]) {
          echo $_GET[atd];
        }else{
          echo '결석/출석';
        }
       ?>
    </th><th>현재 인원</th><th>출석부</th>
  </tr>
  <?php
  //관리 정보 가져오기
  $k = 0;
  $result_admin = mysqli_query($conn, "SELECT * FROM bs_atd_admin WHERE atd_admin_option = '시간' ORDER BY atd_admin_option,atd_admin_id");
  while( $row_admin = mysqli_fetch_assoc($result_admin)){
  echo
  '<tr>
    <th>'.$row_admin[atd_admin_contents].'</th><td>';
    $result_reg = mysqli_query($conn, "SELECT * FROM bs_atd_reg WHERE atd_reg_atd = '".$_GET[atd]."'");
    while( $row_reg = mysqli_fetch_assoc($result_reg)){
      $day = explode('~',$row_reg[atd_reg_day]);
      $day1 = strtotime($day[0]);
      $day2 = strtotime($day[1]);
      $seek_day = strtotime($_GET[seek_day]);
      while ($day1 <= $day2) {
        if($day1 == $seek_day){
          $seek_name = explode('/',$row_reg[atd_reg_name]);
          $seek_option = explode('/',$row_reg[atd_reg_option]);
          for ($i=0; $i < count($seek_option); $i++) {
            if(strpos($seek_option[$i],$row_admin[atd_admin_contents])!==false){
              for ($j=0; $j < count($seek_name); $j++) {
                if(strpos($seek_name[$j],'10학년')!==false){
                  $cnt_grade10++;
                  $atd_option[$k]=$row_admin[atd_admin_contents];
                  $atd_name[$k]=$seek_name[$j];
                  $atd_contents[$k]=$row_reg[atd_reg_contents];
                  $k++;
                }
                if(strpos($seek_name[$j],'11학년')!==false){
                  $cnt_grade11++;
                  $atd_option[$k]=$row_admin[atd_admin_contents];
                  $atd_name[$k]=$seek_name[$j];
                  $atd_contents[$k]=$row_reg[atd_reg_contents];
                  $k++;
                }
                if(strpos($seek_name[$j],'12학년')!==false){
                  $cnt_grade12++;
                  $atd_option[$k]=$row_admin[atd_admin_contents];
                  $atd_name[$k]=$seek_name[$j];
                  $atd_contents[$k]=$row_reg[atd_reg_contents];
                  $k++;
                }
                if(strpos($seek_name[$j],'교사')!==false){
                  $cnt_teacher++;
                  $atd_option[$k]=$row_admin[atd_admin_contents];
                  $atd_name[$k]=$seek_name[$j];
                  $atd_contents[$k]=$row_reg[atd_reg_contents];
                  $k++;
                }
              }
            }
          }
        }
        $day1 = strtotime("+1 days",$day1);
      }
    }
    if(isset($_GET[atd])){
      echo '10학년('.$cnt_grade10.') / 11학년('.$cnt_grade11.') / 12학년('.$cnt_grade12.') / 교사('.$cnt_teacher.') ['.($cnt_grade10+$cnt_grade11+$cnt_grade12+$cnt_teacher).']';}
      echo '</td>';
    if($_GET[atd]=='출석'){
      echo '<td>10학년('.$cnt_grade10.') / 11학년('.$cnt_grade11.') / 12학년('.$cnt_grade12.') / 교사('.$cnt_teacher.') ['.($cnt_grade10+$cnt_grade11+$cnt_grade12+$cnt_teacher).']</td>';}
    else{
      echo '<td>10학년('.($grade10-$cnt_grade10).') / 11학년('.($grade11-$cnt_grade11).') / 12학년('.($grade12-$cnt_grade12).') / 교사('.($grade_teacher-$cnt_teacher).') ['.($grade10-$cnt_grade10+$grade11-$cnt_grade11+$grade12-$cnt_grade12+$grade_teacher-$cnt_teacher).']</td>';}
      echo '
      <td>
        <form action="index.php" method="get">
          <input class="w3-input" type="hidden" name="id" value="System_Atd_Result">
          <input class="w3-input" type="hidden" name="season_atd" value="'.$_GET[season_atd].'">
          <input class="w3-input" type="hidden" name="seek_day" value="'.$_GET[seek_day].'">
          <input class="w3-input" type="hidden" name="atd" value="'.$_GET[atd].'">
          <input class="w3-input" type="hidden" name="contents" value="'.$row_admin[atd_admin_contents].'">
          <input class="w3-input" type="submit" name="submit" value="보기">
        </form>
      </td>';
      echo '</tr>';
  //변수 초기화
  $cnt_grade10=$cnt_grade11=$cnt_grade12=$cnt_teacher=0;
  }
   ?>
</table>


<?php
if(isset($_GET[contents])){
  echo '<h6><strong>출석부 ['.$_GET[seek_day].'] ('.$_GET[contents].')</strong></h6>';
  echo '
  <table style="width:100%" class="w3-small">
    <tr>
      <th>'.$_GET[atd].'명단</th>
      <td colspan="7" style="text-align:left">';
      $temp = [];$k=0;
      for ($i=0; $i < count($atd_name); $i++) {
        if($atd_option[$i]==$_GET[contents]){
          $temp[$k] = $atd_name[$i];
          $k++;
        }
      }
      sort($temp);//정렬
      $temp = array_unique($temp);//중복제거
      $k=$l=$m=$n=0;
      foreach ($temp as $key => $value) {
        if(strpos($value,'10학년')!==false && $k==0){
          echo '10학년 : ';$k=1;
        }
        if(strpos($value,'11학년')!==false && $l==0){
        if($k==1){echo '<br>';$k = 2;}
          echo '11학년 : ';$l=1;
        }
        if(strpos($value,'12학년')!==false && $m==0){
        if($l==1 || $k==1){echo '<br>';$l = 2;}
          echo '12학년 : ';$m=1;
        }
        if(strpos($value,'교사')!==false && $n==0){
        if($l==1 || $k==1 || $m==1){echo '<br>';$m = 2;}
          echo '교사 : ';$n=1;
        }
        $value = explode(':',$value);
        echo $value[1].' ';
      }
  echo '
      </td>
    </tr>
    <tr>
      <th style="width:15%">10학년<button style="width:100%" onclick="check10()">전체선택</button></th><th style="width:10%">'.$_GET[atd].'사유</th><th style="width:15%">11학년<button style="width:100%" onclick="check11()">전체선택</button></th><th style="width:10%">'.$_GET[atd].'사유</th><th style="width:15%">12학년<button style="width:100%" onclick="check12()">전체선택</button></th><th style="width:10%">'.$_GET[atd].'사유</th><th style="width:15%">교사<button style="width:100%" onclick="check_teacher()">전체선택</button></th><th style="width:10%">'.$_GET[atd].'사유</th>
    </tr>';
    echo '<form id="chk_form" action="index.php?id=System_Atd_Result&season_atd='.$_GET[season_atd].'&seek_day='.$_GET[seek_day].'&atd='.$_GET[atd].'&contents='.$_GET[contents].'" method="post">';
  for ($i=0; $i < $chk_array_size; $i++) {
    echo '<tr><td>';
    if(isset($chk_grade10[$i])){
    echo '<input class="w3-check" type="checkbox" id="grade10" name="chk[]" value="10학년:'.$chk_grade10[$i].'"';
    if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_atd_chk WHERE atd_chk_day = '".$_GET[seek_day]."' AND atd_chk_option = '".$_GET[contents]."' AND atd_chk_atd = '".$_GET[atd]."' AND atd_chk_name LIKE '%10학년:".$chk_grade10[$i]."%'"))){
      echo 'checked';
    }
    echo '>'.$chk_grade10[$i];}
    echo '</td><td>';
    for ($j=0; $j <count($atd_name) ; $j++) {
      if($_GET[contents]==$atd_option[$j] && '10학년:'.$chk_grade10[$i]==$atd_name[$j]){
        echo $atd_contents[$j];
      }
    }
    echo '</td><td>';
    if(isset($chk_grade11[$i])){
    echo '<input class="w3-check" type="checkbox" id="grade11" name="chk[]" value="11학년:'.$chk_grade11[$i].'"';
    if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_atd_chk WHERE atd_chk_day = '".$_GET[seek_day]."' AND atd_chk_option = '".$_GET[contents]."' AND atd_chk_atd = '".$_GET[atd]."' AND atd_chk_name LIKE '%11학년:".$chk_grade11[$i]."%'"))){
      echo 'checked';
    }
    echo '>'.$chk_grade11[$i];}
    echo '</td><td>';
    for ($j=0; $j <count($atd_name) ; $j++) {
      if($_GET[contents]==$atd_option[$j] && '11학년:'.$chk_grade11[$i]==$atd_name[$j]){
        echo $atd_contents[$j];
      }
    }
    echo '</td><td>';
    if(isset($chk_grade12[$i])){
    echo '<input class="w3-check" type="checkbox" id="grade12" name="chk[]" value="12학년:'.$chk_grade12[$i].'"';
    if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_atd_chk WHERE atd_chk_day = '".$_GET[seek_day]."' AND atd_chk_option = '".$_GET[contents]."' AND atd_chk_atd = '".$_GET[atd]."' AND atd_chk_name LIKE '%12학년:".$chk_grade12[$i]."%'"))){
      echo 'checked';
    }
    echo '>'.$chk_grade12[$i];}
    echo '</td><td>';
    for ($j=0; $j <count($atd_name) ; $j++) {
      if($_GET[contents]==$atd_option[$j] && '12학년:'.$chk_grade12[$i]==$atd_name[$j]){
        echo $atd_contents[$j];
      }
    }
    echo '</td><td>';
    if(isset($chk_grade_teacher[$i])){
    echo '<input class="w3-check" type="checkbox" id="grade_teacher" name="chk[]" value="교사:'.$chk_grade_teacher[$i].'"';
    if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_atd_chk WHERE atd_chk_day = '".$_GET[seek_day]."' AND atd_chk_option = '".$_GET[contents]."' AND atd_chk_atd = '".$_GET[atd]."' AND atd_chk_name LIKE '%교사:".$chk_grade_teacher[$i]."%'"))){
      echo 'checked';
    }
    echo '>'.$chk_grade_teacher[$i];}
    echo '</td><td>';
    for ($j=0; $j <count($atd_name) ; $j++) {
      if($_GET[contents]==$atd_option[$j] && '교사:'.$chk_grade_teacher[$i]==$atd_name[$j]){
        echo $atd_contents[$j];
      }
    }
    echo '</td>';
    echo '</tr>';
  }
  $result_chk = mysqli_query($conn, "SELECT * FROM bs_atd_chk WHERE atd_chk_day = '".$_GET[seek_day]."' AND atd_chk_option = '".$_GET[contents]."' AND atd_chk_atd = '".$_GET[atd]."'");
  while( $row_chk = mysqli_fetch_assoc($result_chk)){
    $chk_text = $row_chk[atd_chk_text];
    $chk_author = $row_chk[atd_chk_author];
    $chk_time = $row_chk[atd_chk_created];
  }
  echo '<th>'.$chk_author.'<br>'.$chk_time.'</th><th>메모</th><td colspan="5"><textarea class="w3-input" name="text">'.$chk_text.'</textarea></td><td><input class="w3-input" type="submit" name="submit" value="제출"></td></table>';
}
 ?>
</form>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery.min.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

  <script>
  var chk = 0;
$(function() {
  $("#datepicker").datepicker({
    dateFormat: 'yy-mm-dd',
    prevText: '이전 달',
    nextText: '다음 달',
    monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    dayNames: ['일','월','화','수','목','금','토'],
    dayNamesShort: ['일','월','화','수','목','금','토'],
    dayNamesMin: ['일','월','화','수','목','금','토'],
    showMonthAfterYear: true,
    changeMonth: true,
    changeYear: true,
    yearSuffix: '년'
  });
});

function check10() {
    if(chk==0){
	for(i=0; i < chk_form.grade10.length; i++) {
		chk_form.grade10[i].checked = true;
	}chk=1;}
    else{
	for(i=0; i < chk_form.grade10.length; i++) {
		chk_form.grade10[i].checked = false;
	}chk=0;}
}
function check11() {
    if(chk==0){
	for(i=0; i < chk_form.grade11.length; i++) {
		chk_form.grade11[i].checked = true;
	}chk=1;}
    else{
	for(i=0; i < chk_form.grade11.length; i++) {
		chk_form.grade11[i].checked = false;
	}chk=0;}
}
function check12() {
    if(chk==0){
	for(i=0; i < chk_form.grade12.length; i++) {
		chk_form.grade12[i].checked = true;
	}chk=1;}
    else{
	for(i=0; i < chk_form.grade12.length; i++) {
		chk_form.grade12[i].checked = false;
	}chk=0;}
}
function check_teacher() {
    if(chk==0){
	for(i=0; i < chk_form.grade_teacher.length; i++) {
		chk_form.grade_teacher[i].checked = true;
	}chk=1;}
    else{
	for(i=0; i < chk_form.grade_teacher.length; i++) {
		chk_form.grade_teacher[i].checked = false;
	}chk=0;}
}
</script>

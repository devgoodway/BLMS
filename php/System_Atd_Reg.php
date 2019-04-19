<?php
// 권한 관리
require_once 'php/user_chk.php';

//사용자 정보 가져오기
$result_user = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".substr($_COOKIE['Season'],0,4)."'");
while( $row_user = mysqli_fetch_assoc($result_user)){
  $user_name = $row_user[user_name];
}

//등록 정보 재구성
$atd_reg_author = $name;
$atd_reg_day = $_POST[plan_when].'~'.$_POST[plan_when2];
for ($i=0; $i <count($_POST[atd_reg_option]) ; $i++) {
  $atd_reg_option .= $_POST[atd_reg_option][$i].':';
}
$atd_reg_name = $_POST[atd_reg_user];
$atd_reg_atd = $_POST[atd_reg_atd];
$atd_reg_contents = $_POST[atd_reg_contents];
$atd_reg_text = $_POST[atd_reg_text];

//출결 등록
if($_POST[submit]=='제출'){
  mysqli_query($conn,"INSERT INTO bs_atd_reg (atd_reg_author, atd_reg_day, atd_reg_option, atd_reg_name, atd_reg_atd, atd_reg_contents, atd_reg_text, atd_reg_created) SELECT '".$atd_reg_author."', '".$atd_reg_day."', '".$atd_reg_option."', '".$atd_reg_name."', '".$atd_reg_atd."', '".$atd_reg_contents."', '".$atd_reg_text."',now()");
}elseif ($_POST[submit]=='보기') {
  $result_seek = mysqli_query($conn, "SELECT * FROM bs_atd_reg WHERE atd_reg_id = '".$_POST[atd_reg_id]."'");
  while($row_seek = mysqli_fetch_assoc($result_seek)) {
    $atd_reg_day_seek = explode('~',$row_seek[atd_reg_day]);
    $atd_reg_option_seek = $row_seek[atd_reg_option];
    $atd_reg_name_seek = $row_seek[atd_reg_name];
    $atd_reg_atd_seek = $row_seek[atd_reg_atd];
    $atd_reg_contents_seek = $row_seek[atd_reg_contents];
    $atd_reg_text_seek = $row_seek[atd_reg_text];
  }
}elseif ($_POST[submit]=='수정') {
  mysqli_query($conn, "UPDATE bs_atd_reg SET atd_reg_author = '".$atd_reg_author."', atd_reg_day = '".$atd_reg_day."', atd_reg_option = '".$atd_reg_option."', atd_reg_name = '".$atd_reg_name."', atd_reg_atd = '".$atd_reg_atd."', atd_reg_contents = '".$atd_reg_contents."', atd_reg_text = '".$atd_reg_text."', atd_reg_created = now() WHERE atd_reg_id = '".$_POST[atd_reg_id]."'");
}elseif ($_POST[submit]=='삭제') {
  mysqli_query($conn,"DELETE FROM bs_atd_reg WHERE atd_reg_id ='".$_POST[atd_reg_id]."'");
}
 ?>
<h4><strong>출결 정보</strong></h4>
<!-- Top container -->
<div class="w3-bar w3-theme-l5">
  <a href="index.php?id=System_Atd_Reg" class="w3-bar-item w3-button w3-mobile">등록</a>
  <a href="index.php?id=System_Atd_Result" class="w3-bar-item w3-button w3-mobile">확인</a>
</div>

<h5><strong>등록</strong></h5>
<h6><strong>출결 등록</strong></h6>
<form action="index.php?id=System_Atd_Reg" onsubmit="return confirmDel()" method="post">
<table style="width:100%">
  <tr>
    <th style="width:10%">구분</th><td style="text-align:left">
      <select style="width:100%" class="w3-input" name="atd_reg_atd" required>
        <option><?php echo $atd_reg_atd_seek;?></option>
        <option>결석</option>
        <option>출석</option>
    </td>
  </tr>
  <tr>
    <th style="width:10%">명단</th><td><input class="w3-input" type="text" id="myInput" onkeyup="myFunction()" placeholder="학년 또는 이름을 검색하세요..." title="Type in a name" value="<?php if(isset($atd_reg_name_seek)){echo '수정하시려면 다시 검색하여 넣으세요. : '.$atd_reg_name_seek;}?>"></td>
  </tr>
</table>
<ul class="w3-ul w3-small">
<span id="myOn"></span>
<input type="hidden" id="myList" name="atd_reg_user" value="<?php echo $atd_reg_name_seek;?>" required>
</ul>
<?php
echo '<ul class="w3-ul w3-small" id="myUL">';
//사용자 정보 가져오기
$result_user = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".substr($_COOKIE['Season'],0,4)."' ORDER BY user_grade,user_name");
while( $row_user = mysqli_fetch_assoc($result_user)){
  echo '<li class="w3-button" style="display:none;"><a style=text-decoration:none href="#" onclick="myOnclick(';
  echo "'".$row_user[user_grade].":".$row_user[user_name]."'";
  echo ')">'.$row_user[user_grade].':'.$row_user[user_name].'</a></li>';
}
echo '</ul>';?>
<table style="width:100%">
  <tr>
    <th style="width:20px">일정</th><td><input class="w3-input w3-half" type="text" id="datepicker1" name = "plan_when" value="<?php echo $atd_reg_day_seek[0];?>" placeholder="시작일" required><input class="w3-input w3-half" type="text" id="datepicker2" name = "plan_when2" value="<?php echo $atd_reg_day_seek[1];?>" placeholder="종료일" required></td>
  </tr>
  <tr>
    <th style="width:10%">시간</th>
    <td style="text-align:left">
    <?php
    $result_admin = mysqli_query($conn, "SELECT * FROM bs_atd_admin WHERE atd_admin_option = '시간' ORDER BY atd_admin_id");
    while( $row_admin = mysqli_fetch_assoc($result_admin)){
      echo ' '.$row_admin[atd_admin_contents].' : '.'<input class="w3-check" type="checkbox" name="atd_reg_option[]" value="'.$row_admin[atd_admin_contents].'"';
       if(isset($atd_reg_option_seek)){
         if(strpos($atd_reg_option_seek,$row_admin[atd_admin_contents])!==false){
           echo 'checked';
         }
       }else{
         echo 'checked';
       }
      echo '>';
    }
    mysqli_free_result($result_admin);
     ?>
     </td>
  </tr>
  <tr>
    <th style="width:10%">사유</th>
    <td>
      <select class="w3-input" name="atd_reg_contents" required>
        <option><?php echo $atd_reg_contents_seek;?></option>
    <?php
    $result_admin = mysqli_query($conn, "SELECT * FROM bs_atd_admin WHERE atd_admin_option = '사유'");
    while( $row_admin = mysqli_fetch_assoc($result_admin)){
      echo '<option>'.$row_admin[atd_admin_contents].'</option>';
    }
    mysqli_free_result($result_admin);
     ?>
   </select>
    </td>
  </tr>
  <tr>
    <th style="width:10%">메모</th><td><input class="w3-input" type="text" name="atd_reg_text" value="<?php echo $atd_reg_text_seek;?>"></td>
  </tr>
  <tr>
    <th colspan="2">
      <?php
      if(isset($atd_reg_day_seek)){
        echo '<input class="w3-input w3-half" type="hidden" name="atd_reg_id" value="'.$_POST[atd_reg_id].'"><input class="w3-input w3-half  w3-theme-l3" type="submit" name="submit" value="수정"><input class="w3-input w3-half w3-theme-d3" type="submit" name="submit" value="삭제">';
      }else{
        echo '<input class="w3-input" type="submit" name="submit" value="제출">';
      }
       ?>
    </th>
  </tr>
</table>
</form>

<h6><strong>출결 등록 확인</strong></h6>
  <table style="width:100%" class="w3-small">
    <tr>
      <th style="width:10%">작성자</th><th style="width:5%">구분</th><th style="width:20%">명단</th><th style="width:10%">일정</th><th>시간</th><th style="width:10%">사유</th><th>메모</th><th style="width:10%">작성시간</th><th>보기</th>
    </tr>
    <?php
  //관리 정보 가져오기
  $result_reg = mysqli_query($conn, "SELECT * FROM bs_atd_reg ORDER BY atd_reg_id DESC");
  while( $row_reg = mysqli_fetch_assoc($result_reg)){
    echo
    '<tr>
      <td>'.$row_reg[atd_reg_author].'</td>
      <td>'.$row_reg[atd_reg_atd].'</td>
      <td style="text-align:left">';
      $temp = explode('/',$row_reg[atd_reg_name]);
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
      echo '</td>
      <td>'.$row_reg[atd_reg_day].'</td>
      <td>';
      $temp = explode(':',$row_reg[atd_reg_option]);
      if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_atd_admin WHERE atd_admin_option = '시간'")) == (count($temp)-1)){
        echo '모든 시간';
      }else{
        foreach ($temp as $key => $value) {
          echo $value.' ';
        }
      }
      echo '</td>
      <td>'.$row_reg[atd_reg_contents].'</td>
      <td>'.$row_reg[atd_reg_text].'</td>
      <td>'.$row_reg[atd_reg_created].'</td>
      <td><form action="index.php?id=System_Atd_Reg" method="post"><input class="w3-input" type="hidden" name="atd_reg_id" value="'.$row_reg[atd_reg_id].'"><input class="w3-input" type="submit" name="submit" value="보기"></form></td>
    </tr>';
  }
     ?>
  </table>
</form>
<script>
var namelist = new Array();
function myFunction() {
  // Declare variables
  var input, filter, ul, li, a, i, txtValue, inputText;
  inputText = document.getElementById('myInput').value;
  input = document.getElementById('myInput');
  filter = input.value.toUpperCase();
  ul = document.getElementById("myUL");
  li = ul.getElementsByTagName('li');

  // Loop through all list items, and hide those who don't match the search query
  if(inputText == ''){
    li[i].style.display = "none";
  }else{
  for (i = 0; i < li.length; i++) {
    a = li[i].getElementsByTagName("a")[0];
    txtValue = a.textContent || a.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      li[i].style.display = "";
    } else {
      li[i].style.display = "none";
    }
  }
}
}


function myOnclick(a) {
  var temp = '';
  var list = '';
  namelist.push(a);
  for (var i = 0; i < namelist.length; i++) {
    temp += '<li class="w3-button w3-theme-l3"><a style=text-decoration:none href="#" onclick="mySplice(';
	temp += "'"+namelist[i]+"'";
	temp += ')">'+namelist[i]+' ×</a></li>';
  list += namelist[i]+"/";
  }
  document.getElementById("myOn").innerHTML = temp;
  document.getElementById("myList").value = list;
}


function mySplice(a) {
  var temp = '';
  var list = '';
  var idx = namelist.findIndex((item,idx)=>{
    return item === a;
  });
  namelist.splice(idx,1);
  for (var i = 0; i < namelist.length; i++) {
    temp += '<li class="w3-button w3-theme-l3"><a style=text-decoration:none href="#" onclick="mySplice(';
	temp += "'"+namelist[i]+"'";
	temp += ')">'+namelist[i]+' ×</a></li>';
  list += namelist[i]+"/";
  }
  document.getElementById("myOn").innerHTML = temp;
  document.getElementById("myList").value = list;
}
</script>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery.min.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

  <script>
$(function() {
  $("#datepicker1").datepicker({
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
  $("#datepicker2").datepicker({
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

function confirmDel() {
        var r = confirm("정말 실행하시겠습니까?");
        if (r == true) {
        } else {
        return false;
        }
}
</script>

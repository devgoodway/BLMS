<?php
// 권한 관리
require_once 'php/user_chk.php';
//사용자 검색
if(isset($_POST[user_search])){
  $user_search = explode(":",$_POST[user_search]);
  $result_users = mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".$user_search[0]."' AND user_email = '".$user_search[1]."' ORDER BY user_season, user_grade, user_name");
while($row_users = mysqli_fetch_assoc($result_users)){
  $user_id = $row_users[user_id];
  $user_season = $row_users[user_season];
  $user_email = $row_users[user_email];
  $user_grade = $row_users[user_grade];
  $user_name = $row_users[user_name];
  $user_adv = $row_users[user_adv];
}
}
//사용자 등록&삭제
if($_POST[submit]=='신규'){
  mysqli_query($conn, "INSERT INTO bs_users(user_season,user_1q,user_2q,user_3q,user_4q,user_email,user_grade,user_name,user_adv,user_ab_unauth,user_ab_etc,user_late_dis,user_late_unauth,user_late_etc,user_early_dis,user_early_unauth,user_early_etc,user_skip_dis,user_skip_unauth,user_skip_etc,user_created) SELECT '".$_POST[user_season]."','대기중','대기중','대기중','대기중','".$_POST[user_email]."','".$_POST[user_grade]."','".$_POST[user_name]."','".$_POST[user_adv]."','ㆍ','ㆍ','ㆍ','ㆍ','ㆍ','ㆍ','ㆍ','ㆍ','ㆍ','ㆍ','ㆍ',now()");
}else if($_POST[submit]=='수정'){
  mysqli_query($conn, "UPDATE bs_users SET user_season = '".$_POST[user_season]."',user_1q = '대기중',user_2q = '대기중',user_3q = '대기중',user_4q = '대기중',user_email = '".$_POST[user_email]."',user_grade = '".$_POST[user_grade]."',user_name = '".$_POST[user_name]."',user_adv = '".$_POST[user_adv]."' WHERE user_id='".$_POST[user_id]."'");
}else if($_POST[submit]=='삭제'){
  mysqli_query($conn, "DELETE FROM bs_users WHERE user_id = '".$_POST[user_id]."'");
}

//사진 등록
$user_num = explode('@',$user_email);
// 설정
if(isset($_FILES['myfile'])){
$uploads_dir = './photo';
$allowed_ext = array('jpg');

// 변수 정리
$error = $_FILES['myfile']['error'];
$name = $_FILES['myfile']['name'];
$ext = array_pop(explode('.', $name));

// 오류 확인
if( $error != UPLOAD_ERR_OK ) {
	switch( $error ) {
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			echo "파일이 너무 큽니다. ($error)";
			break;
		case UPLOAD_ERR_NO_FILE:
			echo "파일이 첨부되지 않았습니다. ($error)";
			break;
		default:
			echo "파일이 제대로 업로드되지 않았습니다. ($error)";
	}
	exit;
}

// 확장자 확인
if( !in_array($ext, $allowed_ext) ) {
	echo "허용되지 않는 확장자입니다.";
	exit;
}

// 파일 이동
move_uploaded_file( $_FILES['myfile']['tmp_name'], "$uploads_dir/$user_num[0].$ext");

// 파일 정보 출력
echo "<h2>파일 정보</h2>
<ul>
	<li>파일명: $user_num[0].$ext</li>
	<li>확장자: $ext</li>
	<li>파일형식: {$_FILES['myfile']['type']}</li>
	<li>파일크기: {$_FILES['myfile']['size']} 바이트</li>
</ul>
<h3>※ 사진이 바로 반영이 안될 경우 캐쉬를 지우고 확인하세요.</h3>";}
 ?>

<h5><strong>사용자<strong><h5>
  <form class="w3-form" action="index.php?id=Admin_Main&admin=Admin_User" method="post">
    <select class="w3-select w3-small" name="user_search" onchange="form.submit()">
      <?php
  if(isset($user_id)){
    echo "<option value='".$user_season.':'.$user_email."'>".$user_season.'/'.$user_grade.'/'.$user_name."</option>";
  }
    echo "<option value='new'>신규</option>";
      $result_users = mysqli_query($conn, "SELECT * FROM bs_users ORDER BY user_season DESC, user_grade, user_name");
      while($row_users = mysqli_fetch_assoc($result_users)){
        echo '<option value="'.$row_users[user_season].':'.$row_users[user_email].'">'.$row_users[user_season].'/'.$row_users[user_grade].'/'.$row_users[user_name].'</option>';
      }?>
    </select>
  </form>
<div class="w3-quarter w3-center">
    <img src="photo/<?php echo $user_num[0];?>.jpg" alt="" style="height:190px">
</div>
<div class="w3-rest">
  <form class="w3-form" action="index.php?id=Admin_Main&admin=Admin_User" method="post">
    <?php echo '<input type="hidden" name="user_id" value="'.$user_id.'">'; ?>
    <table class="w3-small w3-card-4 w3-bordered w3-centered" style="width:100%">
      <tr>
      <th>학년도</th><td>
        <select class="w3-select" name="user_season">
          <?php echo '<option value="'.$user_season.'">'.$user_season.'학년도</option>'; ?>
          <option value="2020">2020학년도</option>
          <option value="2019">2019학년도</option>
          <option value="2018">2018학년도</option>
          <option value="2017">2017학년도</option>
          <option value="2016">2016학년도</option>
        </select>
      </td>
      <th>이메일</th><td><?php echo '<input class="w3-input" type="text" name="user_email" value="'.$user_email.'">'; ?></td>
    </tr>
        <th>학년</th><td>
        <select class="w3-select" name="user_grade">
          <?php echo '<option value="'.$user_grade.'">'.$user_grade.'</option>'; ?>
          <option value="10학년">10학년</option>
          <option value="11학년">11학년</option>
          <option value="12학년">12학년</option>
          <option value="교사">교사</option>
        </select>
      </td><div class="w3-half">

      </div>
      <th>이름</th><td><?php echo '<input class="w3-input" type="text" name="user_name" value="'.$user_name.'">'; ?></td>
    </tr><tr>
      <th>어드바이저</th><td><?php echo '<input class="w3-input" type="text" name="user_adv" value="'.$user_adv.'">'; ?></td>
      <td colspan='2'><div class="w3-half"><input class="w3-input w3-theme-l2" type="submit" name="submit" value="<?php if($user_email){echo '수정';}else{echo '신규';}?>"></div><div class="w3-half"><input class="w3-input w3-theme-d2" type="submit" name="submit" value="삭제"></td></div></tr>
        </form>
        <?php
        if(isset($_POST[user_search])){
          echo "<tr><td colspan='2'><form enctype='multipart/form-data' action='index.php?id=Admin_Main&admin=Admin_User' method='post'>";
          echo "<input type='hidden' name='user_search' value='".$user_season.":".$user_email."'>";
          echo "<input class='w3-input' type='file' name='myfile'></td><td colspan='2'><button class='w3-input w3-theme'>사진 업로드</button></td></tr></form>";
        }
         ?>
    </table>
</div>
  <br>

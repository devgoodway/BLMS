<?php
// 권한 관리
require_once 'php/user_chk.php';
//개설교과 카운트
$count_plan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_plan"));
//수강신청 카운트
$count_apply = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_apply"));
//교육과정 카운트
$count_curriculum = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_curriculum"));
//사용자 카운트
$count_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_users WHERE user_season = '".substr($_COOKIE['Season'],0,4)."'"));
//교과 개설 비율
$i = 0; //count 변수
$result_cur = mysqli_query($conn, "SELECT * FROM bs_curriculum GROUP BY curriculum_cur");
while($row_cur = mysqli_fetch_assoc($result_cur)){$data_cur[$i] = $row_cur[curriculum_cur];$i++;}
for($j=0;$j<$i;$j++){$count_cur[$j] = array($data_cur[$j],mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bs_plan WHERE plan_sub LIKE '%".$data_cur[$j]."%' AND plan_season LIKE '%2018%'")));}
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main">

<!-- Header -->
<header class="w3-container" style="padding-top:22px">
  <h5><b><i class="fa fa-dashboard"></i> 누적통계</b></h5>
</header>

<div class="w3-row-padding w3-margin-bottom">
  <div class="w3-quarter">
    <div class="w3-container w3-red w3-padding-16">
      <div class="w3-left"><i class="fa fa-comment w3-xxxlarge"></i></div>
      <div class="w3-right">
        <h3><?php echo number_format($count_plan);?></h3>
      </div>
      <div class="w3-clear"></div>
      <h4>개설교과</h4>
    </div>
  </div>
  <div class="w3-quarter">
    <div class="w3-container w3-blue w3-padding-16">
      <div class="w3-left"><i class="fa fa-eye w3-xxxlarge"></i></div>
      <div class="w3-right">
        <h3><?php echo number_format($count_apply);?></h3>
      </div>
      <div class="w3-clear"></div>
      <h4>수강신청</h4>
    </div>
  </div>
  <div class="w3-quarter">
    <div class="w3-container w3-teal w3-padding-16">
      <div class="w3-left"><i class="fa fa-share-alt w3-xxxlarge"></i></div>
      <div class="w3-right">
        <h3><?php echo number_format($count_curriculum);?></h3>
      </div>
      <div class="w3-clear"></div>
      <h4>교육과정</h4>
    </div>
  </div>
  <div class="w3-quarter">
    <div class="w3-container w3-orange w3-text-white w3-padding-16">
      <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
      <div class="w3-right">
        <h3><?php echo number_format($count_users);?></h3>
      </div>
      <div class="w3-clear"></div>
      <h4>사용자</h4>
    </div>
  </div>
</div>

<div class="w3-container">

    <h5><b><i class="fa fa-dashboard"></i> 교과개설수</b></h5>
      <div id="piechart"></div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
  var arr = <?= json_encode($count_cur) ?>;
  var data = google.visualization.arrayToDataTable([
  ['교과','개설수'],arr[0],arr[1],arr[2],arr[3],arr[4],arr[5],arr[6],arr[7],arr[8],arr[9],arr[10],arr[11],arr[12],arr[13],arr[14],arr[15],arr[16],arr[17],arr[18],arr[19]
  ]);

  // Optional; add a title and set the width and height of the chart
  var options = {'title':'교과개설수', 'width':550, 'height':550};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.BarChart(document.getElementById('piechart'));
  chart.draw(data, options);
}
</script>

</div>
</div>

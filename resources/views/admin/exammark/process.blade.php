<!doctype html>
<html>
  <head>
  <title>Bar Chart</title>
  <script src="https://www.chartjs.org/dist/2.7.3/Chart.bundle.js"></script>
  <!-- <script src="http://www.chartjs.org/samples/latest/utils.js"></script> -->
  <style>
  canvas {
    -moz-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
  }
  </style>
  </head>
  <body>
    <div id="container" style="width: 75%;">
    <canvas id="examCanvas"></canvas>
    </div>
    <script>
   var examOneData = {
  label: <?php echo json_encode($examone); ?>,
  data:<?php echo json_encode($marksone); ?>,
  backgroundColor: 'rgba(0, 99, 132, 0.6)',
  borderColor: 'rgba(0, 99, 132, 1)',
  barThickness: 0.5,
  barPercentage: 5,
  minBarLength: 2,
 
};

  var examOneAverage = {
    //console.log(<?php echo json_encode($examone); ?>);
  label: <?php echo json_encode("Class Average"); ?>,
  data:<?php echo json_encode($examOneAverage); ?>,
  backgroundColor: "white",
  //borderColor: "rgba(255, 99, 132)",
   borderColor: 'rgba(0, 99, 132, 1)',
  barThickness: 0.5,
  barPercentage: 5,
  minBarLength: 2,
  type:'line',
 
};


var examTwoAverage = {
  label: <?php echo json_encode('Class Average'); ?>,
  data:<?php echo json_encode($examTwoAverage); ?>,
  backgroundColor: "white",
   borderColor: 'rgba(99, 132, 0, 1)',
  //borderColor: "rgb(54, 162, 235)",
  barThickness: 2,
  barPercentage: 0.5,
  minBarLength: 2,
  type:'line',

 
};

var examTwoData = {
  label: <?php echo json_encode($examtwo); ?>,
  data:<?php echo json_encode($markstwo); ?>,
  backgroundColor: 'rgba(99, 132, 0, 0.6)',
  borderColor: 'rgba(99, 132, 0, 1)',
  barThickness: 2,
  barPercentage: 0.5,
  minBarLength: 2,

 
};
 
var examData = {
  labels: <?php echo json_encode($subjects); ?>,
  datasets: [examTwoData,examOneData,examTwoAverage,examOneAverage]
};
 console.log(examData);
var chartOptions = {
   scales: {
    yAxes: [{
    ticks: {
    beginAtZero:true
    }
    }],
     xAxes: [{
                // Change here
              barPercentage: 0.5
            }]
    }
};
 
var barChart = new Chart(examCanvas, {
  type: 'bar',
  data: examData,
  options: chartOptions
});
    
    </script>
  </body>
</html>
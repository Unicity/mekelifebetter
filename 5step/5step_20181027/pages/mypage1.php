<?php session_start();?>
<?php  include_once("../inc/function.php");?>
<?php 
	include "../inc/dbconn.php";
	checkSessionValue();
	$userName=$_SESSION["username"];
	
	$query = "select TotalCholesterol,LDL,HDL,Triglyceride,BloodSugar from BloodResult where id ='".$userName."' and createdate = '2017-10-13 10:40:45'";
	
	$query_result = mysql_query($query);
	
	$query_row = mysql_fetch_array($query_result);
	
	$query1 = "select TotalCholesterol,LDL,HDL,Triglyceride,BloodSugar from BloodResult where id ='".$userName."' and createdate = '2017-12-29 00:00:00'";
	$query_result1 = mysql_query($query1);
	$query_row1 = mysql_fetch_array($query_result1);
	
	$TotalCholesterol1= $query_row1["TotalCholesterol"];
	$LDL1= $query_row1["LDL"];
	$HDL1= $query_row1["HDL"];
	$Triglyceride1= $query_row1["Triglyceride"];
	$BloodSugar1= $query_row1["BloodSugar"];
	
	$TotalCholesterol1 = floatval(preg_replace("/([^\d\.])/i", "", $TotalCholesterol1));
	$LDL1 = floatval(preg_replace("/([^\d\.])/i", "", $LDL1));
	$HDL1 = floatval(preg_replace("/([^\d\.])/i", "", $HDL1));
	$Triglyceride1 = floatval(preg_replace("/([^\d\.])/i", "", $Triglyceride1));
	$BloodSugar1 = floatval(preg_replace("/([^\d\.])/i", "", $BloodSugar1));
	
	//echo $TotalCholesterol1;
	
	$TotalCholesterol= $query_row["TotalCholesterol"];
	$LDL= $query_row["LDL"];
	$HDL= $query_row["HDL"];
	$Triglyceride= $query_row["Triglyceride"];
	$BloodSugar= $query_row["BloodSugar"];
	
	$TotalCholesterol = floatval(preg_replace("/([^\d\.])/i", "", $TotalCholesterol));
	$LDL = floatval(preg_replace("/([^\d\.])/i", "", $LDL));
	$HDL = floatval(preg_replace("/([^\d\.])/i", "", $HDL));
	$Triglyceride = floatval(preg_replace("/([^\d\.])/i", "", $Triglyceride));
	$BloodSugar = floatval(preg_replace("/([^\d\.])/i", "", $BloodSugar));
	
?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="ScreenOrientation" content="autoRotate:disabled">
    <meta name="description" content="">
    <meta name="author" content="">

	<title>5 Step</title>
    
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

    <!-- Custom styles for this template -->
<script src="https://d3js.org/d3.v4.min.js"></script>
    <link href="../css/billboard.css" rel="stylesheet">
    <script type="text/javascript" src="../js/spinner/spin.js"></script>
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<!--  <script type="text/javascript" src="../js/jquery.canvasjs.min.js"></script>-->
	<script type="text/javascript" src="../js/billboard.js"></script> 

  	<script type="text/javascript">
  	
	function afterResult (){
/*
		CanvasJS.addColorSet("greenShades",
	             [//colorSet Array

	             "#2F4F4F",
	             "#008080",
	             "#2E8B57",

	             "#90EE90"                
	             ]);
		
		$("#chartContainer1").CanvasJSChart({ 
			animationEnabled: true,
			backgroundColor: "#F5DEB3",
			colorSet: "greenShades",
			title: { 
				text: "섭취후 결과",
				 fontFamily: "gulim",
				 fontStyle: "italic",
				 fontWeight: "bold",
				 padding: 5,
				 borderThickness: 1,
				 borderColor: "green",
				 
				fontSize: 24
			}, 
			axisY: { 
				title: "Products in %" 
			}, 
			legend :{ 
				 fontStyle: "italic",
				verticalAlign: "center", 
				horizontalAlign: "right"
			
			}, 
			data: [ 
					{ 
						type: "pie", 
						showInLegend: true, 
						indexLabelFontSize: 15,
						toolTipContent: "{label} <br/> {y} %", 
						indexLabel: "{y} %", 
						indexLabelPlacement: "inside",
						dataPoints: [ 
										{ label: "유익균(비피도박테리움)",  y: 21, legendText: "유익균(비피도박테리움)"}, 
										{ label: "유익균(락토바실러스)",   y: 17, legendText: "유익균(락토바실러스)"  }, 
										{ label: "중간균(박테로이데스)",   y: 5.5,  legendText: "중간균(박테로이데스)" }, 
										{ label: "유해균(글로스트리튬)",   y: 2.3,  legendText: "유해균(글로스트리튬)"}
									] 
					} 
				] 
		}); 
*/

		var chart = bb.generate({
			  data: {
			    columns: [
				["data1", 30],
				["data2", 120]
			    ],
			    type: "pie",
			    onclick: function (d, i) {
				console.log("onclick", d, i);
			    },
			    onover: function (d, i) {
				console.log("onover", d, i);
			    },
			    onout: function (d, i) {
				console.log("onout", d, i);
			    }
			  },
			  bindto: "#PieChart"
			});

			setTimeout(function() {
				chart.load({
					columns: [
						["setosa", 0.2, 0.2, 0.2, 0.2, 0.2, 0.4, 0.3, 0.2, 0.2, 0.1, 0.2, 0.2, 0.1, 0.1, 0.2, 0.4, 0.4, 0.3, 0.3, 0.3, 0.2, 0.4, 0.2, 0.5, 0.2, 0.2, 0.4, 0.2, 0.2, 0.2, 0.2, 0.4, 0.1, 0.2, 0.2, 0.2, 0.2, 0.1, 0.2, 0.2, 0.3, 0.3, 0.2, 0.6, 0.4, 0.3, 0.2, 0.2, 0.2, 0.2],
						["versicolor", 1.4, 1.5, 1.5, 1.3, 1.5, 1.3, 1.6, 1.0, 1.3, 1.4, 1.0, 1.5, 1.0, 1.4, 1.3, 1.4, 1.5, 1.0, 1.5, 1.1, 1.8, 1.3, 1.5, 1.2, 1.3, 1.4, 1.4, 1.7, 1.5, 1.0, 1.1, 1.0, 1.2, 1.6, 1.5, 1.6, 1.5, 1.3, 1.3, 1.3, 1.2, 1.4, 1.2, 1.0, 1.3, 1.2, 1.3, 1.3, 1.1, 1.3],
						["virginica", 2.5, 1.9, 2.1, 1.8, 2.2, 2.1, 1.7, 1.8, 1.8, 2.5, 2.0, 1.9, 2.1, 2.0, 2.4, 2.3, 1.8, 2.2, 2.3, 1.5, 2.3, 2.0, 2.0, 1.8, 2.1, 1.8, 1.8, 1.8, 2.1, 1.6, 1.9, 2.0, 2.2, 1.5, 1.4, 2.3, 2.4, 1.8, 1.8, 2.1, 2.4, 2.3, 1.9, 2.3, 2.5, 2.3, 1.9, 2.0, 2.3, 1.8],
					]
				});
			}, 1500);

			setTimeout(function() {
				chart.unload({
					ids: 'data1'
				});
				chart.unload({
					ids: 'data2'
				});
			}, 2500);
	}

  	    
  	</script>
</head>
<body>
	<!--  <div id="chartContainer1" style="height: 300px; width: 100%;"></div>-->
	<div id="PieChart"></div>	
</body>
</html>

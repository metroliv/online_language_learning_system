<?php
/**
 *  Bar chart data
 * @var mixed
 */

$rev_streams = [];
$rev_targets = [];
$rev_collections = [];
$collectors = [];
$collorPerformance = [];
$collorCollections = [];
$colors = [];

		/**
		 * @var mixed
		 */
		$fetch_records_sql = mysqli_query(
			$mysqli,
			"SELECT * FROM revenue_streams"
		);
		if (mysqli_num_rows($fetch_records_sql) > 0) {
			$cnt =  1;
			$total_target = 0;
			$total_defecit = 0;
			$total_stream_target = 0;
			$total_monthly_target = 0;
			$total_collections = 0;

			while ($rows = mysqli_fetch_array($fetch_records_sql)) 
			{
				array_push($rev_streams, $rows['stream_name']);													
															
				/** target amount */
				$query = "SELECT SUM(info_amount) FROM collectortarget_info ci
				INNER JOIN collectortarget ct ON ct.collectortarget_id = ci.info_collectortarget_id
				INNER JOIN revenue_streams rs ON rs.stream_id = ci.info_stream_id
				INNER JOIN financial_year fy ON fy.fy_id = ct.collectortarget_fy
				WHERE ci.info_stream_id = '{$rows['stream_id']}'
				AND fy.fy_id = '{$fy}'
				AND ct.collectortarget_ward_id = '{$_SESSION['user_ward_id']}'
				AND ct.collectortarget_month IN ({$months})
				";
				$stmt = $mysqli->prepare($query);
				$stmt->execute();
				$stmt->bind_result($target);
				$stmt->fetch();
				$stmt->close();

				/** Defecit amount */
				$query = "SELECT SUM(info_defecit) FROM collectortarget_info ci
				INNER JOIN collectortarget ct ON ct.collectortarget_id = ci.info_collectortarget_id
				INNER JOIN revenue_streams rs ON rs.stream_id = ci.info_stream_id
				INNER JOIN financial_year fy ON fy.fy_id = ct.collectortarget_fy
				WHERE ci.info_stream_id = '{$rows['stream_id']}'
				AND fy_status = '1'
				AND ct.collectortarget_ward_id = '{$_SESSION['user_ward_id']}'
				AND ct.collectortarget_month IN ({$months})
				";
				$stmt = $mysqli->prepare($query);
				$stmt->execute();
				$stmt->bind_result($defecit);
				$stmt->fetch();
				$stmt->close();

				//stream Target
				array_push($rev_targets, ($target+$defecit));

																		
				/** Total collections */
				$query = "SELECT SUM(collection_amount) FROM revenue_collections rc
				INNER JOIN financial_year fy ON rc.collections_fy = fy.fy_id
				WHERE collection_ward_id = '{$_SESSION['user_ward_id']}'
				AND collection_stream_id = '{$rows['stream_id']}'
				AND collection_status = 'Approved' 
				AND fy.fy_status = '1'																														AND collections_month IN ({$months})
				AND collections_month IN ({$months})

				";

				$stmt = $mysqli->prepare($query);
				$stmt->execute();
				$stmt->bind_result($stream_collections);
				$stmt->fetch();
				$stmt->close();

				if ($stream_collections > 0) {
					array_push($rev_collections, $stream_collections);
				} else {
					array_push($rev_collections, 0);
				};

				$target = 0;
				$defecit = 0;
			}
		}
		

																		
													

/**
 * @var mixed
 */
$fetch_records_sql = mysqli_query(
	$mysqli,
	"SELECT * FROM users u 
	WHERE user_access_level IN ('Revenue Collector', 'Ward Receiver')
	AND user_ward_id = '{$_SESSION['user_ward_id']}'
	"
);
if (mysqli_num_rows($fetch_records_sql) > 0) {
	$cnt =  1;
	while ($rows = mysqli_fetch_array($fetch_records_sql)) {
		array_push($collectors, $rows["user_names"]);

		// calcating % performance
		$query = "SELECT SUM(collection_amount) FROM revenue_collections 
				WHERE collection_ward_id = '{$_SESSION['user_ward_id']}'
				AND collection_user_id = '{$rows['user_id']}'
				AND collection_status = 'Approved' 
				AND collections_month IN ({$months})
				";

		$stmt = $mysqli->prepare($query);
		$stmt->execute();
		$stmt->bind_result($stream_collections);
		$stmt->fetch();
		$stmt->close();

		// Collections
		if($stream_collections>0){
			array_push($collorCollections, $stream_collections);
		}else{
			array_push($collorCollections, 1);
		}

		// Percentage
		$achiedPercentage = ($stream_collections*100);
		if($collectorTarget>0){
			$res = floor($achiedPercentage / $collectorTarget);
			array_push($collorPerformance, $res);														
		}else{
			array_push($collorPerformance, 0);											
		}

		// getting random color
		array_push($colors, getRandomRGBColor());
	}
}

?>


<!-- FLOT CHARTS -->
<script src="../public/plugins/flot/jquery.flot.js"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="../public/plugins/flot/plugins/jquery.flot.resize.js"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="../public/plugins/flot/plugins/jquery.flot.pie.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../public/dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
	$(function() {
		/* ChartJS
		 * -------
		 * Here we will create a few charts using ChartJS
		 */

		//--------------
		//- AREA CHART -
		//--------------

		// Get context with jQuery - using jQuery's .get() method.
		var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

		var areaChartData = {
			labels: <?php echo json_encode($rev_streams); ?>,
			datasets: [{
					label: 'Collections',
					backgroundColor: 'rgba(60,141,188,0.9)',
					borderColor: 'rgba(60,141,188,0.8)',
					pointRadius: false,
					pointColor: '#3b8bba',
					pointStrokeColor: 'rgba(60,141,188,1)',
					pointHighlightFill: '#fff',
					pointHighlightStroke: 'rgba(60,141,188,1)',
					data: <?php echo json_encode($rev_collections) ?>
				},
				{
					label: 'Target',
					backgroundColor: 'rgba(210, 214, 222, 1)',
					borderColor: 'rgba(210, 214, 222, 1)',
					pointRadius: false,
					pointColor: 'rgba(210, 214, 222, 1)',
					pointStrokeColor: '#c1c7d1',
					pointHighlightFill: '#fff',
					pointHighlightStroke: 'rgba(220,220,220,1)',
					data: <?php echo json_encode($rev_targets); ?>
				},
			]
		}

		var areaChartOptions = {
			maintainAspectRatio: false,
			responsive: true,
			legend: {
				display: false
			},
			scales: {
				xAxes: [{
					gridLines: {
						display: false,
					}
				}],
				yAxes: [{
					gridLines: {
						display: false,
					}
				}]
			}
		}

		// This will get the first returned node in the jQuery collection.
		new Chart(areaChartCanvas, {
			type: 'line',
			data: areaChartData,
			options: areaChartOptions
		})

		//-------------
		//- LINE CHART -
		//--------------
		var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
		var lineChartOptions = $.extend(true, {}, areaChartOptions)
		var lineChartData = $.extend(true, {}, areaChartData)
		lineChartData.datasets[0].fill = false;
		lineChartData.datasets[1].fill = false;
		lineChartOptions.datasetFill = false

		var lineChart = new Chart(lineChartCanvas, {
			type: 'line',
			data: lineChartData,
			options: lineChartOptions
		})

		//-------------
		//- DONUT CHART -
		//-------------
		// Get context with jQuery - using jQuery's .get() method.
		var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
		var donutData = {
			labels: <?php echo json_encode($collectors); ?>,
			datasets: [{
				data: <?php echo json_encode($collorCollections); ?>,
				backgroundColor: <?php echo json_encode($colors) ?>,
			}]
		}
		var donutOptions = {
			maintainAspectRatio: false,
			responsive: true,
		}
		//Create pie or douhnut chart
		// You can switch between pie and douhnut using the method below.
		new Chart(donutChartCanvas, {
			type: 'doughnut',
			data: donutData,
			options: donutOptions
		})

		//-------------
		//- PIE CHART -
		//-------------
		// Get context with jQuery - using jQuery's .get() method.
		var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
		var pieData = donutData;
		var pieOptions = {
			maintainAspectRatio: false,
			responsive: true,
		}
		//Create pie or douhnut chart
		// You can switch between pie and douhnut using the method below.
		new Chart(pieChartCanvas, {
			type: 'pie',
			data: pieData,
			options: pieOptions
		})

		//-------------
		//- BAR CHART -
		//-------------
		var barChartCanvas = $('#barChart').get(0).getContext('2d')
		var barChartData = $.extend(true, {}, areaChartData)
		var temp0 = areaChartData.datasets[0]
		var temp1 = areaChartData.datasets[1]
		barChartData.datasets[0] = temp1
		barChartData.datasets[1] = temp0

		var barChartOptions = {
			responsive: true,
			maintainAspectRatio: false,
			datasetFill: false
		}

		new Chart(barChartCanvas, {
			type: 'bar',
			data: barChartData,
			options: barChartOptions
		})

		//---------------------
		//- STACKED BAR CHART -
		//---------------------
		var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
		var stackedBarChartData = $.extend(true, {}, barChartData)

		var stackedBarChartOptions = {
			responsive: true,
			maintainAspectRatio: false,
			scales: {
				xAxes: [{
					stacked: true,
				}],
				yAxes: [{
					stacked: true
				}]
			}
		}

		new Chart(stackedBarChartCanvas, {
			type: 'bar',
			data: stackedBarChartData,
			options: stackedBarChartOptions
		})
	})
</script>
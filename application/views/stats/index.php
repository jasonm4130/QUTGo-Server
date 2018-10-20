<!-- Page Content Holder -->
<div id="content">

	<!-- Graphs and Stuff -->
	<div class="row">
		<div class="col-md-8 col-sm-12">
			<canvas id="lineChart"></canvas>
		</div>
		<div class="col-md-4 col-sm-6">
			<canvas id="radarChart"></canvas>
		</div>
		<div class="col-12">
			<canvas id="stackedChart"></canvas>
		</div>
	</div>

	<!-- <?php var_dump($friends); ?> -->



</div>
</div>

<script>
	function stepNumber(min, max) {
		return Math.floor((Math.random() * max) + min);
	}

	var ctx = document.getElementById("lineChart").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: ["Mon", "Tues", "Wed", "Thurs", "Fri", "Sat", "Sun"],
			datasets: [{
				label: 'This Week',
				data: [
					<?php foreach($thisWeek as $day){
							$key = array_search($day, array_column($steps, 'date'));
							if ($key) {
								echo $steps[$key]['steps'] . ', ';
							} else {
								echo '0, ';
							}
						} ?>
				],
				borderColor: [
					'rgba(8,217,214,1)'
				],
				backgroundColor: 'rgba(8,217,214,0.1)',
				borderWidth: 1
			}, {
				label: 'Last Week',
				data: [
					<?php foreach($lastWeek as $day){
							$key = array_search($day, array_column($steps, 'date'));
							if ($key) {
								echo $steps[$key]['steps'] . ', ';
							} else {
								echo '0, ';
							}
						} ?>
				],
				borderColor: [
					'rgba(255,46,99,1)'
				],
				backgroundColor: 'rgba(255,46,99,0.1)',
				borderWidth: 1
			}]
		},
		options: {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		}
	});

	var ctx = document.getElementById("radarChart").getContext('2d');
	var myRadarChart = new Chart(ctx, {
		type: 'radar',
		data: {
			labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			datasets: [{
				label: "Last Year",
				data: [stepNumber(20000, 450000), stepNumber(20000, 450000), stepNumber(20000, 450000), stepNumber(20000,
					450000), stepNumber(20000, 450000), stepNumber(20000, 450000), stepNumber(20000, 450000), stepNumber(20000,
					450000), stepNumber(20000, 450000), stepNumber(20000, 450000), stepNumber(20000, 450000), stepNumber(20000,
					450000)],
				borderColor: ['rgba(247,73,6,1)'],
				backgroundColor: ['rgba(247,73,6,0.1)'],
				borderWidth: 1
			}, {
				label: "Current Year",
				data: [stepNumber(20000, 450000), stepNumber(20000, 450000), stepNumber(20000, 450000), stepNumber(20000,
					450000), stepNumber(20000, 450000), stepNumber(20000, 450000), stepNumber(20000, 450000), stepNumber(20000,
					450000), stepNumber(20000, 450000)],
				borderColor: ['rgba(85,78,68,1)'],
				backgroundColor: ['rgba(85,78,68,0.1)'],
				borderWidth: 1
			}]
		}
	});

	var ctx = document.getElementById("stackedChart").getContext("2d");
	var stackedLine = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: ["Mon", "Tues", "Wed", "Thurs", "Fri", "Sat", "Sun"],
			datasets: [{
				label: 'You',
				data: [stepNumber(2000, 45000), stepNumber(2000, 45000), stepNumber(2000, 45000), stepNumber(2000, 45000),
					stepNumber(2000, 45000), stepNumber(2000, 45000), stepNumber(2000, 45000)
				],
				borderColor: [
					'rgba(255,116,115,1)'
				],
				backgroundColor: 'rgba(255,116,115,0.1)',
				borderWidth: 1
			}, {
				label: 'Jason',
				data: [stepNumber(2000, 45000), stepNumber(2000, 45000), stepNumber(2000, 45000), stepNumber(2000, 45000),
					stepNumber(2000, 45000), stepNumber(2000, 45000), stepNumber(2000, 45000)
				],
				borderColor: [
					'rgba(255,201,82,1)'
				],
				backgroundColor: 'rgba(255,201,82,0.1)',
				borderWidth: 1
			}, {
				label: 'Luke',
				data: [stepNumber(2000, 45000), stepNumber(2000, 45000), stepNumber(2000, 45000), stepNumber(2000, 45000),
					stepNumber(2000, 45000), stepNumber(2000, 45000), stepNumber(2000, 45000)
				],
				borderColor: [
					'rgba(71,184,224,1)'
				],
				backgroundColor: 'rgba(71,184,224,0.1)',
				borderWidth: 1
			}, {
				label: 'Daniel',
				data: [stepNumber(2000, 45000), stepNumber(2000, 45000), stepNumber(2000, 45000), stepNumber(2000, 45000),
					stepNumber(2000, 45000), stepNumber(2000, 45000), stepNumber(2000, 45000)
				],
				borderColor: [
					'rgba(52,49,76,1)'
				],
				backgroundColor: 'rgba(52,49,76,0.1)',
				borderWidth: 1
			}]
		},
		options: {
			tooltips: {
				mode: 'index',
				intersect: true
			},
			responsive: true
		}
	});

</script>

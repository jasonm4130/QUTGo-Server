<!-- Page Content Holder -->
<div id="content">

	<!-- Graphs and Stuff -->
	<div class="row">
		<div class="col-12">
			<h3>Weekly Overview</h3>
			<canvas id="lineChart"></canvas>
		</div>
		<div class="col-12">
			<h3>You vs Your Friends</h3>
			<canvas id="stackedChart"></canvas>
		</div>
	</div>



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

	var ctx = document.getElementById("stackedChart").getContext("2d");
	var stackedLine = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: ["Mon", "Tues", "Wed", "Thurs", "Fri", "Sat", "Sun"],
			datasets: [{
				label: 'You',
				data: [<?php foreach($thisWeek as $day){
							$key = array_search($day, array_column($steps, 'date'));
							if ($key) {
								echo $steps[$key]['steps'] . ', ';
							} else {
								echo '0, ';
							}
						} ?>],
				borderColor: [
					'rgba(196, 69, 105,.5)'
				],
				backgroundColor: 'rgba(196, 69, 105,.5)',
				borderWidth: 1
			},
			<?php $colors = array("rgba(245, 205, 121,.5)", "rgba(84, 109, 229,.5)", "rgba(247, 143, 179,.5)", "rgba(99, 205, 218,.5)", "rgba(87, 75, 144,.5)"); ?>
			<?php $i = 0; ?>
			<?php foreach($friendsNames as $friend): ?>{
				label: '<?= $friend; ?>',
				data: [<?php foreach($thisWeek as $day){
							$key = array_search($day, array_column($friendStats[$i], 'date'));
							if ($key) {
								echo $steps[$key]['steps'] . ', ';
							} else {
								echo '0, ';
							}
						} ?>],
				borderColor: ['<?= $colors[$i]; ?>'],
				backgroundColor: '<?= $colors[$i]; ?>',
				borderWidth: 1
			},<?php $i++; ?><?php endforeach; ?>]
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

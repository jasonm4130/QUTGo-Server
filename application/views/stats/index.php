<div class="row">
	<?php foreach ($stats_current as $stats): ?>
		<div class="col-1">
			Current
			Steps: <?= $stats['steps'] ?>
			Date: <?= $stats['date'] ?>
		</div>
	<?php endforeach; ?>
	<?php foreach ($stats_past as $stats): ?>
		<div class="col-1">
			Past
			Steps: <?= $stats['steps'] ?>
			Date: <?= $stats['date'] ?>
		</div>
	<?php endforeach; ?>
</div>
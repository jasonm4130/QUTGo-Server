<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>QUT Go</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= link_tag('/assets/css/main.css'); ?>
	<?= script_tag('/assets/js/jQuery-3.3.1.js'); ?>
	<?= script_tag('/assets/js/Chart.min.js'); ?>
	<?= script_tag('/assets/js/bootstrap.min.js'); ?>
	<?= script_tag('/assets/js/main.js'); ?>
</head>

<body>


	<div class="wrapper">
		<!-- Sidebar Holder -->
		<nav id="sidebar">
			<div class="sidebar-header">
				<h3>QUT Go <br>Dashboard</h3>
			</div>

			<ul class="list-unstyled components">
				<p>Welcome User Name</p>
				<li class="active">
					<a href="#">Home</a>
				</li>
				<li>
					<a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Dashboard</a>
					<ul class="collapse list-unstyled" id="pageSubmenu">
						<li>
							<a href="/dashboard/challenges">Your Challenges</a>
						</li>
						<li>
							<a href="/dashboard/statistics">Statistics</a>
						</li>
						<li>
							<a href="/dashboard/trends">Weekly Trends</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="#">Contact Us</a>
				</li>
			</ul>

			<ul class="list-unstyled CTAs">
				<li>
					<a href="#" class="download">Download QUT Go</a>
				</li>
				<li>
					<a href="#" class="article">Report an Issue</a>
				</li>
			</ul>
			<ul class="list-unstyled login">
				<li>
				<?php if ($this->session->userdata('user_id')): ?>
					<a href="/user/user_logout" class="login-btn">Logout</a>
				<?php else: ?>
					<a data-toggle="modal" href="#loginModal" class="login-btn">Login</a>
				<?php endif; ?>
				</li>
			</ul>
		</nav>

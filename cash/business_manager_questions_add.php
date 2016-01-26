<?php
session_start();
?>
<!DOCTYPE HTML>
<html><!-- InstanceBegin template="/Templates/right-sidebar.dwt.php" codeOutsideHTMLIsLocked="false" -->
	<head>
  <meta charset="UTF-8">
	<!-- InstanceBeginEditable name="doctitle" -->
	<title>Business Manager</title>
	<!-- InstanceEndEditable -->
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,700,500,900' rel='stylesheet' type='text/css'>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="themes/templated-linear/js/skel.min.js"></script>
		<script src="themes/templated-linear/js/skel-panels.min.js"></script>
		<script src="themes/templated-linear/js/init.js"></script>
		<!-- InstanceBeginEditable name="head" -->
		<!-- InstanceEndEditable -->
			<link rel="stylesheet" href="themes/templated-linear/css/skel-noscript.css" />
			<link rel="stylesheet" href="themes/templated-linear/css/style.css" />
			<link rel="stylesheet" href="themes/templated-linear/css/style-desktop.css" />
	</head>
	<body>
	<!-- Header -->
		<div id="header">
			<div id="nav-wrapper"> 
				<!-- Nav -->
				<nav id="nav">
					<?php include('menu.php'); ?>
				</nav>
			</div>
			<div class="container"> 
				
				<!-- Logo -->
				<div id="logo">
					<?php include('submenu.php'); ?>
				</div>
			</div>
		</div>
	<!-- Header --> 

	<!-- Main -->
		<div id="main">
			<div class="container">
				<div class="row">

					<!-- Content -->
					<div id="content" class="8u skel-cell-important">
						<section>
<!-- InstanceBeginEditable name="EditRegion3" -->
							<header>
								<h2>Business Manager</h2>
								<span class="byline">Edit Your Business/Ads</span>
							</header>
							<p></p>
<!-- InstanceEndEditable -->
						</section>
					</div>

					<!-- Sidebar -->
					<div id="sidebar" class="4u">
            <!-- InstanceBeginEditable name="EditRegion4" -->
						<?php include('business_manager_menu.php'); ?>
            <!-- InstanceEndEditable -->
					</div>
					
				</div>
			</div>
		</div>
	<!-- /Main -->

	<!-- Tweet -->
		<div id="tweet">
			<div class="container">
				<section>
					<blockquote>&ldquo;In posuere eleifend odio. Quisque semper augue mattis wisi. Maecenas ligula. Pellentesque viverra vulputate enim. Aliquam erat volutpat.&rdquo;</blockquote>
				</section>
			</div>
		</div>
	<!-- /Tweet -->

	<!-- Footer -->
		<div id="footer">
			<div class="container">
				<section>
					<header>
						<h2>Get in touch</h2>
						<span class="byline">Integer sit amet pede vel arcu aliquet pretium</span>
					</header>
					<ul class="contact">
						<li><a href="#" class="fa fa-twitter"><span>Twitter</span></a></li>
						<li class="active"><a href="#" class="fa fa-facebook"><span>Facebook</span></a></li>
						<li><a href="#" class="fa fa-dribbble"><span>Pinterest</span></a></li>
						<li><a href="#" class="fa fa-tumblr"><span>Google+</span></a></li>
					</ul>
				</section>
			</div>
		</div>
	<!-- /Footer -->

	<!-- Copyright -->
		<div id="copyright">
			<div class="container">
				Design: <a href="http://templated.co">TEMPLATED</a> Images: <a href="http://unsplash.com">Unsplash</a> (<a href="http://unsplash.com/cc0">CC0</a>)
			</div>
		</div>


	</body>
<!-- InstanceEnd --></html>
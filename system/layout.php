<html>
	<head>
		<title>AetherCop - <?=$data["title"]?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<?=$data["head"]?>
		<link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css">
		<link rel="stylesheet" href="css/css.css">
		<script language="javascript" src="js/geotools.js"></script>
		<script language="javascript" src="js/geo.js"></script>
	</head>
	<body>
		<div class="topbar" data-dropdown="dropdown" >
			<div class="topbar-inner">
				<div class="container">
					<h3><a href="#">AetherCop</a></h3>
					<ul class="nav">
						<li class="active"><a href="#">Home</a></li>
						<li><a href="#">Link</a></li>
						<li><a href="#">Link</a></li>
						<li><a href="#">Link</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div id="body">
			<?=$data["body"]?>
		</div>
	</body>
</html>

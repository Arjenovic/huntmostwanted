<!DOCTYPE html>
<html>
<head>
		<title>Hunt The Most Wanted | Arjen van Gaal</title>
		<meta http-equiv="Content-Type" charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/reset.css" />
		<link rel="stylesheet" type="text/css" href="css/index.css" />
	<script src='js/addlistener.js'></script><!-- INFO BUBBLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> <!-- LOAD GENERAL JQUERY LIBRARY -->
    <script src='js/googlemaps_infobubble.js'></script><!-- INFO BUBBLE -->
</head>
<body>
	<header>
		<div id='pageTitle'>
			<h1>Hunt The<br><span> Most Wanted </span></h1>
		</div>

		<nav>
			<div id='criminals_menu'>
				<div class='nav_arrow blink_me_slower' id='nav_arrow_left' onclick='assignNextClicks("left")'></div>
				<div class='criminalSelections' id='criminalSelection0'></div>
				<div class='criminalSelections' id='criminalSelection1'></div>
				<div class='criminalSelections' id='criminalSelection2'></div>
				<div class='criminalSelections' id='criminalSelection3'></div>
				<div class='criminalSelections' id='criminalSelection4'></div>
				<div class='nav_arrow blink_me_slower' id='nav_arrow_right' onclick='assignNextClicks("right")'></div>
			</div>
		</nav>
	</header>

	<div id="startScreen" onclick='startScreenClick()'>
		<h1>Hunt The<br><span>Most Wanted</span></h1><br>
		<h2 class='blink_me'>- Start Tracking Criminals -</h2>
	</div>

	<div id='container'>
		<div id='profile'>
			<div id='profilePicture'></div>
			<table id='profileData'>
				<tr>
					<td>Name</td>
					<td id='td_name'></td>
				</tr>
				<tr>
					<td>Aliases</td>
					<td id='td_aliases'></td>
				</tr>
				<tr>
					<td>Offense(s)</td>
					<td id='td_offenses'></td>
				</tr>
				<tr>
					<td>Gender</td>
					<td id='td_gender'></td>
				</tr>
				<tr>
					<td>Date Of Birth</td>
					<td id='td_dob'></td>
				</tr>
				<tr>
					<td>Place Of Birth</td>
					<td id='td_pob'></td>
				</tr>
				<tr>
					<td>Height</td>
					<td id='td_height'></td>
				</tr>
				<tr>
					<td>Weight</td>
					<td id='td_weight'></td>
				</tr>
				<tr>
					<td>Hair Color</td>
					<td id='td_haircolor'></td>
				</tr>
				<tr>
					<td>Eye Color</td>
					<td id='td_eyecolor'></td>
				</tr>
				<tr>
					<td>Reward</td>
					<td id='td_reward'></td>
				</tr>
			</table>
		</div>

		<div class='popUpScreen' id='instructionScreen'>
			<p class='blink_me'>Select a Wanted Criminal in the top menu.</p>
		</div>

<!--
		<div class='popUpScreen' id='trackScreen'>
			<p class='ourGreen'>- START TRACKING -</p>
			<p>(Click circle markers for location info.)<p>
		</div>
-->

		<div id='legenda'>
			<ul>
				<li>First Location</li>
				<li>Last Location</li>
			</ul>
		</div>
		
		<div id="map"></div>
	  
		<script type="text/javascript" src='js/googlemaps.js'></script>
		<!-- Refers to function initMap to initalize -->
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAwl262zzwitdobe2Z9IHsa3h6zlMmw5b0&callback=initMap"></script>

	</div><!-- Einde Container -->
	<footer>
		<p>&copy; 2015 Arjen van Gaal</p>
	</footer>
</body>
</html>
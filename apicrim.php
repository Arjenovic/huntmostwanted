<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// controleer welke GET parameter aanwezig is
// pas aan de hand daarvan de WHERE clause van het SQL statement aan
// Geen parameter criminal_pob of event_location betekent geen search dus alle criminals tonen
require 'includes/connect.php';

// Is het formaat ingevuld?
if(isset($_GET['formaat'])){
	$formaat= $_GET['formaat'];
	$search = "";

	if(isset($_GET['name'])){
		$criminal_name = mysqli_escape_string($mysqli, $_GET['name']);
		$search = "AND criminal_name LIKE '%" . $criminal_name . "%'";
	}
	if(isset($_GET['alias'])){
		$criminal_aliases = mysqli_escape_string($mysqli, $_GET['alias']);
		$search = "AND criminal_aliases LIKE '%" . $criminal_aliases . "%'";
	}
	if(isset($_GET['offense'])){
		$criminal_offenses = mysqli_escape_string($mysqli, $_GET['offense']);
		$search = "AND criminal_offenses LIKE '%" . $criminal_offenses . "%'";
	}
	if(isset($_GET['dob'])){
		$criminal_dob = mysqli_escape_string($mysqli, $_GET['dob']);
		$search = "AND criminal_dob LIKE '%" . $criminal_dob . "%'";
	}
	if(isset($_GET['pob'])){
		$criminal_pob = mysqli_escape_string($mysqli, $_GET['pob']);
		$search = "AND criminal_pob LIKE '%" . $criminal_pob . "%'";
	}

	if(isset($_GET['height'])){
		$criminal_height = mysqli_escape_string($mysqli, $_GET['height']);
		$search = "AND criminal_height LIKE '%" . $criminal_height . "%'";
	}

	if(isset($_GET['weight'])){
		$criminal_weight = mysqli_escape_string($mysqli, $_GET['weight']);
		$search = "AND criminal_weight LIKE '%" . $criminal_weight . "%'";
	}

	if(isset($_GET['hair'])){
		$criminal_hair = mysqli_escape_string($mysqli, $_GET['hair']);
		$search = "AND criminal_hair LIKE '%" . $criminal_hair . "%'";
	}

	if(isset($_GET['eyes'])){
		$criminal_eyes = mysqli_escape_string($mysqli, $_GET['eyes']);
		$search = "AND criminal_eyes LIKE '%" . $criminal_eyes . "%'";
	}

	if(isset($_GET['sex'])){
		$criminal_sex = mysqli_escape_string($mysqli, $_GET['sex']);
		$search = "AND criminal_sex LIKE '%" . $criminal_sex . "%'";
	}

	if(isset($_GET['reward'])){
		$criminal_reward = mysqli_escape_string($mysqli, $_GET['reward']);
		$search = "AND criminal_reward LIKE '%" . $criminal_reward . "%'";
	}

	if(isset($_GET['eventlocation'])){
		$event_location = mysqli_escape_string($mysqli, $_GET['eventlocation']);
		$search = "AND event_location LIKE '%" . $event_location . "%'";
	}

	if(isset($_GET['eventdate'])){
		$event_date = mysqli_escape_string($mysqli, $_GET['eventdate']);
		$search = "AND event_date LIKE '%" . $event_date . "%'";
	}

	// prepare 
	$stmt = $mysqli->prepare("SELECT criminal_name, 
									criminal_aliases,
									criminal_photo,
									criminal_offenses,
									criminal_dob,
									criminal_pob,
									criminal_height,
									criminal_weight,
									criminal_hair,
									criminal_eyes,
									criminal_sex,
									criminal_reward,
									event_location, 
									event_date 
									FROM criminal_profile, criminal_locations 
									WHERE criminal_profile.criminal_id = criminal_locations.criminal_id " . $search . "ORDER BY criminal_reward * 1 DESC, criminal_name, event_date;");

	$stmt->execute();
	$stmt->bind_result($criminal_name,
					   $criminal_aliases,
					   $criminal_photo,
					   $criminal_offenses,
					   $criminal_dob,
					   $criminal_pob,
					   $criminal_height,
					   $criminal_weight,
					   $criminal_hair,
					   $criminal_eyes,
					   $criminal_sex,
					   $criminal_reward,
					   $event_location, 
					   $event_date); //get data from statement
	$stmt->store_result();
	
	if($stmt->num_rows != 0) // als er criminals beschikbaar zijn in de database
	{   if($formaat=='json') //JSON
		{
			while($stmt->fetch()) // zolang er rijen zijn
			{
				// get data from mysqli query and save into variables
				$criminal_profiles[] = array('criminal_name' => $criminal_name,
											'criminal_aliases' => $criminal_aliases, 
											'criminal_photo' => $criminal_photo, 
											'criminal_offenses' => $criminal_offenses,
											'criminal_dob' => $criminal_dob, 
											'criminal_pob' => $criminal_pob,  
											'criminal_height' => $criminal_height,  
											'criminal_weight' => $criminal_weight,
											'criminal_hair' => $criminal_hair, 
											'criminal_eyes' => $criminal_eyes, 
											'criminal_sex' => $criminal_sex, 
											'criminal_reward' => $criminal_reward,  
											'event_location' => $event_location,
											'event_date' => $event_date); // zet alles in een array
			
			}
			header('Content-type: application/json');
			echo (json_encode(array('criminals'=>$criminal_profiles)));
		}
		elseif($formaat=='xml'){ //XML
			$xml=new SimpleXMLElement('<criminals></criminals>');


			while($stmt->fetch()) // zolang er rijen zijn
			{
				// $criminal_name, Scriminal_pob en $event_location zijn gevuld
  				//voeg element criminal_pob toe met naam criminal_pob, criminal_name en event_location:
   				$info = $xml->addChild('criminal');
					$item = $info->addChild('criminal_name',$criminal_name);
					$item = $info->addChild('criminal_aliases',$criminal_aliases);
					$item = $info->addChild('criminal_photo',$criminal_photo);
					$item = $info->addChild('criminal_offenses',$criminal_offenses);
					$item = $info->addChild('criminal_dob',$criminal_dob);
					$item = $info->addChild('criminal_pob',$criminal_pob);  
					$item = $info->addChild('criminal_height',$criminal_height);  
					$item = $info->addChild('criminal_weight',$criminal_weight);
					$item = $info->addChild('criminal_hair',$criminal_hair);
					$item = $info->addChild('criminal_eyes',$criminal_eyes); 
					$item = $info->addChild('criminal_sex',$criminal_sex); 
					$item = $info->addChild('criminal_reward',$criminal_reward);  
					$item = $info->addChild('event_location',$event_location);
					$item = $info->addChild('event_date',$event_date); 
			}
			header('Content-type: text/xml'); //DIT WERKT NIET? cant send headers after they were sent

			// coderen als JSON:
			echo $xml->asXML();
		}
	}
	else // er is geen criminal data
	{	
		$info = "Geen criminal data.";
		if($formaat=='json')
		{
			header('Content-type: application/json');
			echo (json_encode(array('info'=>$info)));
		}
		elseif($formaat='xml'){
			$xml=new SimpleXMLElement("<info>$info</info>");
			header('Content-type: text/xml');
			echo $xml->asXML();
		}
	}
}	
else  // er is geen formaat meegegeven
{
	$error = "Aanroep is: apicrim.php?formaat=xml of apicrim.php?formaat=json. Eventueel uitgebreid met &name, alias, offense, dob, pob, height, weight, hair, eyes, sex, reward, eventlocation, eventdate ="; header('Content-type: application/json');
	echo (json_encode(array('error'=>$error)));
}
?> 
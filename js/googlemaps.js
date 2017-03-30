function showSingleCriminal(criminalName){
  clearMap();
  $("#profile").hide(500);
  $("#profile").show(1000);

  getCriminalData(criminalName);
}

var criminalNames = [];
var criminalPhotos = [];


function getAllCriminalData(){
  $.getJSON("http://arjenvangaal.nl/showcase/huntmostwanted/apicrim.php?formaat=json").done(function(response) {
      $.each(response.criminals, function(object, value){
          //if exists in array
          if(criminalNames.indexOf(value.criminal_name) >= 0){
            console.log("Criminalname already in array.");
          }else{
            criminalNames.push(value.criminal_name); 
            criminalPhotos.push(value.criminal_photo);          
          }
      });
    //set criminal pictures in topmenu
    for(i=0; i<=4; i++){
      $('#criminalSelection' + i).css('background-image', "url('photos/" + criminalPhotos[i] + "')");
      assignClicks(i,i);
    }
  });
}

var overlays = [];
var criminal_data = [];
var locations = [];
var date_locations = [];
var years_locations = [];

function getCriminalData(criminalName){
  $("#instructionScreen").fadeOut(500);

  $.getJSON("http://arjenvangaal.nl/showcase/huntmostwanted/apicrim.php?formaat=json&name=" + criminalName).done(function(response) {

      //CREATE PROFILE ON SIDE
      //Get all profile data into array
      criminal_data = [];
      $.each(response.criminals[0], function(object, value){
          criminal_data.push(value);
      });
      
      //insert all profile data into page
      document.getElementById('td_name').innerHTML = criminal_data[0];
      document.getElementById('td_aliases').innerHTML = criminal_data[1]; 
      document.getElementById('td_offenses').innerHTML = criminal_data[3]; 
      document.getElementById('td_gender').innerHTML = criminal_data[10]; 
      document.getElementById('td_dob').innerHTML = criminal_data[4];     
      document.getElementById('td_pob').innerHTML = criminal_data[5]; 
      document.getElementById('td_height').innerHTML = criminal_data[6].substring(0, 1) + "'" + criminal_data[6].substring(2, criminal_data[6].length); 
      document.getElementById('td_weight').innerHTML = criminal_data[7] + " pounds"; 
      document.getElementById('td_haircolor').innerHTML = criminal_data[8]; 
      document.getElementById('td_eyecolor').innerHTML = criminal_data[9]; 
      document.getElementById('td_reward').innerHTML = "$" + commaSeparateNumber(criminal_data[11]);
      //set profile picture
      $('#profilePicture').css('background-image', "url('photos/" + criminal_data[2] + "')"); 


      //Get all event locations and event dates, already set in chronological order
      locations = [];
      date_locations = [];
      $.each(response.criminals, function(index, criminals){
          locations.push(criminals.event_location);
          date_locations.push(criminals.event_date.substring(0,4));
      });
      //console.log(locations);
      //console.log(date_locations);

      //Calculate years per location
      years_locations = [];
      for(i=0; i<date_locations.length; i++){
        if(i!= date_locations.length -1){
          years_locations.push(date_locations[i+1].substring(0,4) - date_locations[i].substring(0,4));
        }else{
          years_locations.push(new Date().getFullYear() - date_locations[i].substring(0,4));
        }
      }

      //remove duplicates or join
      for (x=0; x<3; x++){ //reinig 3x van duplicaties
        for(i=0; i<locations.length; i++){
          if(locations[i] == locations[i+1]){ //Als 2 locaties achter elkaar hetzelfde zijn
            console.log(locations[i] + " is duplicated!");
            locations.splice(i+1,1); //remove duplicate location from array
            date_locations.splice(i+1, 1); //remove duplicate location date from array
            
            years_locations[i] = years_locations[i] + years_locations[i+1]; //increase first value with 2nd value
            years_locations.splice(i+1, 1); //remove duplicate amountofyearsinlocation value
          }
        }       
      }
  });  

  drawMap();
}


var map;
var poly;
var timertje;

function initMap() {
getAllCriminalData();

  $("#instructionScreen").hide();
  $("#profile").hide();
  $("header").hide();
  $("#legenda").hide();

var myLatlng = new google.maps.LatLng(40, -40);
var mapOptions = {
  scrollwheel: false, 
  zoom: 3,
  center: myLatlng,
  mapTypeId: google.maps.MapTypeId.ROADMAP,
  styles: [{"stylers":[{"hue":"#ff1a00"},{"invert_lightness":true},{"saturation":-100},{"lightness":33},{"gamma":0.5}]}, {"featureType": "all","elementType": "labels","stylers": [{"visibility": "off"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#282828"}]}],
  disableDefaultUI: true
};
map = new google.maps.Map(document.getElementById("map"),
mapOptions);
}

function drawMap(){
    //POLYLINE
  poly = new google.maps.Polyline({
    geodesic: true,
    strokeColor: '#1db954',
    strokeOpacity: 0.65,
    strokeWeight: 3
  });
  overlays.push(poly);
  poly.setMap(map);

  //GEOCODER
  var geocoder = new google.maps.Geocoder();

 //DRAW CRIMINAL PATH
  var i = 0;

  timertje = setInterval(function(){ 
  if(i<locations.length){
    var color_location ='black';
    if(i == 0){color_location = 'whitesmoke';}
    if(i == locations.length - 1){color_location = '#1db954';}
    geocodeAddress(locations[i], geocoder, map, years_locations[i], date_locations[i], color_location); i++;
  }else{
    clearInterval(timertje);
  }   
  }, 1000);
}


function geocodeAddress(address, geocoder, resultsMap, circleScale, TimeOfPlace, LocationColor) {
  var criminalPath = poly.getPath();

  geocoder.geocode({'address': address}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      resultsMap.setCenter(results[0].geometry.location); //set center of map
      criminalPath.push(results[0].geometry.location); //add new location to poly path line
      

      //INFOWINDOW
       var contentString = address + " - " + TimeOfPlace + "<br>" + "Stay of " + circleScale + " years";

      var infobubble = new InfoBubble({
        content: contentString,
        backgroundColor: 'rgba(255, 255, 255, 0.25)',
        borderColor: 'rgb(40,40,40)',
        disableAutoPan: true
      });
      overlays.push(infobubble);

      //MARKER
      var marker = new google.maps.Marker({
      	animation: google.maps.Animation.DROP,
        map: resultsMap,
        position: results[0].geometry.location, //center view on latest added location
        icon:{
          path: google.maps.SymbolPath.CIRCLE,
          fillOpacity: 0.90,
          fillColor: LocationColor,
          strokeOpacity: .25,
          strokeColor: 'whitesmoke',
          strokeWeight: circleScale * 4.5 , 
          scale: 5 //pixels          
        }
      });
      overlays.push(marker);

      //LISTENER PER MARKER
      marker.addListener('click', function() {
        infobubble.open(map, marker);
      });

    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}


function clearMap(){
  clearInterval(timertje);

  if(overlays != '' || overlays != null){
     while(overlays[0]){
     overlays.pop().setMap(null);
    } 
  }else{
    console.log("Overlays are empty.");
  }
}


function commaSeparateNumber(val){
  while (/(\d+)(\d{3})/.test(val.toString())){
    val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
  }
  return val;
}


function assignClicks(picturenummer, crimineeltje){
    $("#criminalSelection" + picturenummer).off('click');

    $("#criminalSelection" + picturenummer).on('click', function(){
    showSingleCriminal(criminalNames[crimineeltje]);
  });
}


var firstCriminalNumber = 5;

function assignNextClicks(direction){
  if(direction == 'left'){
    firstCriminalNumber = firstCriminalNumber - 10;
  }
    for(a=0; a<5; a++){ //5x uitvoeren - plaatje toevoegen + link toevoegen
      //als nieuwe nummer boven het hoogste aantal is
      if(firstCriminalNumber>=criminalNames.length){
        firstCriminalNumber = firstCriminalNumber - criminalNames.length;
      }    
      //als nieuwe nummer lager dan 0 is, pak totale count - hoeveel je in de min bent
      if(firstCriminalNumber < 0){
        firstCriminalNumber = criminalNames.length  + firstCriminalNumber; 
      }

      $('#criminalSelection' + a).css('background-image', "url('photos/" + criminalPhotos[firstCriminalNumber] + "')"); //plaatje toevoegen
      assignClicks(a,firstCriminalNumber); //boxnummer + welk crimineel nummer

      //console.log(firstCriminalNumber);
      firstCriminalNumber++; //criminal number + 1 / dit wordt 5 x uitgevoerd
    }
}

function startScreenClick(){
  $("#startScreen").fadeOut(500);
  $("header").fadeIn(500);
  $("#legenda").fadeIn(500);
  $("#instructionScreen").fadeIn(500);
}





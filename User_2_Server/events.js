var map;
const LAT = 49.900412;
const LNG = -97.141117;
var startMarkerListener = null;
var form_submit_listener = null;
var markerArray = new Array();
const LIST_TABLE_CONTENT_LENGTH = 20;
const EVENT_ADDRESS = "http://www.gristlebone.com/School/User_2_Server/";

var submit_handler;


function event_marker(latLng,content,screen_name)
{
	this.id = "marker" + (markerArray.length + 1);
	this.latLng = latLng;
	this.content = content;
	this.user = screen_name;
	this.timestamp = "12:00";
}
function startMap()
{
	var mapOptions = {
		zoom: 18,
		//maxZoom: 18,
		minZoom: 10,
		center: new google.maps.LatLng(LAT,LNG)
	};
	map = new google.maps.Map(document.getElementById("event_map"),mapOptions);
}

function setDisplay()
{
	// Hide form
	document.getElementById("event_form").style.display = "none";
}
function createMarkerOnMap(latLng)
{
	// create google map marker
	var map_marker = new google.maps.Marker({
		position: latLng,
		map: map
	});
	return map_marker;
}

function addMarkerToList(marker)
{
	var list_div = document.getElementById("event_list");
	var temp_div = document.createElement("div");
	temp_div.setAttribute("class","list_marker");
	var temp_content = "";

	if(marker.event_content.length>LIST_TABLE_CONTENT_LENGTH)
	{
		temp_content = marker.event_content.substring(0,LIST_TABLE_CONTENT_LENGTH);
	}
	else
	{
		temp_content = marker.event_content;
	}
	var temp_p_content = document.createElement("p");
	temp_p_content.textContent = temp_content;
	temp_div.appendChild(temp_p_content);

	var temp_p_time = document.createElement("p");
	temp_p_time.textContent = marker.event_created_at;
	temp_div.appendChild(temp_p_time);

	list_div.appendChild(temp_div);

	return temp_div;

}

function postEventFeeds(post_string)
{
	eventsHttp = new XMLHttpRequest();
	
	eventsHttp.onreadystatechange = function(){
		
		if(eventsHttp.readyState == 4 && eventsHttp.status == 200)
		{
			markerArray = eval(eventsHttp.responseText);
			document.getElementById("event_list").innerHTML = "";

			// show saved markers on the map
			for(var i=0;i<markerArray.length;i++)
			{
				var temp_div = addMarkerToList(markerArray[i]);
				var latLng = new google.maps.LatLng(parseFloat(markerArray[i].event_lat),parseFloat(markerArray[i].event_long));
				var map_marker = createMarkerOnMap(latLng);

			}
		}
	}
	eventsHttp.open("GET", post_string, true);
	eventsHttp.send();
}

function submitForm(latLng)
{

	if((document.getElementById("event_form_content").value.length < 1) || (document.getElementById("event_form_content").value == null))
	{
		// Validation error

	}
	else
	{
		var map_marker = createMarkerOnMap(latLng);
		// create new event marker and push to array
		var temp_marker = new event_marker(latLng,document.getElementById("event_form_content").value,"Me")

		// post to server
		var post_string = EVENT_ADDRESS + "posts.php?lat="+latLng.d + "&long=" + latLng.e + "&content=" + temp_marker.content + "&screen_name=Me";
		postEventFeeds(post_string);

		markerArray.push(temp_marker);
		// dump array to storage
		//localStorage.setItem("markerArray", JSON.stringify(markerArray));

		document.getElementById("event_form").style.display = "none";
		document.getElementById("event_list").style.display = "block";
		addMarkerListener();
	}
	document.getElementById("event_form_submit").removeEventListener("click",submit_handler,false);
}

function setMarker(event)
{
	removeMarkerListener();
	// show form
	document.getElementById("event_list").style.display = "none";
	document.getElementById("event_form").style.display = "block";
	document.getElementById("event_form_content").value = "";
	document.getElementById("event_form_content").focus();
	submit_handler = function(){submitForm(event.latLng);}
	document.getElementById("event_form_submit").addEventListener("click",submit_handler,false);
	

}
function addMarkerListener()
{
	if(startMarkerListener == null)
	{
		startMarkerListener = google.maps.event.addListener(map, 'click', function(e){setMarker(e);});
	}
	
}
function removeMarkerListener()
{
	google.maps.event.removeListener(startMarkerListener);
	startMarkerListener = null;
}
function load()
{

	startMap();
	setDisplay();
	postEventFeeds("retrieve_events.php");

	addMarkerListener();
	google.maps.event.addListener(map, 'dblclick',addMarkerListener);
}

document.addEventListener("DOMContentLoaded", load, false);
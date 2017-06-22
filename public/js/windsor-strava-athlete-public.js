jQuery(document).ready(function($) {
	WindsorStravaAthlete.bindEvents();
});

var WindsorStravaAthlete = {



	bindEvents:function(){
		jQuery('.wsa-show-map').on('click', WindsorStravaAthlete.buildMapData);
	},

	buildMapData:function(e){
		e.preventDefault();

		var resp = [];
		var atts = {};
		var athlete = jQuery('.wsa-feed').data('ride-meta');

		// initMap expects first arg to be an array of activities - fill er up.
		resp[0] = jQuery(this).data('ride-meta');

		// build atts
		atts.lat = resp[0].start_latitude;
		atts.lng = resp[0].start_longitude;
		atts.zoom = 11;
		atts.mapid = 'feed-' + resp[0].id;

		jQuery('#map-' + atts.mapid).height('400px');

		// init map
		WindsorStravaAthlete.initMap(resp, athlete, atts, true);
		
	},

	initMap:function(resp, athlete, atts, single=false) {

		var monthNames = [
		  "January", "February", "March",
		  "April", "May", "June", "July",
		  "August", "September", "October",
		  "November", "December"
		];

		var date = new Date(resp[0].start_date_local);
		var day = date.getDate();
		var month = date.getMonth();
		var year = date.getFullYear();
		var date = month + '/' + day  + '/' + year;
		var latLng = false;

		jQuery('.wsc-date').text(date);

		// check for lat/lng
		if (atts.lng != false && atts.lat != false) {
			var latLng = new google.maps.LatLng(atts.lat, atts.lng);
		}

		else if(single){
			var latLng = new google.maps.LatLng(resp[0].start_latitude, resp[0].start_longitude);
		}

		else{
		    var latLng = new google.maps.LatLng(39.7469, -105.2108);
		}

	    var myOptions = {
	        zoom: parseInt(atts.zoom),
	        center: latLng,
	        mapTypeId: google.maps.MapTypeId.TERRAIN
	    }
	    
	    var mapid = 'map-' + atts.mapid;
	    var map = new google.maps.Map(document.getElementById(mapid), myOptions);

	    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(WindsorStravaAthlete.fullScreenControl(map));

	    for (var i = resp.length - 1; i >= 0; i--) {

	    	// console.log(resp[i]);
	    	var infowindow = new google.maps.InfoWindow({ pixelOffset: new google.maps.Size(0,-48)});
	    	var marker, i;

	    	// Decode 
	    	var decodedPath = google.maps.geometry.encoding.decodePath(resp[i].map.summary_polyline); 
	    	var decodedLevels = WindsorStravaAthlete.decodeLevels("BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB");

	    	console.log(decodedPath);
	    	console.log(decodedLevels);

	    	// Draw polyline
	    	var setRegion = new google.maps.Polyline({
	    	    path: decodedPath,
	    	    levels: decodedLevels,
	    	    strokeColor: "#FF0000",
	    	    strokeOpacity: .5,
	    	    strokeWeight: 2,
	    	    map: map
	    	});

	    	marker = new RichMarker({
	    	  position: new google.maps.LatLng(decodedPath[0].lat(), decodedPath[0].lng()),
	    	  map: map,
	    	  shadow: 'none',
	    	  content: '<div class="wsc-label"><img src="' + athlete.profile_medium + '"/></div>',
	    	  // labelClass: 'wsc-label'
	    	});


	    	google.maps.event.addListener(marker, 'click', (function(marker, i) {
	    	  return function() {
	    	  	// var mc = '<div class="athlete"><strong>' + resp[i].athlete.firstname + ' ' + resp[i].athlete.lastname + '</strong></div>';
	    	  	var	mc = '<div class="athlete">' + resp[i].name + '</div>';
	    	  		// mc += '<div class="athlete-page"><a target="_blank" href="https://www.strava.com/athletes/' + resp[i].athlete.id + '">Athlete Page</a></div>';
	    	  		// mc += '<div class="on-strava"><a target="_blank" href="https://www.strava.com/activities/' + resp[i].id + '">View on Strava</a></div>';
	    	    infowindow.setContent(mc);
	    	    infowindow.open(map, marker);
	    	  }
	    	})(marker, i));
	    }     
	},

	decodeLevels:function(encodedLevelsString) {
	    var decodedLevels = [];

	    for (var i = 0; i < encodedLevelsString.length; ++i) {
	        var level = encodedLevelsString.charCodeAt(i) - 63;
	        decodedLevels.push(level);
	    }
	    return decodedLevels;
	},

	googleMapButton:function (text, className) {
	    "use strict";
	    var controlDiv = document.createElement("div");
	    controlDiv.className = className;
	    controlDiv.index = 1;
	    controlDiv.style.padding = "10px";
	    // set CSS for the control border.
	    var controlUi = document.createElement("div");
	    controlUi.style.backgroundColor = "rgb(255, 255, 255)";
	    controlUi.style.color = "#565656";
	    controlUi.style.cursor = "pointer";
	    controlUi.style.textAlign = "center";
	    controlUi.style.boxShadow = "rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px";
	    controlDiv.appendChild(controlUi);
	    // set CSS for the control interior.
	    var controlText = document.createElement("div");
	    controlText.style.fontFamily = "Roboto,Arial,sans-serif";
	    controlText.style.fontSize = "11px";
	    controlText.style.paddingTop = "8px";
	    controlText.style.paddingBottom = "8px";
	    controlText.style.paddingLeft = "8px";
	    controlText.style.paddingRight = "8px";
	    controlText.innerHTML = text;
	    controlUi.appendChild(controlText);
	    jQuery(controlUi).on("mouseenter", function () {
	        controlUi.style.backgroundColor = "rgb(235, 235, 235)";
	        controlUi.style.color = "#000";
	    });
	    jQuery(controlUi).on("mouseleave", function () {
	        controlUi.style.backgroundColor = "rgb(255, 255, 255)";
	        controlUi.style.color = "#565656";
	    });
	    return controlDiv;
	},
	
	fullScreenControl:function(map, enterFull, exitFull) {
	    "use strict";
	    if (enterFull === void 0) { enterFull = null; }
	    if (exitFull === void 0) { exitFull = null; }
	    if (enterFull == null) {
	        enterFull = "Full screen";
	    }
	    if (exitFull == null) {
	        exitFull = "Exit full screen";
	    }
	    var controlDiv = WindsorStravaAthlete.googleMapButton(enterFull, "fullScreen");
	    var fullScreen = false;
	    var interval;
	    var mapDiv = map.getDiv();
	    var divStyle = mapDiv.style;
	    if (mapDiv.runtimeStyle) {
	        divStyle = mapDiv.runtimeStyle;
	    }
	    var originalPos = divStyle.position;
	    var originalWidth = divStyle.width;
	    var originalHeight = divStyle.height;
	    // ie8 hack
	    if (originalWidth === "") {
	        originalWidth = mapDiv.style.width;
	    }
	    if (originalHeight === "") {
	        originalHeight = mapDiv.style.height;
	    }
	    var originalTop = divStyle.top;
	    var originalLeft = divStyle.left;
	    var originalZIndex = divStyle.zIndex;
	    var bodyStyle = document.body.style;
	    if (document.body.runtimeStyle) {
	        bodyStyle = document.body.runtimeStyle;
	    }
	    var originalOverflow = bodyStyle.overflow;
	    controlDiv.goFullScreen = function () {
	        var center = map.getCenter();
	        mapDiv.style.position = "fixed";
	        mapDiv.style.width = "100%";
	        mapDiv.style.height = "100%";
	        mapDiv.style.top = "0";
	        mapDiv.style.left = "0";
	        mapDiv.style.zIndex = "100";
	        document.body.style.overflow = "hidden";
	        jQuery(controlDiv).find("div div").html(exitFull);
	        fullScreen = true;
	        google.maps.event.trigger(map, "resize");
	        map.setCenter(center);
	        // this works around street view causing the map to disappear, which is caused by Google Maps setting the 
	        // css position back to relative. There is no event triggered when Street View is shown hence the use of setInterval
	        interval = setInterval(function () {
	            if (mapDiv.style.position !== "fixed") {
	                mapDiv.style.position = "fixed";
	                google.maps.event.trigger(map, "resize");
	            }
	        }, 100);
	    };
	    controlDiv.exitFullScreen = function () {
	        var center = map.getCenter();
	        if (originalPos === "") {
	            mapDiv.style.position = "relative";
	        }
	        else {
	            mapDiv.style.position = originalPos;
	        }
	        mapDiv.style.width = originalWidth;
	        mapDiv.style.height = originalHeight;
	        mapDiv.style.top = originalTop;
	        mapDiv.style.left = originalLeft;
	        mapDiv.style.zIndex = originalZIndex;
	        document.body.style.overflow = originalOverflow;
	        jQuery(controlDiv).find("div div").html(enterFull);
	        fullScreen = false;
	        google.maps.event.trigger(map, "resize");
	        map.setCenter(center);
	        clearInterval(interval);
	    };
	    // setup the click event listener
	    google.maps.event.addDomListener(controlDiv, "click", function () {
	        if (!fullScreen) {
	            controlDiv.goFullScreen();
	        }
	        else {
	            controlDiv.exitFullScreen();
	        }
	    });
	    return controlDiv;
	}

}


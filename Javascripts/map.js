function initMap(){
	var myLatlng = new google.maps.LatLng(-25.363882,131.044922);
	  var mapOptions = {
	    zoom: 4,
	    center: myLatlng
	  }
	  var map = new google.maps.Map(document.getElementById('mymap'), mapOptions);
	  var marker = new google.maps.Marker({
	      position: myLatlng,
	      map: map,
	      title: 'Hello World!'
	  });
}

function initMap2(){
	var mapDiv = document.getElementById("mymap");
	var mapOptions = {
			center : new google.maps.LatLng(1.6264447294025188, 103.9031982421875),
			zoom : 4,
			mapTypeId : google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(mapDiv, mapOptions);

	var destinations = new google.maps.MVCArray();
	destinations.push(new google.maps.LatLng(1.6429175148900304, 103.6834716796875));
	destinations.push(new google.maps.LatLng(1.5783983419218972, 103.7548828125));
	destinations.push(new google.maps.LatLng(1.6264447294025188, 103.9031982421875));
	destinations.push(new google.maps.LatLng(1.688216970476038, 103.85787963867188));

	var polygonOptions = {path: destinations};
	var polygon = new google.maps.Polygon(polygonOptions);
	polygon.setMap(map);
}
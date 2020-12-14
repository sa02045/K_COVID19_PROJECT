<?php
	 $latitude = $_GET["latitude"];
	 $longitude = $_GET["longitude"];
	 //echo $latitude;
?>

<form>
    <input type="hidden" name="latitude" id="latitude" value="<?php echo $latitude;?>">
</form>

<form>
    <input type="hidden" name="longitude" id="longitude" value="<?php echo $longitude;?>">
</form>

<body>
	<div id="map" style="width:500px;height:400px;"></div>
	<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=53cd63221304fc0c2939cbae72e13f1c"></script>
	<script>
		var latitude = document.getElementById('latitude').value;
		var longitude = document.getElementById('longitude').value;
		var container = document.getElementById('map');
		var options = {
			center: new kakao.maps.LatLng(latitude, longitude),
			level: 3
		};

		var map = new kakao.maps.Map(container, options);
		var markerPosition  = new kakao.maps.LatLng(latitude, longitude); 

		var marker = new kakao.maps.Marker({
			position: markerPosition
		});

		marker.setMap(map);
	</script>
</body>
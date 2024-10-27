<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	function initialisation() {
		var Tours = new google.maps.LatLng(  <?php echo $adresseGeoCodeur_lat; ?>,  <?php echo $adresseGeoCodeur_lng; ?> );
		var optionsCarte = {
			zoom: 8,
			center: Tours
		};
		var maCarte = new google.maps.Map( document.getElementById("ajeadresse-carte"), optionsCarte );
		var optionsMarqueur = {
			map: maCarte,
			position: maCarte.getCenter(),
			title: maCarte.getCenter().toUrlValue(),
			<?php echo $icone; ?>
			draggable: true
		};
		var marqueur = new google.maps.Marker( optionsMarqueur );
		google.maps.event.addListener( marqueur, "dragend", function( evenement ) {
			this.setTitle( evenement.latLng.toUrlValue() );
			document.getElementById("ajeadresse-methode").value = "points";
			f();
			document.getElementById("ajeadresse-preremplir").value = "nouvelle";
			ff();
			document.getElementById("ajeadresse-adresse").value = "";
			document.getElementById("ajeadresse-lat").value = evenement.latLng.lat();
			document.getElementById("ajeadresse-lng").value = evenement.latLng.lng();
		});


	 }
	 google.maps.event.addDomListener( window, 'load', initialisation );
</script>

<div id="ajeadresse-carte"></div>
<script type='text/javascript'>
function initMap() {
  var map = new google.maps.Map(document.getElementById('ajeadresse-carte'), {
    zoom: <?php echo $zoom; ?>,
    scrollwheel: false,
    center: {lat: <?php echo $adresseGeoCodeur_lat; ?>, lng: <?php echo $adresseGeoCodeur_lng; ?>}
  });
  var marker = new google.maps.Marker({
    map: map,
    <?php echo $icone; ?>
    // Define the place with a location, and a query string.
    place: {
      location: {lat: <?php echo $adresseGeoCodeur_lat; ?>, lng: <?php echo $adresseGeoCodeur_lng; ?>},
      query: '<?php echo $titre; ?>'
    },
    // Attributions help users find your site again.
    attribution: {
      source: 'Google Maps JavaScript API',
      webUrl: 'https://developers.google.com/maps/'
    }
  });
  // Construct a new InfoWindow.
  var infoWindow = new google.maps.InfoWindow({
    content: '<?php echo '<strong>'.$titre.'</strong><br>'.$description; ?>',
	  maxWidth: 600
  });
  // Opens the InfoWindow when marker is clicked.
  marker.addListener('click', function() {
    infoWindow.open(map, marker);
  });
}
</script>

<div id="ajeadresse-carte"></div>

<?php
if ( get_option( 'cleAPIGoogleMaps' ) !== false ) {
?><script async defer
        src="//maps.googleapis.com/maps/api/js?libraries=places&callback=initMap&key=<?php echo get_option('cleAPIGoogleMaps');?>"></script><?php
}else{
  ?><script async defer
        src="//maps.googleapis.com/maps/api/js?libraries=places&callback=initMap"></script><?php
}
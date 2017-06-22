<div id="wsc">
	<div class="wsa-map" id="map-<?php echo $atts['mapid']; ?>"></div>
</div>
<?php $atts = json_encode($atts);  ?>
<script>
	jQuery(document).ready(function($) {
		WindsorStravaAthlete.initMap( <?php echo $activities; ?>, <?php echo json_encode($athlete); ?>, <?php echo $atts; ?> );
	});
</script>
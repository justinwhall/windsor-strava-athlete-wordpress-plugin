<?php 
	$activity = json_decode($activity);
	$athlete = json_decode($athlete);
	$activity_single = array();
	$activity_single[] = $activity;
	$date = new DateTime($activity->start_date_local);
 ?>
 <div class="wsa-wrap">
 	<div class="wsa-brand">
 		<img src=" <?php echo $athlete->profile_medium; ?>" >
 		<div class="wsa-name-location">
 			<div class="wsa-name">
 				<?php echo $athlete->firstname . ' ' . $athlete->lastname; ?>
 			</div>
 			<div class="wsa-location">
 				<?php echo $athlete->city . ' ' . $athlete->state . ' ' . $athlete->country; ?>
 			</div>
 		</div>
 	</div>

 	<?php if ($atts['followers']): ?>
 		<div class="wsa-friends">
 			<div class="wsa-followers">
 				<span><?php echo $athlete->follower_count; ?></span> <span><?php _e('Followers', 'windsor-strava-athlete'); ?></span>
 			</div>
 			<div class="wsa-following">
 				<span><?php echo $athlete->friend_count; ?></span> <span><?php _e('Following', 'windsor-strava-athlete'); ?></span>
 			</div>
 		</div>
 	<?php endif ?>
 	<h3 class="ride-name"><?php echo $activity->name; ?></h3>
 	<div class="wsa-timestamp"><?php echo $date->format('d/n/Y'); ?></div>
 	<div id="wsc">
 		<div class="wsa-map" id="map-<?php echo $atts['mapid']; ?>"></div>
 	</div>
 	<script>
 		jQuery(document).ready(function($) {
 			WindsorStravaAthlete.initMap( <?php echo json_encode($activity_single); ?>, <?php echo json_encode($athlete); ?>, <?php echo json_encode($atts); ?>, true );
 		});
 	</script>
 	<div class="powered-by-wsa" target="_blank">Powered by <a href="https://windsorup.com/windsor-wordpress-strava-plugin-athlete/">Windsor Strava Athlete</a></div>
 </div>
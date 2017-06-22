<?php 
	$dist_unit = ($athlete->measurement_preference == 'feet') ? 'Miles' : 'Kilometers';
	$el_unit = ($athlete->measurement_preference == 'feet') ? 'Feet' : 'Meters';
?>

	<div class="wsa-brand">
		<img src=" <?php echo $athlete->profile_medium; ?>" >
		<div class="wsa-name-location">
			<div class="wsa-name">
				<?php echo $athlete->firstname . ' ' . $athlete->lastname; ?>
			</div>
			<div class="wsa-location">
				<?php echo $athlete->city . ' ' . $athlete->state . ' ' . $athlete->country ; ?>
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
	<?php endif; ?>

	<?php if ($atts['clubs']): ?>
		<div class="wsa-clubs">
			<div class="wsa-sub-head">Clubs</div>
			<?php foreach ($athlete->clubs as $club):?>
					<span><a href="https://www.strava.com/clubs/<?php echo $club->id; ?>"><img src="<?php echo $club->profile_medium;?>"></a></span>
			<?php endforeach; ?>		
		</div>
	<?php endif; ?>

	<?php if ($atts['stats']):  ?>

			<div class="wsa-stat-wrap">
			
<!-- 					<div class="wsa-row">
						<span class="wsa-key"><?php _e('Biggest Ride') ?></span>
						<span class="wsa-val"><?php echo $this->get_distance( $stats->biggest_ride_distance ); ?></span>
						<span class="measure"> <?php echo $dist_unit; ?></span>
					</div>

					<div class="wsa-row">
						<span class="wsa-key"><?php _e('Biggest Climb') ?></span>
						<span class="wsa-val"><?php echo $this->get_elevation( $stats->biggest_climb_elevation_gain ); ?></span>
						<span class="measure"> <?php echo $el_unit; ?></span>
					</div> -->

					<?php if ( $atts['totals'] ): ?>
						<?php foreach ( $atts['totals'] as $key => $total_key ):  ?>

							<div class="wsa-sub-head"><strong><?php echo str_replace('_', ' ', $total_key); ?></strong></div>

							<div class="wsa-row">
								<span class="wsa-key"><?php _e('Count'); ?></span>
								<span class="wsa-val"><?php echo $stats->$total_key->count; ?></span>
							</div>

							<div class="wsa-row">
								<span class="wsa-key"><?php _e('Distance'); ?></span>
								<span class="wsa-val"><?php echo $this->get_distance($stats->$total_key->distance); ?></span>
								<span class="measure"> <?php echo $dist_unit; ?></span>
							</div>

							<div class="wsa-row">
								<span class="wsa-key"><?php _e('Elevation Gain'); ?></span>
								<span class="wsa-val"><?php echo $this->get_elevation($stats->$total_key->elevation_gain); ?></span>
								<span class="measure"> <?php echo $el_unit; ?></span>
							</div>

						<?php endforeach; ?>
					<?php endif; ?>

			</div>

	<?php endif; ?>

	<?php if ($atts['bikes']): ?>
		<div class="wsa-bikes">
			<div class="wsa-sub-head">Bikes</div>
			<?php foreach ($athlete->bikes as $bike): ?>
					<div class="wsa-row">
						<span class="wsa-key"><?php echo $bike->name; ?> </span><span class="wsa-val"><?php echo $this->get_distance($bike->distance); ?></span><span class="measure"> <?php echo $dist_unit; ?></span>
					</div>
			<?php endforeach; ?>		
		</div>
	<?php endif; ?>



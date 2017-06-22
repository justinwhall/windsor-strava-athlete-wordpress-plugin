<?php
    $wsa_options = get_option('wsa_options');
    $dist_unit = ($athlete->measurement_preference == 'feet') ? 'miles' : 'kilometers';
    $el_unit = ($athlete->measurement_preference == 'feet') ? 'feet' : 'meters';
    $date_px = ( isset( $wsa_options['feed_date_px'] ) ) ? $wsa_options['feed_date_px'] : 14;
    $title_px = ( isset( $wsa_options['feed_title_px'] ) ) ? $wsa_options['feed_title_px'] : 23;
    $meta_px = ( isset( $wsa_options['feed_meta_px'] ) ) ? $wsa_options['feed_meta_px'] : 13;
?>

<style>
    .wsa-date{ font-size: <?php echo $date_px; ?>px }
    .activity-title a{ font-size: <?php echo $title_px; ?>px }
    .wsa-meta{ font-size: <?php echo $meta_px; ?>px }
</style>

<div class="wsa-feed">
    <div class="wsa-brand">
        <img src=" <?php echo $athlete->profile_medium; ?>" >
        <div class="wsa-name-location">
            <div class="wsa-name">
                <a target="_blank" href="https://www.strava.com/athletes/<?php echo $athlete->id; ?>">
                    <?php echo $athlete->firstname . ' ' . $athlete->lastname; ?>
                </a>
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


    <?php 
        $count = 1;
        if ( $activities ):  ?>
        <?php foreach ( $activities as $key => $activity ): 

            if ( $count <= $atts['activities'] ):

                $distance = ( $el_unit == 'feet' ) ? meters_to_miles( $activity->distance ) : $activity->distance / 1000;
                $el_gain = ( $el_unit == 'feet' ) ? meters_to_feet( $activity->total_elevation_gain ) : $activity->total_elevation_gain;

        ?>
                <div class="wsa-activity" data-athlete="<?php json_encode( $athlete ); ?>">
                    <div class="wsa-date">
                        <span class="activity-type"><?php echo $activity->type; ?></span> <?php echo date_i18n( 'M j, Y', strtotime( $activity->start_date_local ) ); ?>
                    </div>
                    <div class="activity-title">
                        <a target="_blank" href="https://www.strava.com/activities/<?php echo $activity->id; ?>">
                            <?php echo $activity->name; ?>
                        </a>
                    </div>
                    <div class="wsa-meta">
                        <span class="activity-dist"><?php echo round( $distance, 2 ) . ' ' . $dist_unit; ?></span>
                        <span> • </span>
                        <span class="activity-gain"><?php echo round( $el_gain ) . ' ' . $el_unit; ?></span>
                        <span> • </span>
                        <a href="#" class="wsa-show-map"  data-ride-meta='<?php echo json_encode( $activity ); ?>'>Map</a>
                    </div>
                    <div class="map-target" id="map-feed-<?php echo $activity->id; ?>" ></div>
                </div>
            <?php endif; $count++; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

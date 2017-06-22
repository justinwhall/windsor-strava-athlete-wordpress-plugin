<?php

if ( ! function_exists( 'meters_to_feet' ) ) {
    function meters_to_feet( $val ) {
        return $val * 3.28084;
    }
} 

if ( ! function_exists( 'meters_to_miles' ) ) {
    function meters_to_miles( $val ) {
        return $val * 0.000621371;
    }
} 
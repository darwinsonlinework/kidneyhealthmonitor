<?php

    if( isset( $_COOKIE['user_id'] ) ) {

        unset( $_COOKIE['user_id'] );
        unset( $_COOKIE['user_type'] );

        setcookie('user_id', '', time() - 3600, '/');
        setcookie('user_type', '', time() - 3600, '/');

        if( !isset( $_COOKIE['user_id'] ) && !isset( $_COOKIE['user_type'] ) ) {

            header( "Location: http://kidneyhealthmonitor.free.nf" );
        } else {

            if( isset( $_SERVER['HTTP_REFERER'] ) ) {

                header( "Location: " . $_SERVER['HTTP_REFERER'] . "?logout=0" );
            }
        }
    }
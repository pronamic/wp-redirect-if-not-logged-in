<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

delete_option( 'redirect_if_not_logged_in_url' );

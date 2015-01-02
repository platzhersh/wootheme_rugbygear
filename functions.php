<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<?php

/*-----------------------------------------------------------------------------------*/
/* Start WooThemes Functions - Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/

// Define the theme-specific key to be sent to PressTrends.
define( 'WOO_PRESSTRENDS_THEMEKEY', 'zdmv5lp26tfbp7jcwiw51ix9sj389e712' );

// WooFramework init
require_once ( get_template_directory() . '/functions/admin-init.php' );

/*-----------------------------------------------------------------------------------*/
/* Load the theme-specific files, with support for overriding via a child theme.
/*-----------------------------------------------------------------------------------*/

$includes = array(
				'includes/theme-options.php', 			// Options panel settings and custom settings
				'includes/theme-functions.php', 		// Custom theme functions
				'includes/theme-actions.php', 			// Theme actions & user defined hooks
				'includes/theme-comments.php', 			// Custom comments/pingback loop
				'includes/theme-js.php', 				// Load JavaScript via wp_enqueue_script
				'includes/sidebar-init.php', 			// Initialize widgetized areas
				'includes/theme-widgets.php',			// Theme widgets
				'includes/theme-install.php',			// Theme installation
				'includes/theme-woocommerce.php'		// WooCommerce options
				);

// Allow child themes/plugins to add widgets to be loaded.
$includes = apply_filters( 'woo_includes', $includes );

foreach ( $includes as $i ) {
	locate_template( $i, true );
}

/*-----------------------------------------------------------------------------------*/
/* You can add custom functions below */
/*-----------------------------------------------------------------------------------*/


/**
* woocommerce_package_rates is a 2.1+ hook
*/
add_filter( 'woocommerce_package_rates', 'hide_shipping_when_free_is_available', 10, 2 );
/**
* Hide shipping rates when free shipping is available
*
* @param array $rates Array of rates found for the package
* @param array $package The package array/object being shipped
* @return array of modified rates
*/
function hide_shipping_when_free_is_available( $rates, $package ) {
	// Only modify rates if free_shipping is present
	if ( isset( $rates['free_shipping'] ) ) {
		// To unset a single rate/method, do the following. This example unsets flat_rate shipping
		unset( $rates['flat_rate'] );
		// To unset all methods except for free_shipping, do the following
		$free_shipping = $rates['free_shipping'];
		$rates = array();
		$rates['free_shipping'] = $free_shipping;
	}
	return $rates;
}

/**
* widget area for frontpage to display a slider or similar
*/
if (function_exists('register_sidebar')) {

	register_sidebar(array(
		'name' => 'Frontpage Precontent',
		'id'   => 'frontpage-precontent',
		'description'   => 'Displayed on frontpage only.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>'
	));

}






/*-----------------------------------------------------------------------------------*/
/* Don't add any code below here or the sky will fall down */
/*-----------------------------------------------------------------------------------*/
?>
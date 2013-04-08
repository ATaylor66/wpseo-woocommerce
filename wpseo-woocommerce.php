<?php
/*
Plugin Name: Yoast WooCommerce SEO
Version: 1.0
Plugin URI: http://yoast.com/wordpress/yoast-woocommerce-seo/
Description: This extenstion to WooCommerce and WordPress SEO by Yoast makes sure there's perfect communication between the two plugins.
Author: Joost de Valk
Author URI: http://yoast.com

Copyright 2013 Joost de Valk (email: joost@yoast.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

$options = get_option( 'wpseo_woo' );
if ( isset( $options['license'] ) && !empty( $options['license'] ) ) {
	if ( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
		// load our custom updater
		include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
	}

	$edd_updater = new EDD_SL_Plugin_Updater( 'http://yoast.com', __FILE__, array(
			'version'   => '1.0', // current version number
			'license'   => trim( $options['license'] ), // license key (used get_option above to retrieve from DB)
			'item_name' => 'WooCommerce Yoast SEO', // name of this plugin
			'author'    => 'Joost de Valk' // author of this plugin
		)
	);
}

class Yoast_WooCommerce_SEO {

	/**
	 * Class constructor, basically hooks all the required functionality.
	 */
	function __construct() {
		$wpseo_options = get_wpseo_options();

		// OpenGraph
		add_filter( 'wpseo_opengraph_type', array( $this, 'return_type_product' ) );
		add_filter( 'wpseo_opengraph_desc', array( $this, 'og_desc_enhancement' ) );
		add_action( 'wpseo_opengraph', array( $this, 'og_enhancement' ) );

		// Twitter
		add_filter( 'wpseo_twitter_card_type', array( $this, 'return_type_product' ) );
		add_action( 'wpseo_twitter', array( $this, 'twitter_enhancement' ) );

		// Products tab columns
		add_filter( 'manage_product_posts_columns', array( $this, 'column_heading' ), 11, 1 );

		// Move Woo box above SEO box
		add_action( 'admin_footer', array( $this, 'footer_js' ) );

		// Product activation
		add_action( 'add_option_wpseo_woo', array( $this, 'activate_license' ) );
		add_action( 'update_option_wpseo_woo', array( $this, 'activate_license' ) );

		// Admin page
		add_action( 'admin_init', array( $this, 'options_init' ) );
		add_action( 'admin_menu', array( $this, 'register_settings_page' ), 20 );

		if ( isset( $wpseo_options['breadcrumbs-enable'] ) && $wpseo_options['breadcrumbs-enable'] && class_exists( 'WPSEO_Breadcrumbs' ) ) {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
			add_action( 'woocommerce_before_main_content', array( $this, 'woo_wpseo_breadcrumbs' ) );
		}
	}

	/**
	 * Registers the settings page in the WP SEO menu
	 *
	 * @since 1.0
	 */
	function register_settings_page() {
		add_submenu_page( 'wpseo_dashboard', 'WooCommerce SEO', 'WooCommerce SEO', 'manage_options', 'wpseo_woo', array( $this, 'admin_panel' ) );
	}

	/**
	 * Registers the wpseo_woo setting for Settings API
	 *
	 * @since 1.0
	 */
	function options_init() {
		register_setting( 'yoast_wpseo_woo_options', 'wpseo_woo' );
	}

	/**
	 * See if there's a license to activate
	 *
	 * @since 1.0
	 */
	function activate_license() {
		$options = get_option( 'wpseo_woo' );

		if ( !isset( $options['license'] ) || empty( $options['license'] ) ) {
			unset( $options['license'] );
			unset( $options['license-status'] );
			update_option( 'wpseo_woo', $options );
			return;
		}

		if ( 'valid' == $options['license-status'] ) {
			return;
		} else if ( isset( $options['license'] ) ) {
			// data to send in our API request
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $options['license'],
				'item_name'  => urlencode( 'WooCommerce Yoast SEO' ) // the name of our product in EDD
			);

			// Call the custom API.
			$url      = add_query_arg( $api_params, 'http://yoast.com/' );
			$args     = array(
				'timeout' => 25,
				'rand'    => rand( 1000, 9999 )
			);
			$response = wp_remote_get( $url, $args );

			if ( is_wp_error( $response ) ) {
				return;
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "valid" or "invalid"
			$options['license-status'] = $license_data->license;
			update_option( 'wpseo_woo', $options );
		}
	}

	/**
	 * Builds the admin page
	 */
	function admin_panel() {
		$options = get_option( 'wpseo_woo' );
		$options = wp_parse_args( (array) $options, array() );

		if ( isset( $_GET['deactivate'] ) && 'true' == $_GET['deactivate'] ) {

			if ( wp_verify_nonce( $_GET['nonce'], 'yoast_woo_seo_deactivate_license' ) === false )
				return;

			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $options['license'],
				'item_name'  => urlencode( 'WooCommerce Yoast SEO' )
			);

			// Send the remote request
			$url = add_query_arg( $api_params, 'http://yoast.com/' );

			$response = wp_remote_get( $url, array( 'timeout' => 25, 'sslverify' => false ) );

			if ( !is_wp_error( $response ) ) {
				$response = json_decode( $response['body'] );

				if ( 'deactivated' == $response->license || 'failed' == $response->license ) {
					unset( $options['license'] );
					$options['license-status'] = 'invalid';
					update_option( 'wpseo_woo', $options );
				}
			}

			echo '<script type="text/javascript">document.location = "' . admin_url( 'admin.php?page=wpseo_woo' ) . '"</script>';
		}
		?>
		<div class="wrap">

		<a href="http://yoast.com/wordpress/woocommerce-yoast-seo/">
			<div id="yoast-icon"
				 style="background: url('<?php echo WPSEO_URL; ?>images/wordpress-SEO-32x32.png') no-repeat;"
				 class="icon32"><br/></div>
		</a>

		<h2 id="wpseo-title"><?php _e( "Yoast WordPress SEO: ", 'yoast-woo-seo' ); _e( 'WooCommerce SEO Settings', 'yoast-woo-seo' ); ?></h2>

		<form action="<?php echo admin_url( 'options.php' ); ?>" method="post" id="wpseo-conf">

		<?php

		settings_fields( 'yoast_wpseo_woo_options' );

		$license_active = false;
		if ( isset( $options['license-status'] ) && $options['license-status'] == 'valid' )
			$license_active = true;

		echo '<h2>' . __( 'License', 'yoast-woo-seo' ) . '</h2>';
		echo '<label class="textinput" for="license">' . __( 'License Key', 'yoast-woo-seo' ) . ':</label> '
			. '<input id="license" class="textinput" type="text" name="wpseo_woo[license]" value="'
			. ( isset( $options['license'] ) ? $options['license'] : '' ) . '"/><br/>';
		echo '<p class="clear description">' . __( 'License Status', 'yoast-woo-seo' ) . ': ' . ( ( $license_active ) ? '<span style="color:#090; font-weight:bold">' . __( 'active', 'yoast-woo-seo' ) . '</span>' : '<span style="color:#f00; font-weight:bold">' . __( 'inactive', 'yoast-woo-seo' ) . '</span>' ) . '</p>';
		echo '<input type="hidden" name="wpseo_woo[license-status]" value="' . ( ( $license_active ) ? 'valid' : 'invalid' ) . '"/>';

		if ( $license_active ) {
			echo '<div>';
			echo '<p><a href="' . admin_url( 'admin.php?page=wpseo_woo&deactivate=true&nonce=' . wp_create_nonce( 'yoast_woo_seo_deactivate_license' ) ) . '" class="button">' . __( 'Deactivate License', 'yoast-woo-seo' ) . '</a></p>';

			echo '<h2>' . __( 'Twitter Product Cards', 'yoast-woo-seo' ) . '</h2>';
			echo '<div>';
			echo '<p>' . __( 'Twitter allows you to display two pieces of data in the Twitter card, pick which two are shown:', 'yoast-woo-seo' ) . '</p>';
			echo '<label for="datatype1" class="checkbox">' . __( 'Data 1', 'yoast-woo-seo' ) . ':</label> ';
			echo '<select class="textinput" id="datatype1" name="wpseo_woo[data1_type]">';
			foreach ( array( 'Price', 'Stock' ) as $data_type ) {
				$sel = '';
				if ( strtolower( $data_type ) == $options['data1_type'] )
					$sel = ' selected';
				echo '<option value="' . strtolower( $data_type ) . '"' . $sel . '>' . $data_type . '</option>';
			}
			foreach ( get_object_taxonomies( 'product', 'objects' ) as $tax ) {
				$sel = '';
				if ( strtolower( $tax->name ) == $options['data1_type'] )
					$sel = ' selected';
				echo '<option value="' . strtolower( $tax->name ) . '"' . $sel . '>' . $tax->labels->name . '</option>';
			}
			echo '</select>';
			echo '<br class="clear"/>';
			echo '<label for="datatype2" class="checkbox">' . __( 'Data 2', 'yoast-woo-seo' ) . ':</label> ';
			echo '<select class="textinput" id="datatype2" name="wpseo_woo[data2_type]">';
			foreach ( array( 'Price', 'Stock' ) as $data_type ) {
				$sel = '';
				if ( strtolower( $data_type ) == $options['data2_type'] )
					$sel = ' selected';
				echo '<option value="' . strtolower( $data_type ) . '"' . $sel . '>' . $data_type . '</option>';
			}
			foreach ( get_object_taxonomies( 'product', 'objects' ) as $tax ) {
				$sel = '';
				if ( $tax->name == $options['data2_type'] )
					$sel = ' selected';
				echo '<option value="' . $tax->name . '"' . $sel . '>' . $tax->labels->name . '</option>';
			}
			echo '</select>';
			echo '</div>';
		}

		echo '<div class="submit"><input type="submit" class="button-primary" name="submit" value="' . __( "Save Settings", 'yoast-woo-seo' ) . '"/></div>';
	}

	/**
	 * Adds a bit of JS that moves the meta box for WP SEO below the WooCommerce box.
	 */
	function footer_js() {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				// Show WooCommerce box before WP SEO metabox
				if ( $('#woocommerce-product-data').length > 0 && $('#wpseo_meta').length > 0 ) {
					$('#woocommerce-product-data').insertBefore($('#wpseo_meta'));
				}
			});
		</script>
	<?php
	}

	/**
	 * Clean up the columns in the edit products page.
	 *
	 * @param array $columns
	 * @return mixed
	 */
	function column_heading( $columns ) {
		unset( $columns['wpseo-title'], $columns['wpseo-metadesc'], $columns['wpseo-focuskw'] );

		return $columns;
	}

	/**
	 * Output WordPress SEO crafted breadcrumbs, instead of WooCommerce ones.
	 */
	function woo_wpseo_breadcrumbs() {
		global $wpseo_bc;
		$wpseo_bc->breadcrumb( '<nav class="woocommerce-breadcrumb">', '</nav>' );
	}

	/**
	 * Adds the other product images to the OpenGraph output
	 */
	function og_enhancement() {
		if ( !is_singular( 'product' ) || !function_exists( 'get_product' ) )
			return;

		global $wpseo_og;

		$product = get_product( get_the_ID() );
		if ( !is_object( $product ) ) {
			return;
		}

		$img_ids = $product->get_gallery_attachment_ids();

		if ( $img_ids ) {
			foreach ( $img_ids as $img_id ) {
				$img_url = wp_get_attachment_url( $img_id );
				$wpseo_og->image_output( $img_url );
			}
		}
	}

	/**
	 * @param $desc
	 * @return string
	 */
	function og_desc_enhancement( $desc ) {
		if ( !is_singular( 'product' ) || !function_exists( 'get_product' ) )
			return $desc;

		return strip_tags( get_the_excerpt() );
	}

	/**
	 * Output the extra data for the Twitter Card
	 */
	function twitter_enhancement() {
		if ( !is_singular( 'product' ) || !function_exists( 'get_product' ) )
			return;

		$product = get_product( get_the_ID() );

		$product_atts = array(
			'domain' => get_bloginfo( 'site_name' )
		);

		$options = get_option( 'wpseo_woo' );

		$i = 1;
		while ( $i < 3 ) {
			switch ( $options['data' . $i . '_type'] ) {
				case 'stock':
					$product_atts['label' . $i] = __( 'In stock', 'woocommerce' );
					$product_atts['data' . $i]  = ( $product->is_in_stock() ) ? __( 'Yes' ) : __( 'No' );
					break;
				case 'category':
					$product_atts['label' . $i] = __( 'Category', 'woocommerce' );
					$product_atts['data' . $i]  = strip_tags( get_the_term_list( get_the_ID(), 'product_cat', '', ', ' ) );
					break;
				case 'price':
					$product_atts['label' . $i] = __( 'Price', 'woocommerce' );
					$product_atts['data' . $i]  = get_woocommerce_currency_symbol() . ' ' . $product->get_price();
					break;
				default:
					$tax                        = get_taxonomy( $options['data' . $i . '_type'] );
					$product_atts['label' . $i] = $tax->labels->name;
					$product_atts['data' . $i]  = strip_tags( get_the_term_list( get_the_ID(), $tax->name, '', ', ' ) );
					break;
			}
			$i++;
		}

		foreach ( $product_atts as $label => $value ) {
			echo '<meta name="twitter:' . $label . '" content="' . $value . '"/>' . "\n";
		}
	}

	/**
	 * Return 'product' when current page is, well... a product.
	 *
	 * @param string $type Passed on without changing if not a product.
	 * @return string
	 */
	function return_type_product( $type ) {
		if ( is_singular( 'product' ) )
			return 'product';

		return $type;
	}
}

function initialize_yoast_woocommerce_seo() {
	global $yoast_woo_seo;
	$yoast_woo_seo = new Yoast_WooCommerce_SEO();
}

add_action( 'plugins_loaded', 'initialize_yoast_woocommerce_seo', 20 );

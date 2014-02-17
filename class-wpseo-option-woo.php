<?php
/**
 * @package Internals
 * @since      1.1.0
 * @version    1.1.0
 */

// Avoid direct calls to this file
if ( ! class_exists( 'Yoast_WooCommerce_SEO' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


/*******************************************************************
 * Option: wpseo_woo
 *******************************************************************/
if ( ! class_exists( 'WPSEO_Option_Woo' ) && class_exists( 'WPSEO_Option' ) ) {

	class WPSEO_Option_Woo extends WPSEO_Option {

		/**
		 * @var  string  option name
		 */
		public $option_name = 'wpseo_woo';

		/**
		 * @var  string  option group name for use in settings forms
		 */
		public $group_name = 'wpseo_woo_options';

		/**
		 * @var  bool  whether to include the option in the return for WPSEO_Options::get_all()
		 */
		public $include_in_all = false;

		/**
		 * @var  bool  whether this option is only for when the install is multisite
		 */
		public $multisite_only = false;
		
		/**
		 * @var int Database version to check whether the plugins options need updating.
		 */
		public $db_version = 2;

		/**
		 * @var  array  Array of defaults for the option
		 *        Shouldn't be requested directly, use $this->get_defaults();
		 */
		protected $defaults = array(
			// Non-form fields, set via validation routine / license activation method
			'dbversion'           => 0, // leave default as 0 to ensure activation/upgrade works
			'license-status'      => 'invalid',

			// Form fields:
			'license'             => '', // text field
			'data1_type'          => 'price',
			'data2_type'          => 'stock',
			'schema_brand'        => '',
			'schema_manufacturer' => '',
			'breadcrumbs'         => true,
			'hide_columns'        => true,
			'metabox_woo_top'     => true,
		);
		
		/**
		 * @var	array	$license_states Array of possible license states for validation purposes
		 */
		protected $license_states = array(
			'valid',
			'invalid',
		);

		/**
		 * @var	array	$valid_data_types Array of pre-defined valid data types, will be enriched with taxonomies
		 */
		public $valid_data_types = array();



		/**
		 * Add the actions and filters for the option
		 *
		 * @return \WPSEO_Option_Woo
		 */
		protected function __construct() {
			parent::__construct();

			// Set and translate the valid data types
			$this->valid_data_types = array(
				'price'	=> __( 'Price', 'woocommerce' ),
				'stock'	=> __( 'Stock', 'woocommerce' ),
			);
		}


		/**
		 * Get the singleton instance of this class
		 *
		 * @return object
		 */
		public static function get_instance() {
			if ( ! ( self::$instance instanceof self ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		/**
		 * Validate the option
		 *
		 * @param  array $dirty New value for the option
		 * @param  array $clean Clean value for the option, normally the defaults
		 * @param  array $old   Old value of the option
		 *
		 * @return  array      Validated clean value for the option to be saved to the database
		 */
		protected function validate_option( $dirty, $clean, $old ) {

			// Have we receive input from a short (license only) form ?
			$short = ( isset( $dirty['short_form'] ) && $dirty['short_form'] === 'on' ) ? true : false;

			// Prepare an array of valid data types and taxonomies to validate against
			$valid_data_types = array_keys( $this->valid_data_types );
			$valid_taxonomies = array();
			$taxonomies       = get_object_taxonomies( 'product', 'objects' );
			if ( is_array( $taxonomies ) && $taxonomies !== array() ) {
				foreach ( $taxonomies as $tax ) {
					$tax_name           = strtolower( $tax->name );
					$valid_data_types[] = $tax_name;
					$valid_taxonomies[] = $tax_name;
				}
			}
			unset( $taxonomies, $tax, $tax_name );


			foreach ( $clean as $key => $value ) {
				switch ( $key ) {
					case 'dbversion':
						$clean[$key] = $this->db_version;
						break;
						
					case 'data1_type':
					case 'data2_type':
						if ( isset( $dirty[$key] ) ) {
							if ( in_array( $dirty[$key], $valid_data_types, true ) ) {
								$clean[$key] = $dirty[$key];
							}
							else if ( sanitize_title_with_dashes( $dirty[$key] ) === $dirty[$key] ) {
								// Allow taxonomies which may not be registered yet
								$clean[$key] = $dirty[$key];
							}
						}
						else if ( $short && isset( $old[$key] ) ) {
							if ( in_array( $old[$key], $valid_data_types, true ) ) {
								$clean[$key] = $old[$key];
							}
							else if ( sanitize_title_with_dashes( $old[$key] ) === $old[$key] ) {
								// Allow taxonomies which may not be registered yet
								$clean[$key] = $old[$key];
							}
						}
						break;

					case 'schema_brand':
					case 'schema_manufacturer':
						if ( isset( $dirty[$key] ) ) {
							if ( in_array( $dirty[$key], $valid_taxonomies, true ) ) {
								$clean[$key] = $dirty[$key];
							}
							else if ( sanitize_title_with_dashes( $dirty[$key] ) === $dirty[$key] ) {
								// Allow taxonomies which may not be registered yet
								$clean[$key] = $dirty[$key];
							}
						}
						else if ( $short && isset( $old[$key] ) ) {
							if ( in_array( $old[$key], $valid_taxonomies, true ) ) {
								$clean[$key] = $old[$key];
							}
							else if ( sanitize_title_with_dashes( $old[$key] ) === $old[$key] ) {
								// Allow taxonomies which may not be registered yet
								$clean[$key] = $old[$key];
							}
						}
						break;

					/* boolean (checkbox) field - may not be in form */
					case 'breadcrumbs':
					case 'hide_columns':
					case 'metabox_woo_top':
						if ( isset( $dirty[$key] ) ) {
							$clean[$key] = self::validate_bool( $dirty[$key] );
						}
						else if ( $short && isset( $old[$key] ) ) {
							$clean[$key] = self::validate_bool( $old[$key] );
						}
						else {
							$clean[$key] = false;
						}
						break;
				}
			}
			
			/* Validate the license */
			$license = $this->validate_license( $dirty, $old );
			$clean['license']        = $license['license'];
			$clean['license-status'] = $license['license-status'];

			return $clean;
		}


		/**
		 * See if there's a license to activate
		 *
		 * @since 1.0
		 *
		 * @param  array $dirty New value for the option
		 * @param  array $old   Old value of the option
		 *
		 * @return  array      Validated clean values related to the license
		 */
		function validate_license( $dirty, $old ) {
			$result = array(
				'license'        => $this->defaults['license'],
				'license-status' => $this->defaults['license-status'],
			);

			if ( ! isset( $dirty['license'] ) || $dirty['license'] === '' ) {
				return $result;
			}
	
			if ( ( isset( $dirty['license-status'] ) && 'valid' === $dirty['license-status'] )  && $dirty['license'] === $old['license'] ) {
				$result['license']        = $dirty['license'];
				$result['license-status'] = $dirty['license-status'];
			}
			else if ( ! isset( $dirty['license-status'] ) && ( ( isset( $old['license-status'] ) && 'valid' === $old['license-status'] ) && $dirty['license'] === $old['license'] ) ) {
				$result['license']        = $old['license'];
				$result['license-status'] = $old['license-status'];
			}
			else {
				// data to send in our API request
				$api_params = array(
					'edd_action' => 'activate_license',
					'license'    => $dirty['license'],
					'item_name'  => urlencode( $GLOBALS['yoast_woo_seo']->plugin_name ),
				);
	
				// Call the custom API.
				$url      = add_query_arg( $api_params, $GLOBALS['yoast_woo_seo']->update_host );
				$args     = array(
					'timeout' => 25,
					'rand'    => rand( 1000, 9999 ),
				);
				$response = wp_remote_get( $url, $args );
	
				if ( ! is_wp_error( $response ) ) {
					// decode the license data
					$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
					// $license_data->license will be either "valid" or "invalid"
					if ( $license_data->license === 'valid' ) {
						$result['license'] = $dirty['license'];
					}
					if ( in_array( $license_data->license, $this->license_states, true ) ) {
						$result['license-status'] = $license_data->license;
					}
				}
			}
			return $result;
		}
	
		/**
		 * Clean a given option value
		 *
		 * @param  array  $option_value    Old (not merged with defaults or filtered) option value to
		 *                                 clean according to the rules for this option
		 * @param  string $current_version (optional) Version from which to upgrade, if not set,
		 *                                 version specific upgrades will be disregarded
		 *
		 * @return  array            Cleaned option
		 */
		/*protected function clean_option( $option_value, $current_version = null ) {

			return $option_value;
		}*/

	} /* End of class WPSEO_Option_Woo */

} /* End of class-exists wrapper */
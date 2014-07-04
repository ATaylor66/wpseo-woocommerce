<?php

class WPSEO_Woo_Products_Feed_Request {

	const REWRITE_RULE = '^products-feed.rss$';

	/**
	 * Setup the Rewrite Rule Hooks
	 */
	public function setup() {
		add_filter( 'rewrite_rules_array', array( $this, 'add_rewrite_rule' ) );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
		add_action( 'wp_loaded', array( $this, 'flush_rules' ) );
		add_action( 'template_redirect', array( $this, 'catch_request' ), 1 );
	}

	/**
	 * Add custom query variables to WordPress query variables
	 *
	 * @param $vars
	 *
	 * @return array query_vars
	 */
	public function add_query_vars( $vars ) {
		array_push( $vars, 'wpseo-woo-products-feed' );

		return $vars;
	}

	/**
	 * Add Editors' Pick rewrite rules to WordPress rewrite rules
	 *
	 * @param $rules
	 *
	 * @return array rules
	 */
	public function add_rewrite_rule( $rules ) {
		$newrules                     = array();
		$newrules[self::REWRITE_RULE] = 'index.php?wpseo-woo-products-feed=1';

		return $newrules + $rules;
	}

	/**
	 * Flush rules if they're not set yet
	 */
	public function flush_rules() {
		$rules = get_option( 'rewrite_rules' );

		if ( ! isset( $rules[self::REWRITE_RULE] ) ) {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}
	}

	/**
	 * Catch the Editors' Pick request
	 */
	public function catch_request() {
		global $wp_query;

		if ( $wp_query->get( 'wpseo-woo-products-feed' ) ) {

			$products_feed = new WPSEO_Woo_Products_Feed();
			echo $products_feed->generate_rss();

			exit;

		}
	}

}
<?php

class WPSEO_Woo_Products_Feed_Meta_Box extends WPSEO_Metabox {

	//private $options;

	public function __construct() {
		//$this->options = WPSEO_News::get_options();
	}

	/**
	 * The metaboxes to display and save for the tab
	 *
	 * @return array $mbs
	 */
	public function get_meta_boxes() {
		$mbs                             = array();

		$mbs['newssitemap-stocktickers'] = array(
				"name"        => "newssitemap-stocktickers",
				"std"         => "",
				"type"        => "text",
				"title"       => __( "Stock Tickers", 'wordpress-seo-news' ),
				"description" => __( 'A comma-separated list of up to 5 stock tickers of the companies, mutual funds, or other financial entities that are the main subject of the article. Each ticker must be prefixed by the name of its stock exchange, and must match its entry in Google Finance. For example, "NASDAQ:AMAT" (but not "NASD:AMAT"), or "BOM:500325" (but not "BOM:RIL").', 'wordpress-seo-news' ),
		);


		return $mbs;
	}

	/**
	 * Add the meta boxes to meta box array so they get saved
	 *
	 * @param $meta_boxes
	 *
	 * @return array
	 */
	public function save( $meta_boxes ) {
		$meta_boxes = array_merge( $meta_boxes, $this->get_meta_boxes() );

		return $meta_boxes;
	}


	/**
	 * Add WordPress SEO meta fields to WPSEO meta class
	 *
	 * @param $meta_fields
	 *
	 * @return mixed
	 */
	public function add_meta_fields_to_wpseo_meta( $meta_fields ) {

		$meta_fields['products_feed'] = $this->get_meta_boxes();

		return $meta_fields;
	}

	/**
	 * The tab header
	 */
	public function header() {
		global $post;

		if('product' == $post->post_type) {
			echo '<li class="news"><a class="wpseo_tablink" href="#wpseo_products_feed">' . __( 'Products Feed', 'wordpress-seo-news' ) . '</a></li>';
		}

	}

	/**
	 * The tab content
	 */
	public function content() {
		global $post;

		if('product' != $post->post_type) {
			return;
		}

		// Build tab content
		$content = '';
		foreach ( $this->get_meta_boxes() as $meta_key => $meta_box ) {
			$content .= $this->do_meta_box( $meta_box, $meta_key );
		}
		$this->do_tab( 'products_feed', __( 'Products Feed', 'wordpress-seo-news' ), $content );
	}


}
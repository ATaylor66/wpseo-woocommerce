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

		$products_feed_manager = new WPSEO_Woo_Products_Feed_Manager();
		$categories = $products_feed_manager->get_categories();

		// Format array for input select field
		$input_categories = array();
		$input_categories['none'] = 'None';
		if(count($categories) > 0 ) {
			foreach($categories as $category) {
				$input_categories[$category] = $category;
			}
		}

		$mbs['products-feed-category[]'] = array(
			"name"        => "category",
			"std"         => "",
			"type"        => "select",
			"options"     => $input_categories,
			"title"       => __( "Product category", 'yoast-woo-seo' ),
			"class"       => "wpseo_woo_pf_categories"
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
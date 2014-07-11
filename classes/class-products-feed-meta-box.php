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
		$mbs = array();


		$mbs['pf-condition'] = array(
			"name"        => "products-feed-condition",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'new'         => __( 'New', 'yoast-woo-seo' ),
				'used'        => __( 'Used', 'yoast-woo-seo' ),
				'refurbished' => __( 'Refurbished', 'yoast-woo-seo' )
			),
			"title"       => __( 'Condition', 'yoast-woo-seo' ),
			"description" => __( 'Condition or state of the item.', 'yoast-woo-seo' ),
		);

		// Unique Product Identifiers

		$mbs['pf-brand'] = array(
			"name"        => "products-feed-brand",
			"std"         => "",
			"type"        => "text",
			"title"       => __( 'Brand', 'yoast-woo-seo' ),
			"description" => __( 'You must not provide your store name as the brand unless you manufacture the product.', 'yoast-woo-seo' ),
		);

		$mbs['pf-gtin'] = array(
			"name"        => "products-feed-gtin",
			"std"         => "",
			"type"        => "text",
			"title"       => __( 'GTIN', 'yoast-woo-seo' ),
			"description" => __( "Use the 'gtin' attribute to submit Global Trade Item Numbers (GTINs).", 'yoast-woo-seo' ),
		);

		$mbs['pf-mpn'] = array(
			"name"        => "products-feed-mpn",
			"std"         => "",
			"type"        => "text",
			"title"       => __( 'MPN', 'yoast-woo-seo' ),
			"description" => __( 'A Manufacturer Part Number is used to reference and identify a product using a manufacturer specific naming other than GTIN.', 'yoast-woo-seo' ),
		);

		$mbs['pf-identifier_exists'] = array(
			"name"        => "products-feed-identifier_exists",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'true'  => __( 'TRUE', 'yoast-woo-seo' ),
				'false' => __( 'FALSE', 'yoast-woo-seo' )
			),
			"title"       => __( 'Identifier Exists', 'yoast-woo-seo' ),
			"description" => __( "Some products don't have an unique identifier like gtin/mpn, if this is one of these products leave the above identifier fields blank and set this to FALSE.", 'yoast-woo-seo' ),
		);

		// Apparel Products
		$mbs['pf-gender'] = array(
			"name"        => "products-feed-gender",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'male'   => __( 'Male', 'yoast-woo-seo' ),
				'female' => __( 'Female', 'yoast-woo-seo' ),
				'unisex' => __( 'Unisex', 'yoast-woo-seo' )
			),
			"title"       => __( 'Gender', 'yoast-woo-seo' ),
			"description" => __( "Select the gender this product targets.", 'yoast-woo-seo' ),
			"class"       => 'pf-cat-specific pf-cat-apparel-accessories',
		);

		$mbs['pf-age_group'] = array(
			"name"        => "products-feed-age_group",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'newborn' => __( 'Newborn', 'yoast-woo-seo' ),
				'infant'  => __( 'Infant', 'yoast-woo-seo' ),
				'toddler' => __( 'Toddler', 'yoast-woo-seo' ),
				'kids'    => __( 'Kids', 'yoast-woo-seo' ),
				'adult'   => __( 'Adult', 'yoast-woo-seo' )
			),
			"title"       => __( 'Age Group', 'yoast-woo-seo' ),
			"description" => __( "Use this to indicate the demographic of your item.", 'yoast-woo-seo' ),
			"class"       => 'pf-cat-specific pf-cat-apparel-accessories',
		);

		$mbs['pf-color'] = array(
			"name"        => "products-feed-color",
			"std"         => "",
			"type"        => "text",
			"title"       => __( 'Color', 'yoast-woo-seo' ),
			"description" => __( "This defines the dominant color(s) for an item, state colors as text (e.g. 'Black'). When a single item has multiple colors, you can submit up to two additional values as accent colors. Combine the colors with ‘/’ in order of prominence, limit the number of colors submitted to three values.", 'yoast-woo-seo' ),
			"class"       => 'pf-cat-specific pf-cat-apparel-accessories',
		);

		$mbs['pf-size'] = array(
			"name"        => "products-feed-size",
			"std"         => "",
			"type"        => "text",
			"title"       => __( 'Size', 'yoast-woo-seo' ),
			"description" => __( "This indicates the size of a product. You may provide any values which are appropriate to your items. You can also submit the ‘size type’ and ‘size system’ attributes to provide more details about your sizing.", 'yoast-woo-seo' ),
			"class"       => 'pf-cat-specific pf-cat-apparel-accessories',
		);

		$mbs['pf-size_type'] = array(
			"name"        => "products-feed-size_type",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'regular'      => __( 'Regular', 'yoast-woo-seo' ),
				'petite'       => __( 'Petite', 'yoast-woo-seo' ),
				'plus'         => __( 'Plus', 'yoast-woo-seo' ),
				'big and tall' => __( 'Big and Tall', 'yoast-woo-seo' ),
				'maternity'    => __( 'Maternity', 'yoast-woo-seo' )
			),
			"title"       => __( 'Size Type', 'yoast-woo-seo' ),
			"description" => __( "Use this attribute to indicate the cut of your item.", 'yoast-woo-seo' ),
			"class"       => 'pf-cat-specific pf-cat-apparel-accessories',
		);

		$mbs['pf-size_system'] = array(
			"name"        => "products-feed-size_system",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'US'         => __( 'US', 'yoast-woo-seo' ),
				'UK'         => __( 'UK', 'yoast-woo-seo' ),
				'EU'         => __( 'EU', 'yoast-woo-seo' ),
				'DE'         => __( 'DE', 'yoast-woo-seo' ),
				'FR'         => __( 'FR', 'yoast-woo-seo' ),
				'JP'         => __( 'JP', 'yoast-woo-seo' ),
				'CN (China)' => __( 'CN (China)', 'yoast-woo-seo' ),
				'IT'         => __( 'IT', 'yoast-woo-seo' ),
				'BR'         => __( 'BR', 'yoast-woo-seo' ),
				'MEX'        => __( 'MEX', 'yoast-woo-seo' ),
				'AU'         => __( 'AU', 'yoast-woo-seo' ),
			),
			"title"       => __( 'Size System', 'yoast-woo-seo' ),
			"description" => __( "Use this attribute to indicate the country’s sizing system in which you are submitting your item.", 'yoast-woo-seo' ),
			"class"       => 'pf-cat-specific pf-cat-apparel-accessories',
		);

		$mbs['pf-adult'] = array(
			"name"        => "products-feed-adult",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'FALSE' => __( 'FALSE', 'yoast-woo-seo' ),
				'TRUE'  => __( 'TRUE', 'yoast-woo-seo' ),
			),
			"title"       => __( 'Adult', 'yoast-woo-seo' ),
			"description" => __( "Google cares about the family status of the product listings you submit in order to make sure that appropriate content is shown to an appropriate audience. Select 'TRUE' to indicate that this product is considered “adult” or “non-family safe”.", 'yoast-woo-seo' ),
		);

		$mbs['pf-multipack'] = array(
			"name"        => "products-feed-multipack",
			"std"         => "",
			"type"        => "text",
			"title"       => __( 'Multipack', 'yoast-woo-seo' ),
			"description" => __( "Multipacks are packages that include several identical products to create a larger unit of sale, submitted as a single item. Merchant-defined multipacks are custom groups of identical products submitted as a single unit of sale.", 'yoast-woo-seo' ),
		);

		$mbs['pf-is_bundle'] = array(
			"name"        => "products-feed-is_bundle",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'FALSE' => __( 'FALSE', 'yoast-woo-seo' ),
				'TRUE'  => __( 'TRUE', 'yoast-woo-seo' ),
			),
			"title"       => __( 'Is Bundle', 'yoast-woo-seo' ),
			"description" => __( "Merchant-defined bundles are custom groupings of different products defined by a merchant and sold together for a single price. A bundle features a main item sold with various accessories or add-ons, such as a camera combined with a bag and a lens. The main item of a bundle is the featured product of those items included in the bundle.", 'yoast-woo-seo' ),
		);

		// Adwords
		$mbs['pf-adwords_grouping'] = array(
			"name"        => "products-feed-adwords_grouping",
			"std"         => "",
			"type"        => "text",
			"title"       => __( 'Adwords Grouping', 'yoast-woo-seo' ),
			"description" => __( "Used to group products in an arbitrary way. It can be used for product filters to limit a campaign to a group of products, or product targets to bid differently for a group of products. Required if you want to bid differently on different subsets of products. It can only hold one value.", 'yoast-woo-seo' ),
		);

		$mbs['pf-adwords_labels'] = array(
			"name"        => "products-feed-adwords_labels",
			"std"         => "",
			"type"        => "text",
			"title"       => __( 'Adwords Labels', 'yoast-woo-seo' ),
			"description" => __( "Very similar to adwords_grouping, but it will only only work on CPC. It can hold multiple values (seperated by a comma), allowing a product to be tagged with multiple labels.", 'yoast-woo-seo' ),
		);

		$mbs['pf-adwords_redirect'] = array(
			"name"        => "products-feed-adwords_redirect",
			"std"         => "",
			"type"        => "text",
			"title"       => __( 'Adwords Redirect', 'yoast-woo-seo' ),
			"description" => __( "Allows advertisers to specify a separate URL that can be used to track traffic coming from Google Shopping. If this attribute is provided, you must make sure that the URL provided through 'adwords redirect' will redirect to the same website as given in the ‘link’ or ‘mobile link’ attribute.", 'yoast-woo-seo' ),
		);

		// energy_efficiency_class
		$mbs['pf-energy_efficiency_class'] = array(
			"name"        => "products-feed-energy_efficiency_class",
			"std"         => "",
			"type"        => "select",
			"options"     => array(
				'none' => __( 'None', 'yoast-woo-seo' ),
				'A+++' => __( 'A+++', 'yoast-woo-seo' ),
				'A++'  => __( 'A++', 'yoast-woo-seo' ),
				'A+'   => __( 'A+', 'yoast-woo-seo' ),
				'A'    => __( 'A', 'yoast-woo-seo' ),
				'B'    => __( 'B', 'yoast-woo-seo' ),
				'C'    => __( 'C', 'yoast-woo-seo' ),
				'D'    => __( 'D', 'yoast-woo-seo' ),
				'E'    => __( 'E', 'yoast-woo-seo' ),
				'F'    => __( 'F', 'yoast-woo-seo' ),
				'G'    => __( 'G', 'yoast-woo-seo' ),
			),
			"title"       => __( 'Energy efficiency class', 'yoast-woo-seo' ),
			"description" => __( "This attribute allows you to submit the energy label for your applicable products in feeds targeting European Union countries and Switzerland.", 'yoast-woo-seo' ),
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
	 * Save the products feed category
	 */
	public function save_category() {

		// Save the category
		if ( isset( $_POST['post_ID'] ) && isset( $_POST['yoast_wpseo_products-feed-category'] ) ) {
			update_post_meta( $_POST['post_ID'], 'yoast_wpseo_products-feed-category', $_POST['yoast_wpseo_products-feed-category'] );
		}
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

		if ( 'product' == $post->post_type ) {
			echo '<li class="news"><a class="wpseo_tablink" href="#wpseo_products_feed">' . __( 'Products Feed', 'wordpress-seo-news' ) . '</a></li>';
		}

	}

	/**
	 * The tab content
	 */
	public function content() {
		global $post;

		if ( 'product' != $post->post_type ) {
			return;
		}

		// Build tab content
		$content = '';

		// Products Feed Manager
		$products_feed_manager = new WPSEO_Woo_Products_Feed_Manager();

		// Get category from post meta
		$categories_meta = get_post_meta( $post->ID, 'yoast_wpseo_products-feed-category', true );

		// Add the Product category table
		$content .= '<tr><th>Product category:</th><td>';

		// Loop through meta categories
		if ( is_array( $categories_meta ) && count( $categories_meta ) > 0 ) {
			for ( $i = 0; $i < count( $categories_meta ); $i ++ ) {

				$parents = null;
				if ( $i > 0 ) {
					$parents = array();
					for ( $j = 0; $j < $i; $j ++ ) {
						$parents[] = $categories_meta[ $j ];
					}
				}

				// Custom category fields
				$categories = $products_feed_manager->get_categories( $parents );

				// Format array for input select field
				$input_categories = array();
				if ( count( $categories ) > 0 ) {
					foreach ( $categories as $category ) {
						$input_categories[ $category ] = $category;
					}
				}


				$content .= '<select name="yoast_wpseo_products-feed-category[]" id="yoast_wpseo_products-feed-category[]" class="yoast wpseo_woo_pf_categories">' . PHP_EOL;
				foreach ( $input_categories as $input_category ) {
					$content .= '<option value="' . $input_category . '"' . ( ( $categories_meta[ $i ] == $input_category ) ? 'selected="selected"' : '' ) . '>' . $input_category . '</option>' . PHP_EOL;
				}
				$content .= '</select>' . PHP_EOL;

			}
		}

		// Category table closer
		$content .= '</td></tr>';

		// Add the metabox fields
		foreach ( $this->get_meta_boxes() as $meta_key => $meta_box ) {
			$content .= $this->do_meta_box( $meta_box, $meta_key );
		}

		$this->do_tab( 'products_feed', __( 'Products Feed', 'wordpress-seo-news' ), $content );
	}


}
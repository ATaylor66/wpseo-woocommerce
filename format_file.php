<?php

class Tax_Collection {

	private $items;

	/**
	 * Construct
	 */
	public function __construct() {
		$this->items = array();
	}

	/**
	 * Add Collection to Collection
	 *
	 * @param $key
	 */
	public function add( $key ) {
		if ( !isset( $this->items[ $key ] ) ) {
			$this->items[ $key ] = new Tax_Collection();
		}
	}

	/**
	 * Get an item
	 *
	 * @param $key
	 *
	 * @return null
	 */
	public function get( $key ) {
		if ( isset( $this->items[ $key ] ) ) {
			return $this->items[ $key ];
		}

		return null;
	}

	/**
	 * Check if the Collection has items
	 *
	 * @return bool
	 */
	public function has_items() {
		return ( count( $this->items ) > 0 );
	}

	/**
	 * Return all items
	 *
	 * @return array
	 */
	public function get_items() {
		return $this->items;
	}

	/**
	 * Generate a row
	 *
	 * @param $object
	 *
	 * @return string
	 */
	private function generate_row( $object ) {

		$output = '';

		foreach ( $object->get_items() as $key => $item ) {

			$output .= ' "' . $key . '" => array( ';

			if ( is_object( $item ) ) {
				$output .= $this->generate_row( $item );
			}

			$output .= '),';

		}

		return $output;

	}

	/**
	 * Output the code
	 */
	public function output() {

		$output = 'array(';

		if ( count( $this->items ) > 0 ) {

			foreach ( $this->items as $item ) {

				$output .= $this->generate_row( $item );

			}

		}

		$output .= ');';

		echo $output;

	}

}

$fc      = file_get_contents( dirname( __FILE__ ) . '/assets/taxonomy.txt' );
$all_tax = explode( "\n", $fc );

$taxonomies = new Tax_Collection();

foreach ( $all_tax as $tax ) {
	if ( false === stripos( $tax, '>' ) ) {
		$taxonomies->add( trim( $tax ) );
	} else {

		$sub_cats = array_map( 'trim', explode( '>', $tax ) );

		$cur = $taxonomies;

		$sub = trim( array_shift( $sub_cats ) );

		$cur = $taxonomies->get( $sub );

		while ( null !== $cur && count( $sub_cats ) > 0 ) {

			$sub = trim( array_shift( $sub_cats ) );

			$cur->add( $sub );

			$cur = $cur->get( $sub );
		}

	}
}

// Output
$taxonomies->output();

// Exit
exit;
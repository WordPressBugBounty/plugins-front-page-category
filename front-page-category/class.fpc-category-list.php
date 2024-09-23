<?php
/**
 * A customizer class that displays a list of categories.
 *
 * @package front-page-category
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * A list control with drag and drop, allows customizable elements of the site
 * For example a list of category summaries
 */
class FPC_Category_List extends WP_Customize_Control {

	/**
	 * Type of control (for css and js targetting)
	 *
	 * @var string
	 */
	public $type = 'fpc-category-list';

	/**
	 * Class version number
	 *
	 * @var string
	 */
	public $version = '1.0';


	/**
	 * Construct the control
	 *
	 * @param object $manager Control parent object.
	 * @param int    $id Customizer control id.
	 * @param array  $args Arguments.
	 */
	public function __construct( $manager, $id, $args = array() ) {

		parent::__construct( $manager, $id, $args );

		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}


	/**
	 * Display list of checkboxes for categories
	 */
	public function render_content() {

		// Displays checkbox heading.
		echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';

		echo '<span class="description customize-control-description"><p>' . esc_html( $this->description ) . '</p></span>';

		$values = explode( ',', $this->value() );

		// Displays selectable items.
		$categories = get_categories(
			array(
				'hide_empty' => false,
			)
		);
		foreach ( $categories as $category ) {

			// Make sure all array values are ints, and not strings.
			$values = array_map( 'intval', $values );

?>
	<label>
		<input type="checkbox" class="fpc-category" value="<?php echo (int) $category->term_id; ?>" <?php checked( in_array( $category->term_id, $values, true ) ); ?> />
		<?php echo esc_html( $category->name ); ?>
	</label>
<?php

		}

		// The input box that stores the categories.
?>
	<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" class="fpc-hidden-categories" <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>" />
<?php

	}


	/**
	 * Enqueue javascript
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'fpc-customizer', plugins_url( 'fpc-customizer.js', __FILE__ ), array( 'jquery' ), $this->version, true );

		wp_enqueue_style( 'fpc-styles', plugins_url( 'fpc-styles.css', __FILE__ ), $this->version, true );

	}

}

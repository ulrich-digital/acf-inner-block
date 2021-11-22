<?php
/* 
*   ACF -> Neue Feldgruppe erstellen
*   ACF -> Feld hinzufügen -> start_date, end_date (Feldtyp: Datumsauswahl) 
*   Regeln -> Block ist gleich 'restricted' (acf registered block)
*   https://www.billerickson.net/innerblocks-with-acf-blocks/
*   https://www.advancedcustomfields.com/resources/blocks/
*   https://www.advancedcustomfields.com/resources/acf_register_block_type/
*/

add_action('acf/init', 'my_acf_init_block_types');
function my_acf_init_block_types() {

	// Register a restricted block.
        acf_register_block_type(array(
            'name'              => 'restricted',
            'title'             => 'Restricted',
            'description'       => 'A restricted content block.',
            'category'          => 'formatting',
            'mode'              => 'preview',
 	    'category'          => 'formatting',
            'icon'              => 'admin-comments',
            'supports'          => array(
                'align' => true,
                'mode' => false,
                'jsx' => true // notwending für inner Block
            ),
            'render_callback'   => 'my_acf_block_render_callback', // callback
	    // 'render_template'   => 'template-parts/blocks/beispiel.php', // template
	    // 'enqueue_script'    =>  get_template_directory_uri() .'/template-parts/blocks/beispiel.js', 
	    // 'enqueue_style'     =>  get_template_directory_uri() . '/template-parts/blocks/beispiel.css',
        ));
}

function my_acf_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {

	/**
	 * Restricted Block Callback Function.
	 *
	 * @param   array $block The block settings and attributes.
	 * @param   string $content The block inner HTML (empty).
	 * @param   bool $is_preview True during AJAX preview.
	 * @param   (int|string) $post_id The post ID this block is saved to.
	 */

	// Create class attribute allowing for custom "className" and "align" values.
	$classes = '';
	if( !empty($block['className']) ) {
		$classes .= sprintf( ' %s', $block['className'] );
	}
	if( !empty($block['align']) ) {
		$classes .= sprintf( ' align%s', $block['align'] );
	}

	// Load custom field values.
	$start_date = get_field('start_date');
	$end_date = get_field('end_date');

	// Restrict block output (front-end only).
	if( !$is_preview ) {
		$now = time();
		if( $start_date && strtotime($start_date) > $now ) {
			echo sprintf( '<p>Content restricted until %s. Please check back later.</p>', $start_date );
			return;
		}
		if( $end_date && strtotime($end_date) < $now ) {
			echo sprintf( '<p>Content expired on %s.</p>', $end_date );
			return;
		}
	}

	// Define notification message shown when editing.
	if( $start_date && $end_date ) {
		$notification = sprintf( 'Content visible from %s until %s.', $start_date, $end_date );
	} elseif( $start_date ) {
		$notification = sprintf( 'Content visible from %s.', $start_date );
	} elseif( $end_date ) {
		$notification = sprintf( 'Content visible until %s.', $end_date );
	} else {
		$notification = 'Content unrestricted.';
	}
	?>
	<div class="restricted-block <?php echo esc_attr($classes); ?>">
		<span class="restricted-block-notification"><?php echo esc_html( $notification ); ?></span>
		<?php
		$template = array(
			array( 'core/paragraph', array(
				'placeholder' => 'Add a root-level paragraph',
			) ),
			array( 'core/columns', array(), array(
				array( 'core/column', array(), array(
					array( 'core/image', array() ),
				) ),
				array( 'core/column', array(), array(
					array( 'core/paragraph', array(
						'placeholder' => 'Add a inner paragraph'
					) ),
				) ),
			) )
		);
		echo '<InnerBlocks template="' . esc_attr( wp_json_encode( $template ) ) . '" templateLock="insert" />'; // templateLock = "all" or "insert"
		?>
	</div>
	<?php

}


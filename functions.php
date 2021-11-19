<?php
/* 
*   ACF -> Neue Feldgruppe erstellen
*   Regeln -> Block ist gleich 'testimonial'
*   https://www.billerickson.net/innerblocks-with-acf-blocks/
*   
*/

add_action('acf/init', 'my_acf_init_block_types');
function my_acf_init_block_types() {

    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'testimonial',
            'title'             => __('Testimonial'),
            'description'       => __('A custom testimonial block.'),
            'render_template'   => 'template-parts/blocks/testimonial.php',
            'mode'					    => 'preview', // modes: preview, edit, auto
			      // 'enqueue_script'    =>  get_template_directory_uri() .'/template-parts/blocks/testimonial.js',
            'category'          => 'formatting',
            'icon'              => 'admin-comments',
            'keywords'          => array( 'testimonial', 'quote' ),
			      'supports'      	=> [
				      'align'         	  => false,
				      'anchor'        	  => true,
				      'customClassName'   => true,
				      'jsx'           	  => true, // notwending für inner Block
			      ]
        ));
    }
}




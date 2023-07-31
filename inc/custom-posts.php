<?php
add_action( 'init', 'gff_store_locator_custom_post' );
function gff_store_locator_custom_post() {
    register_post_type( 'gff-store-map', [
        'labels' => [
            'name' => __( 'Store Maps' )
        ],
        'public' => false,
        'show_ui' => true,
        'supports' => ['title'],
    ]);
}
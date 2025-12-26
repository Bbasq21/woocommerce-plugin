<?php

namespace Company\OrderMetadata;

class AdminDisplay {

    public function __construct() {
        add_action( 'add_meta_boxes', [ $this, 'add_custom_meta_box' ] );
    }

    public function add_custom_meta_box() {
        add_meta_box(
            'company_order_reference_box',      
            'Company Reference Code',           
            [ $this, 'render_meta_box' ],      
            'shop_order',                       
            'side',                             
            'high'                              
        );
    }

    public function render_meta_box( $post ) {
        
        $order = wc_get_order( $post->ID );
        
        if ( ! $order ) return;

        $reference_code = $order->get_meta( OrderGenerator::META_KEY );

        echo '<div style="padding: 10px; background: #f0f0f1; border-radius: 4px;">';
        if ( $reference_code ) {
            echo '<strong style="font-size: 1.2em; display:block; margin-bottom:5px;">' . esc_html( $reference_code ) . '</strong>';
            echo '<span class="description" style="color:#666;">(Internal Reference)</span>';
        } else {
            echo '<span style="color: #cc0000;">No code generated yet.</span>';
        }
        echo '</div>';
    }
}
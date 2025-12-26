<?php

namespace Company\OrderMetadata;

class OrderGenerator {

    const META_KEY = 'company_reference_code';

    public function __construct() {
        add_action( 'woocommerce_new_order', [ $this, 'generate_order_reference' ], 10, 1 );
    }

    public function generate_order_reference( $order_id ) {
        if ( ! $order_id ) return;
        
        $order = wc_get_order( $order_id );
        if ( ! $order ) return;

        // Evitar duplicados
        if ( $order->get_meta( self::META_KEY ) ) return;

        $year = date( 'Y' );
        $reference_code = sprintf( 'CMP-%d-%s', $order_id, $year );

        $final_code = apply_filters( 'company_order_reference_code', $reference_code, $order );

        $order->update_meta_data( self::META_KEY, sanitize_text_field( $final_code ) );
        $order->save_meta_data();
    }
}
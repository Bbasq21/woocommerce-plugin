<?php

namespace {
    if ( ! function_exists( 'add_action' ) ) {
        function add_action( $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
            return true;
        }
    }
    if ( ! function_exists( 'apply_filters' ) ) {
        function apply_filters( $tag, $value, ...$args ) {
            return $value;
        }
    }
    if ( ! function_exists( 'sanitize_text_field' ) ) {
        function sanitize_text_field( $str ) {
            return trim( $str );
        }
    }
}

namespace Company\OrderMetadata {

class OrderGenerator {

    const META_KEY = '_company_reference_code';

    public function __construct() {
        // Usamos este hook para asegurarnos de que la orden ya existe pero es nueva
        \add_action( 'woocommerce_checkout_order_processed', [ $this, 'generate_order_reference' ], 10, 1 );
    }

    /**
     * Generates and saves the custom reference code.
     *
     * @param int $order_id The Order ID.
     */
    public function generate_order_reference( $order_id ) {
        $order = wc_get_order( $order_id );

        if ( ! $order ) {
            return;
        }

        // Evitar duplicados: Si ya tiene el código, no hacemos nada [cite: 28]
        if ( $order->get_meta( self::META_KEY ) ) {
            return;
        }

        $year = date( 'Y' );
        
        // Formato requerido: CMP-{ORDER_ID}-{YYYY} [cite: 22]
        $reference_code = sprintf( 'CMP-%d-%s', $order_id, $year );

        /**
         * Task 4: Filter to allow other plugins to modify the code.
         * * @param string $reference_code The generated code.
         * @param WC_Order $order The order object.
         */
        $final_code = \apply_filters( 'company_order_reference_code', $reference_code, $order ); // [cite: 41, 43]

        // Guardar como metadato de la orden [cite: 24]
        $order->update_meta_data( self::META_KEY, \sanitize_text_field( $final_code ) );
        $order->save();
    }
}
}

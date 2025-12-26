<?php

namespace Company\OrderMetadata;

class AdminDisplay {

    public function __construct() {
        // Mostrar en el detalle de la orden en Admin
        add_action( 'woocommerce_admin_order_data_after_order_details', [ $this, 'display_reference_code' ] );
    }

    /**
     * Muestra el código de referencia en modo solo lectura.
     * * @param WC_Order $order
     */
    public function display_reference_code( $order ) {
        $reference_code = $order->get_meta( OrderGenerator::META_KEY );

        if ( ! $reference_code ) {
            return;
        }

        ?>
        <div class="form-field form-field-wide">
            <h3><?php _e( 'Company Reference Code', 'company-order-metadata' ); ?></h3>
            <p>
                <strong><?php echo esc_html( $reference_code ); ?></strong> 
                <span class="description"><?php _e( '(Internal Reference - Read Only)', 'company-order-metadata' ); [cite_start]?></span> [cite: 35]
            </p>
        </div>
        <?php
    }
}
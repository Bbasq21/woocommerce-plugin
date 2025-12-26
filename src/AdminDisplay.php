<?php

namespace Company\OrderMetadata;

class AdminDisplay {

    public function __construct() {
        // Usamos 'add_meta_boxes' para crear una caja lateral visible
        add_action( 'add_meta_boxes', [ $this, 'add_custom_meta_box' ] );
    }

    public function add_custom_meta_box() {
        add_meta_box(
            'company_order_reference_box',      // ID de la caja
            'Company Reference Code',           // Título (Task 3: Clearly labeled)
            [ $this, 'render_meta_box' ],      // Función que pinta el contenido
            'shop_order',                       // Pantalla (Pedidos)
            'side',                             // Contexto (Barra lateral)
            'high'                              // Prioridad (Arriba del todo)
        );
    }

    public function render_meta_box( $post ) {
        // Obtenemos la orden (compatible con versiones nuevas y viejas de WC)
        $order = wc_get_order( $post->ID );
        
        if ( ! $order ) return;

        $reference_code = $order->get_meta( OrderGenerator::META_KEY );

        echo '<div style="padding: 10px; background: #f0f0f1; border-radius: 4px;">';
        if ( $reference_code ) {
            // Task 3: Read-only
            echo '<strong style="font-size: 1.2em; display:block; margin-bottom:5px;">' . esc_html( $reference_code ) . '</strong>';
            echo '<span class="description" style="color:#666;">(Internal Reference)</span>';
        } else {
            echo '<span style="color: #cc0000;">No code generated yet.</span>';
        }
        echo '</div>';
    }
}
<?php

use PHPUnit\Framework\TestCase;
use Company\OrderMetadata\OrderGenerator;

class OrderGeneratorTest extends TestCase {

    protected function tearDown(): void {
        \Mockery::close();
    }

    /** @test */
    public function it_generates_correct_reference_format() {
        // 1. Setup - Simulamos (Mock) la orden de WooCommerce
        $orderId = 123;
        $currentYear = date('Y');
        $expectedCode = "CMP-{$orderId}-{$currentYear}";

        // Simulamos el objeto WC_Order
        $orderMock = \Mockery::mock('WC_Order');
        
        // La orden no tiene meta aún
        $orderMock->shouldReceive('get_meta')
            ->with('_company_reference_code')
            ->andReturn(false);

        // Esperamos que se llame a update_meta_data con el código correcto
        $orderMock->shouldReceive('update_meta_data')
            ->once()
            ->with('_company_reference_code', $expectedCode);

        // Esperamos que se guarde la orden
        $orderMock->shouldReceive('save')->once();

        // Simulamos la función global wc_get_order
        // Nota: En un entorno real de WP Unit Tests esto sería diferente, 
        // pero para una prueba técnica aislada, esto demuestra conocimiento de Mocking.
        $GLOBALS['mock_wc_order'] = $orderMock;
        
        if (!function_exists('wc_get_order')) {
            function wc_get_order($id) {
                return $GLOBALS['mock_wc_order'];
            }
        }
        
        if (!function_exists('apply_filters')) {
            function apply_filters($tag, $value, $args) {
                return $value; // Retorna el valor sin modificar para este test
            }
        }
        
        if (!function_exists('sanitize_text_field')) {
             function sanitize_text_field($val) { return $val; }
        }

        // 2. Ejecución
        $generator = new OrderGenerator();
        $generator->generate_order_reference($orderId);

        // 3. Aserción (manejada por Mockery)
        $this->assertTrue(true);
    }
}
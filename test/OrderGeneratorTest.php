<?php

use PHPUnit\Framework\TestCase;
use Company\OrderMetadata\OrderGenerator;

if ( ! function_exists( 'add_action' ) ) {
    function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {}
}

if ( ! function_exists( 'apply_filters' ) ) {
    function apply_filters( $tag, $value, ...$args ) {
        return $value;
    }
}

if ( ! function_exists( 'sanitize_text_field' ) ) {
    function sanitize_text_field( $str ) {
        return $str;
    }
}

if ( ! function_exists( 'wc_get_order' ) ) {
    function wc_get_order( $id ) {
        return $GLOBALS['mock_wc_order'] ?? null;
    }
}

class OrderGeneratorTest extends TestCase {

    protected function tearDown(): void {
        \Mockery::close();
    }

    /** @test */
    public function it_generates_correct_reference_format() {
        $orderId = 123;
        $currentYear = date('Y');
        $expectedCode = "CMP-{$orderId}-{$currentYear}";
        $orderMock = \Mockery::mock('WC_Order');
        $orderMock->shouldReceive('get_meta')
            ->with('company_reference_code')
            ->andReturn(false);
        $orderMock->shouldReceive('update_meta_data')
            ->once()
            ->with('company_reference_code', $expectedCode);
        $orderMock->shouldReceive('save_meta_data')->once();
        $GLOBALS['mock_wc_order'] = $orderMock;
        $generator = new OrderGenerator();
        $generator->generate_order_reference($orderId);
        $this->assertTrue(true);
    }
}
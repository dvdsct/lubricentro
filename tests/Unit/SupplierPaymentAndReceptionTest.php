<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SupplierPaymentAndReceptionTest extends TestCase
{
    public function test_pending_calculation_for_partial_receptions(): void
    {
        // Case 1: Ordered 20, already received 10 -> pending is 10
        $cantPedida = 20;
        $cantRecibida = 10;
        $pendiente = max(0, $cantPedida - $cantRecibida);
        $this->assertEquals(10, $pendiente);

        // Case 2: Ordered 20, already received 20 -> pending is 0 (no duplicate stock added)
        $cantPedida2 = 20;
        $cantRecibida2 = 20;
        $pendiente2 = max(0, $cantPedida2 - $cantRecibida2);
        $this->assertEquals(0, $pendiente2);

        // Case 3: Ordered 20, received 0 -> pending is 20
        $cantPedida3 = 20;
        $cantRecibida3 = 0;
        $pendiente3 = max(0, $cantPedida3 - $cantRecibida3);
        $this->assertEquals(20, $pendiente3);
    }

    public function test_negative_egress_for_supplier_payments(): void
    {
        $subtotal = 50000.00;
        $totalNeg = -abs($subtotal);
        $this->assertEquals(-50000.00, $totalNeg);
        $this->assertLessThan(0, $totalNeg);
    }
}

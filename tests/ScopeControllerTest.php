<?php

use PHPUnit\Framework\TestCase;

class ScopeControllerTest extends TestCase
{
    public function testShouldGetScopes()
    {
        $controller = new \Laravel\Passport\Http\Controllers\ScopeController;

        \Laravel\Passport\Passport::tokensCan($scopes = [
            'place-orders' => 'Place orders',
            'check-status' => 'Check order status',
        ]);

        $result = $controller->all();

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(\Laravel\Passport\Scope::class, $result);
        $this->assertSame(['ID' => 'place-orders', 'description' => 'Place orders'], $result[0]->toArray());
        $this->assertSame(['ID' => 'check-status', 'description' => 'Check order status'], $result[1]->toArray());
    }
}

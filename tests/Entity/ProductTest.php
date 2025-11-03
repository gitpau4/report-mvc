<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Product.
 */
class ProductTest extends TestCase
{
    public function testSetAndGetName(): void
    {
        $product = new Product();
        $product->setName("Anna Svensson");
        $this->assertEquals("Anna Svensson", $product->getName());
    }
    
    public function testSetAndGetValue(): void
    {
        $product = new Product();
        $product->setValue(50);
        $this->assertEquals(50, $product->getValue());
    }
}

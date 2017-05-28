<?php

use Lunaticus\Tile\Knot;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для класса Knot.
 */
class KnotTest extends TestCase
{
    protected $knot;
    protected $testData;

    protected function setUp()
    {
        $this->knot = new Knot(0, 0);
        $this->testData = 50;
    }

    public function testCreateKnot()
    {
        $this->assertInstanceOf(Knot::class, $this->knot);
    }

    public function testGetterCoordinateX()
    {
        $knot = new Knot($this->testData, 0);
        $this->assertEquals($this->testData, $knot->getX());
    }

    public function testGetterCoordinateY()
    {
        $knot = new Knot(0, $this->testData);
        $this->assertEquals($this->testData, $knot->getY());
    }

    public function testGetterHorizontalDistance()
    {
        $property = new ReflectionProperty($this->knot, 'horizontalDistance');
        $property->setAccessible(true);
        $property->setValue($this->knot, $this->testData);

        $this->assertEquals($this->testData, $this->knot->getHorizontalDistance());
    }

    public function testSetterHorizontalDistance()
    {
        $property = new ReflectionProperty($this->knot, 'horizontalDistance');
        $property->setAccessible(true);
        $this->knot->setHorizontalDistance($this->testData);

        $this->assertEquals($this->testData, $property->getValue($this->knot));
    }

    public function testGetterVerticalDistance()
    {
        $property = new ReflectionProperty($this->knot, 'verticalDistance');
        $property->setAccessible(true);
        $property->setValue($this->knot, $this->testData);

        $this->assertEquals($this->testData, $this->knot->getVerticalDistance());
    }

    public function testSetterVerticalDistance()
    {
        $property = new ReflectionProperty($this->knot, 'verticalDistance');
        $property->setAccessible(true);
        $this->knot->setVerticalDistance($this->testData);

        $this->assertEquals($this->testData, $property->getValue($this->knot));
    }

    public function testGetterIsInBlock()
    {
        $property = new ReflectionProperty($this->knot, 'isInBlock');
        $property->setAccessible(true);

        $this->assertFalse($this->knot->getIsInBlock());
        $property->setValue($this->knot, true);
        $this->assertTrue($this->knot->getIsInBlock());
    }

    public function testSetterIsInBlock()
    {
        $property = new ReflectionProperty($this->knot, 'isInBlock');
        $property->setAccessible(true);

        $this->assertFalse($property->getValue($this->knot));
        $this->knot->setIsInBlock(true);
        $this->assertTrue($property->getValue($this->knot));
        $this->knot->setIsInBlock(false);
        $this->assertFalse($property->getValue($this->knot));
    }
}

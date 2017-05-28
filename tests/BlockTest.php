<?php

use Lunaticus\Tile\Block;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для класса Block.
 */
final class BlockTest extends TestCase
{
    protected $testData;

    protected function setUp()
    {
        $this->testData = 50;
    }

    public function testCreateBlockFromValidData()
    {
        $block = new Block(0, 0, 10, 10);
        $this->assertInstanceOf(Block::class, $block);
    }

    public function testCreateBlockWithNegativeCoordinateX()
    {
        $this->expectException(\Exception::class);
        new Block(-1, 0, 10, 10);
    }

    public function testCreateBlockWithNegativeCoordinateY()
    {
        $this->expectException(\Exception::class);
        new Block(0, -1, 10, 10);
    }

    public function testCreateBlockWithNegativeWidth()
    {
        $this->expectException(\Exception::class);
        new Block(0, 0, -10, 10);
    }

    public function testCreateBlockWithZeroWidth()
    {
        $this->expectException(\Exception::class);
        new Block(0, 0, 0, 10);
    }

    public function testCreateBlockWithNegativeHeight()
    {
        $this->expectException(\Exception::class);
        new Block(0, 0, 10, -10);
    }

    public function testCreateBlockWithZeroHeight()
    {
        $this->expectException(\Exception::class);
        new Block(0, 0, 10, 0);
    }

    public function testGetterCoordinateX()
    {
        $block = new Block($this->testData, 0, 10, 10);
        $this->assertEquals($this->testData, $block->getX());
    }

    public function testGetterCoordinateY()
    {
        $block = new Block(0, $this->testData, 10, 10);
        $this->assertEquals($this->testData, $block->getY());
    }

    public function testGetterWidth()
    {
        $block = new Block(0, 0, $this->testData, 10);
        $this->assertEquals($this->testData, $block->getWidth());
    }

    public function testGetterHeight()
    {
        $block = new Block(0, 0, 10, $this->testData);
        $this->assertEquals($this->testData, $block->getHeight());
    }
}

<?php

use Lunaticus\Tile\Block;
use Lunaticus\Tile\Knot;
use Lunaticus\Tile\Tile;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для класса Tile.
 */
class TileTest extends TestCase
{
    protected $width = 250;
    protected $height = 400;

    public function testCreateRectangleWithoutBlocks()
    {
        $tile = new Tile($this->width, $this->height);
        $this->assertInstanceOf(Tile::class, $tile);
    }

    public function testCreateRectangleWithBlocks()
    {
        $blocks = [
            new Block(0, 0, $this->width, 20),
            new Block(0, 20, 150, 30),
        ];
        $tile = new Tile($this->width, $this->height, $blocks);
        $this->assertInstanceOf(Tile::class, $tile);
    }

    /**
     * @expectedException \Exception
     */
    public function testCreateRectangleWithNegativeWidth()
    {
        new Tile(-$this->width, $this->height);
    }

    /**
     * @expectedException \Exception
     */
    public function testCreateRectangleWithZeroWidth()
    {
        new Tile(0, $this->height);
    }

    /**
     * @expectedException \Exception
     */
    public function testCreateRectangleWithNegativeHeight()
    {
        new Tile($this->width, -$this->height);
    }

    /**
     * @expectedException \Exception
     */
    public function testCreateRectangleWithZeroHeight()
    {
        new Tile($this->width, 0);
    }

    /**
     * @expectedException \Exception
     */
    public function testCreateRectangleWithWrongFormatBlocks()
    {
        $blocks = [
            'Some wrong data',
            123,
        ];
        new Tile($this->width, $this->height, $blocks);
    }

    /**
     * @expectedException \Exception
     */
    public function testCreateRectangleWithHorizontallyMisplacedBlocks()
    {
        $blocks = [new Block(0, 0, 300, 20)];
        new Tile($this->width, $this->height, $blocks);
    }

    /**
     * @expectedException \Exception
     */
    public function testCreateRectangleWithVerticallyMisplacedBlocks()
    {
        $blocks = [new Block(0, 350, $this->width, 100)];
        new Tile($this->width, $this->height, $blocks);
    }

    public function testCountKnotsInRectangleWithoutBlocks()
    {
        $tile = new Tile($this->width, $this->height);

        $property = new ReflectionProperty($tile, 'knots');
        $property->setAccessible(true);

        $countVerticals = count($property->getValue($tile));
        $this->assertEquals(2, $countVerticals);

        $firstVertical = $property->getValue($tile)[0];
        $countHorizontals = count($firstVertical);
        $this->assertEquals(2, $countHorizontals);
    }

    public function testAngularKnotsInRectangleWithoutBlocks()
    {
        $tile = new Tile($this->width, $this->height);

        $property = new ReflectionProperty($tile, 'knots');
        $property->setAccessible(true);
        $knots = $property->getValue($tile);

        $expectedKnots = [
            0 => [
                new Knot(0, 0),
                new Knot(0, $this->height),
            ],
            1 => [
                new Knot($this->width, 0),
                new Knot($this->width, $this->height),
            ],
        ];
        $expectedKnots[0][0]->setHorizontalDistance($this->width);
        $expectedKnots[0][1]->setHorizontalDistance($this->width);

        $expectedKnots[0][0]->setVerticalDistance($this->height);
        $expectedKnots[1][0]->setVerticalDistance($this->height);

        $this->assertEquals($expectedKnots, $knots);
    }

    public function testNewBlocksInRectangleWithoutBlocks()
    {
        $tile = new Tile($this->width, $this->height);
        $expectedNewBlocks = [new Block(0, 0, $this->width, $this->height)];
        $this->assertEquals($expectedNewBlocks, $tile->getNewBlocks());
    }

    public function testNewBlocksInRectangleWithOneFullsizeBlock()
    {
        $blocks = [new Block(0, 0, $this->width, $this->height)];
        $tile = new Tile($this->width, $this->height, $blocks);
        $this->assertNull($tile->getNewBlocks());
    }

    public function testCountKnotsInRectangleWithSomeInitialBlocks()
    {
        $blocks = [
            new Block(0, 15, 200, 105),
            new Block(200, 40, 50, 30),
            new Block(110, 120, 65, 80),
        ];
        $tile = new Tile($this->width, $this->height, $blocks);

        $property = new ReflectionProperty($tile, 'knots');
        $property->setAccessible(true);

        $countVerticals = count($property->getValue($tile));
        $this->assertEquals(5, $countVerticals);

        $firstVertical = $property->getValue($tile)[0];
        $countHorizontals = count($firstVertical);
        $this->assertEquals(7, $countHorizontals);
    }

    public function testKnotsInRectangleWithSomeInitialBlocks()
    {
        $blocks = [
            new Block(0, 15, 200, 105),
            new Block(200, 40, 50, 30),
            new Block(110, 120, 65, 70),
        ];
        $tile = new Tile($this->width, $this->height, $blocks);

        $property = new ReflectionProperty($tile, 'knots');
        $property->setAccessible(true);
        $knots = $property->getValue($tile);

        $expectedKnots = [
            0 => [
                new Knot(0, 0),
                new Knot(0, 15),
                new Knot(0, 40),
                new Knot(0, 70),
                new Knot(0, 120),
                new Knot(0, 190),
                new Knot(0, $this->height),
            ],
            1 => [
                new Knot(110, 0),
                new Knot(110, 15),
                new Knot(110, 40),
                new Knot(110, 70),
                new Knot(110, 120),
                new Knot(110, 190),
                new Knot(110, $this->height),
            ],
            2 => [
                new Knot(175, 0),
                new Knot(175, 15),
                new Knot(175, 40),
                new Knot(175, 70),
                new Knot(175, 120),
                new Knot(175, 190),
                new Knot(175, $this->height),
            ],
            3 => [
                new Knot(200, 0),
                new Knot(200, 15),
                new Knot(200, 40),
                new Knot(200, 70),
                new Knot(200, 120),
                new Knot(200, 190),
                new Knot(200, $this->height),
            ],
            4 => [
                new Knot($this->width, 0),
                new Knot($this->width, 15),
                new Knot($this->width, 40),
                new Knot($this->width, 70),
                new Knot($this->width, 120),
                new Knot($this->width, 190),
                new Knot($this->width, $this->height),
            ],
        ];

        for ($i = 0; $i < 7; $i++) {
            $expectedKnots[0][$i]->setHorizontalDistance(110);
            $expectedKnots[1][$i]->setHorizontalDistance(65);
            $expectedKnots[2][$i]->setHorizontalDistance(25);
            $expectedKnots[3][$i]->setHorizontalDistance(50);
        }

        for ($i = 0; $i < 5; $i++) {
            $expectedKnots[$i][0]->setVerticalDistance(15);
            $expectedKnots[$i][1]->setVerticalDistance(25);
            $expectedKnots[$i][2]->setVerticalDistance(30);
            $expectedKnots[$i][3]->setVerticalDistance(50);
            $expectedKnots[$i][4]->setVerticalDistance(70);
            $expectedKnots[$i][5]->setVerticalDistance(210);
        }

        $expectedKnots[0][1]->setIsInBlock(true);
        $expectedKnots[0][2]->setIsInBlock(true);
        $expectedKnots[0][3]->setIsInBlock(true);

        $expectedKnots[1][1]->setIsInBlock(true);
        $expectedKnots[1][2]->setIsInBlock(true);
        $expectedKnots[1][3]->setIsInBlock(true);
        $expectedKnots[1][4]->setIsInBlock(true);

        $expectedKnots[2][1]->setIsInBlock(true);
        $expectedKnots[2][2]->setIsInBlock(true);
        $expectedKnots[2][3]->setIsInBlock(true);

        $expectedKnots[3][2]->setIsInBlock(true);

        $this->assertEquals($expectedKnots, $knots);
    }

    public function testNewBlocksInRectangleWithSomeInitialBlocks()
    {
        $blocks = [
            new Block(0, 15, 200, 105),
            new Block(200, 40, 50, 30),
            new Block(110, 120, 65, 70),
        ];
        $tile = new Tile($this->width, $this->height, $blocks);

        $expectedNewBlocks = [
            new Block(0, 0, $this->width, 15),
            new Block(0, 120, 110, 280),
            new Block(110, 190, 140, 210),
            new Block(175, 120, 75, 70),
            new Block(200, 15, 50, 25),
            new Block(200, 70, 50, 50),
        ];

        $this->assertEquals($expectedNewBlocks, $tile->getNewBlocks());
    }
}

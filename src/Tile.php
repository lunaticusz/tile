<?php

namespace Lunaticus\Tile;

/**
 * Класс холста, заполняемого блоками.
 */
class Tile
{
    // Минимальная ширина холста.
    const MIN_WIDTH = 1;

    // Минимальная высота холста.
    const MIN_HEIGHT = 1;

    /**
     * Ширина заполняемого холста.
     *
     * @var int
     */
    protected $width;

    /**
     * Высота заполняемого холста.
     *
     * @var int
     */
    protected $height;

    /**
     * Массив блоков внутри заполняемого холста.
     *
     * @var Block[]
     */
    protected $initBlocks;

    /**
     * Массив новых (сгенерированных) блоков внутри заполняемого холста.
     *
     * @var Block[]
     */
    protected $newBlocks;

    /**
     * Узлы - точки пересечения направляющих всех блоков.
     *
     * @var Knot[]
     */
    protected $knots;

    /**
     * Конструктор класса.
     *
     * @param int     $width  Ширина заполняемого холста.
     * @param int     $height Высота заполняемого холста.
     * @param Block[] $blocks Блоки для начального заполнения холста.
     *
     * @throws \Exception
     */
    public function __construct(int $width, int $height, array $blocks = [])
    {
        $this->width = $width;
        if ($this->width < static::MIN_WIDTH) {
            throw new \Exception('Ширина должна быть >= ' . static::MIN_WIDTH);
        }

        $this->height = $height;
        if ($this->height < static::MIN_HEIGHT) {
            throw new \Exception('Высота должна быть >= ' . static::MIN_HEIGHT);
        }

        $this->setInitBlocks($blocks);
        $this->setKnots();
    }

    /**
     * Вернуть новые блоки внутри заполняемого холста.
     *
     * @return null|Block[]
     */
    public function getNewBlocks()
    {
        if (null === $this->newBlocks) {
            $this->setNewBlocks();
        }
        return $this->newBlocks;
    }

    /**
     * Начальное заполнение холста.
     *
     * @param Block[] $blocks Блоки.
     *
     * @throws \Exception
     */
    protected function setInitBlocks(array $blocks)
    {
        $this->initBlocks = [];
        if ( is_array($blocks) && ! empty($blocks)) {
            foreach ($blocks as $block) {
                if ( ! ($block instanceof Block) ) {
                    throw new \Exception('Данные имеют некорректный формат. Ожидается экземпляр класса Block');
                }
                if ( ($block->getX() + $block->getWidth() <= 0) || ($block->getX() + $block->getWidth() > $this->width) ) {
                    continue;
                }
                if ( ($block->getY() + $block->getHeight() <= 0) || ($block->getY() + $block->getHeight() > $this->height) ) {
                    continue;
                }
                $this->initBlocks[] = $block;
            }
        }
        if (count($blocks) !== count($this->initBlocks)) {
            throw new \Exception('Не все блоки целиком попадают внутрь заполняемой области');
        }
    }

    /**
     * Определяет все узлы (точки пересечения направляющих блоков).
     */
    protected function setKnots()
    {
        $abscissas = [0, $this->width];
        $ordinates = [0, $this->height];

        if (! empty($this->initBlocks)) {
            foreach ($this->initBlocks as $k => $block) {
                $abscissas[] = $block->getX();
                $abscissas[] = $block->getX() + $block->getWidth();
                $ordinates[] = $block->getY();
                $ordinates[] = $block->getY() + $block->getHeight();
            }
        }

        $abscissas = array_unique($abscissas);
        $ordinates = array_unique($ordinates);
        sort($abscissas);
        sort($ordinates);

        $this->knots = [];
        foreach ($abscissas as $verticalIndex => $x) {
            foreach ($ordinates as $y) {
                $this->knots[$verticalIndex][] = new Knot($x, $y);
            }
        }

        foreach ($this->knots as $verticalIndex => $vertical) {
            foreach ($vertical as $horizontalIndex => $knot) {
                if (isset($vertical[$horizontalIndex + 1])) {
                    $knot->setVerticalDistance($vertical[$horizontalIndex + 1]->getY() - $knot->getY());
                }

                $nextVertical = isset($this->knots[$verticalIndex + 1]) ? $this->knots[$verticalIndex + 1] : null;
                if (null !== $nextVertical) {
                    $knot->setHorizontalDistance($nextVertical[$horizontalIndex]->getX() - $knot->getX());
                }
            }
        }

        if (! empty($this->initBlocks)) {
            foreach ($this->initBlocks as $block) {
                $this->markKnotsAsInBlock($block->getX(), $block->getY(), $block->getWidth(), $block->getHeight());
            }
        }
    }

    /**
     * Отмечает, что узлы находятся внутри блока или на его верхней и левой границах.
     *
     * @param int $x      Координата X верхнего левого угла блока.
     * @param int $y      Координата Y верхнего левого угла блока.
     * @param int $width  Ширина блока.
     * @param int $height Высота блока.
     */
    protected function markKnotsAsInBlock(int $x, int $y, int $width, int $height)
    {
        $x2 = $x + $width;
        $y2 = $y + $height;
        foreach ($this->knots as $vertical) {
            if ($vertical[0]->getX() < $x) {
                continue;
            }
            if ($vertical[0]->getX() >= $x2) {
                break;
            }
            foreach ($vertical as $knot) {
                if ($knot->getY() < $y) {
                    continue;
                }
                if ($knot->getY() >= $y2) {
                    break;
                }
                $knot->setIsInBlock(true);
            }
        }
    }

    /**
     * Заполнить новыми блоками пустые места на холсте.
     */
    protected function setNewBlocks()
    {
        $newBlock = $this->findNewBlock();
        while ( null !== $newBlock ) {
            $this->newBlocks[] = $newBlock;
            $newBlock = $this->findNewBlock();
        }
    }

    /**
     * Находит новый блок.
     *
     * @return null|Block
     */
    protected function findNewBlock()
    {
        $startKnotIndex = $this->getKnotIndexToStartNewBlock();

        if (null === $startKnotIndex) {
            return null;
        }

        $verticalIndex   = $startKnotIndex[0];
        $horizontalIndex = $startKnotIndex[1];

        $emptyBlockSize = $this->getEmptyBlockSize($verticalIndex, $horizontalIndex);
        $newBlockWidth  = $emptyBlockSize['w'];
        $newBlockHeight = $emptyBlockSize['h'];

        $increment = 1;
        while ($emptyBlockSize['w'] > 0) {
            $nextHorizontalIndex = $horizontalIndex + $increment;
            $increment++;
            $emptyBlockSize = $this->getEmptyBlockSize($verticalIndex, $nextHorizontalIndex, $emptyBlockSize['w']);
            $newBlockHeight += $emptyBlockSize['h'];
        }

        $startKnot = $this->knots[$verticalIndex][$horizontalIndex];
        $this->markKnotsAsInBlock($startKnot->getX(), $startKnot->getY(), $newBlockWidth, $newBlockHeight);
        return new Block($startKnot->getX(), $startKnot->getY(), $newBlockWidth, $newBlockHeight);
    }

    /**
     * Возвращает индекс узла в массиве узлов, с которого можно начать строить новый блок.
     *
     * @return null|array
     */
    protected function getKnotIndexToStartNewBlock()
    {
        foreach ($this->knots as $verticalIndex => $vertical) {
            foreach ($vertical as $horizontalIndex => $knot) {
                if (true === $knot->getIsInBlock()) {
                    continue;
                }
                if (0 === $knot->getVerticalDistance() || 0 === $knot->getHorizontalDistance()) {
                    continue;
                }
                return [$verticalIndex, $horizontalIndex];
            }
        }
        return null;
    }

    /**
     * Определяет размеры пустого блока.
     *
     * @param int $verticalIndex   Индекс вертикали.
     * @param int $horizontalIndex Индекс горизонтали.
     * @param int $maxWidth        Проверяемая ширина. При $maxWidth = 0 проверяется вся ширина холста.
     *
     * @return array
     */
    protected function getEmptyBlockSize(int $verticalIndex, int $horizontalIndex, int $maxWidth = 0): array
    {
        $horizontal = $this->getHorizontal($verticalIndex, $horizontalIndex);

        if (null === $horizontal || 0 === $horizontal[0]->getVerticalDistance()) {
            return ['w' => 0, 'h' => 0];
        }

        $newBlockWidth = 0;
        $newBlockHeight = $horizontal[0]->getVerticalDistance();

        foreach ($horizontal as $knot) {
            if ($knot->getIsInBlock()) {
                break;
            }

            if ($knot->getHorizontalDistance() > 0 && (0 === $maxWidth || $newBlockWidth < $maxWidth)) {
                $newBlockWidth += $knot->getHorizontalDistance();
            } elseif ($newBlockWidth > 0) {
                break;
            }
        }

        if ($newBlockWidth < $maxWidth) {
            return ['w' => 0, 'h' => 0];
        }

        return ['w' => $newBlockWidth, 'h' => $newBlockHeight];
    }

    /**
     * Возвращает часть горизонтали с индексом $horizontalIndex, начиная с индекса $verticalIndex.
     *
     * @param int $verticalIndex   Индекс вертикали.
     * @param int $horizontalIndex Индекс горизонтали.
     *
     * @return null|Knot[]
     */
    protected function getHorizontal(int $verticalIndex, int $horizontalIndex)
    {
        $horizontal = null;
        foreach ($this->knots as $k => $vertical) {
            if ($k < $verticalIndex) {
                continue;
            }
            $horizontal[] = $vertical[$horizontalIndex];
        }
        return $horizontal;
    }
}

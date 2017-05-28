<?php

namespace Lunaticus\Tile;

/**
 * Класс узлов (точек).
 */
class Knot
{
    /**
     * Координата X узла.
     *
     * @var int
     */
    protected $x;

    /**
     * Координата Y узла.
     *
     * @var int
     */
    protected $y;

    /**
     * Расстояние до ближайшего следующего узла по горизонтали.
     *
     * @var int
     */
    protected $horizontalDistance = 0;

    /**
     * Расстояние до ближайшего следующего узла по вертикали.
     *
     * @var int
     */
    protected $verticalDistance = 0;

    /**
     * Находится ли узел в блоке.
     *
     * @var bool
     */
    protected $isInBlock = false;

    /**
     * Конструктор класса.
     *
     * @param int $x Координата X узла.
     * @param int $y Координата Y узла.
     */
    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * Получить значение координаты X.
     *
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * Получить значение координаты Y.
     *
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * Установить значение расстояния до ближайшего следующего узла по горизонтали.
     *
     * @param int $distance Расстояние.
     */
    public function setHorizontalDistance(int $distance)
    {
        $this->horizontalDistance = $distance >= 0 ? $distance : 0;
    }

    /**
     * Получить значение расстояния до ближайшего следующего узла по горизонтали.
     *
     * @return int
     */
    public function getHorizontalDistance(): int
    {
        return $this->horizontalDistance;
    }

    /**
     * Установить значение расстояния до ближайшего следующего узла по вертикали.
     *
     * @param int $distance Расстояние.
     */
    public function setVerticalDistance(int $distance)
    {
        $this->verticalDistance = $distance >= 0 ? $distance : 0;
    }

    /**
     * Получить значение расстояния до ближайшего следующего узла по вертикали.
     *
     * @return int
     */
    public function getVerticalDistance(): int
    {
        return $this->verticalDistance;
    }

    /**
     * Установить флаг определяющий находится ли узел в блоке.
     *
     * @param bool $isInBlock
     */
    public function setIsInBlock(bool $isInBlock)
    {
        $this->isInBlock = true === $isInBlock;
    }

    /**
     * Получить значение расстояния до ближайшего следующего узла по вертикали.
     *
     * @return bool
     */
    public function getIsInBlock(): bool
    {
        return $this->isInBlock;
    }
}

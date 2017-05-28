<?php

namespace Lunaticus\Tile;

/**
 * Класс блоков (прямоугольников).
 */
class Block
{
    /**
     * Координата X левого верхнего угла блока.
     *
     * @var int
     */
    protected $x;

    /**
     * Координата Y левого верхнего угла блока.
     *
     * @var int
     */
    protected $y;

    /**
     * Ширина блока.
     *
     * @var int
     */
    protected $width;

    /**
     * Высота блока.
     *
     * @var int
     */
    protected $height;

    /**
     * Конструктор класса.
     *
     * @param int $x      Координата X левого верхнего угла блока.
     * @param int $y      Координата Y левого верхнего угла блока.
     * @param int $width  Ширина блока.
     * @param int $height Высота блока.
     *
     * @throws \Exception
     */
    public function __construct(int $x, int $y, int $width, int $height)
    {
        $this->x = $x;
        if ($this->x < 0) {
            throw new \Exception('Координата X должна быть >= 0');
        }

        $this->y = $y;
        if ($this->y < 0) {
            throw new \Exception('Координата Y должна быть >= 0');
        }

        $this->width = $width;
        if ($this->width <= 0) {
            throw new \Exception('Ширина должна быть > 0');
        }

        $this->height = $height;
        if ($this->height <= 0) {
            throw new \Exception('Высота должна быть > 0');
        }
    }

    /**
     * Метод для получения координаты X левого верхнего угла блока.
     *
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * Метод для получения координаты Y левого верхнего угла блока.
     *
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * Метод для получения ширины блока.
     *
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * Метод для получения высоты блока.
     *
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }
}

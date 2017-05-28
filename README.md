Задача
======

Дан прямоугольник (класс Tile), внутри которого могут располагаться другие прямоугольники (класс Block). Необходимо заполнить пустое пространство новыми прямоугольниками (класс Block). Заполнение необходимо производить в направлении сверху вниз, слева направо.

На рисунке ниже приведен пример.

![Пример](https://github.com/lunaticusz/tile/blob/master/example.png)

Синие прямоугольники - это прямоугольники начального заполнения, зеленые - найденные прямоугольники. Номера внутри зеленых прямоугольников - порядок заполнения.

Пример использования
--------------------

### Прямоугольник с начальным заполнением.

```php

$blocks = [
    new Block(0, 15, 200, 105),
    new Block(200, 40, 50, 30),
    new Block(110, 120, 65, 70),
];
$tile = new Tile(250, 400, $blocks);
$newBlocks = $tile->getNewBlocks();

```

Класс Tile - это класс основного прямоугольника, который необходимо заполнить.

Класс Block используется для определения прямоугольников заполнения.

### Прямоугольник без начального заполнения.

```php

$tile = new Tile(250, 400);
$newBlocks = $tile->getNewBlocks();

```
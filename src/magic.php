<?php
// Объявление простого класса
class TestClass
{
    public $foo;
    private $data = array();

    public function __construct($foo)
    {
        $this->foo = $foo;
    }

    public function __call($name, $arguments) {
        return 'Вызов несуществующего метода ' . $name . PHP_EOL;
    }

    public function __unset($name)
    {
        echo "Уничтожение '$name'";
        unset($this->data[$name]);
    }

    public function __toString()
    {
        return $this->foo;
    }

}

$class = new TestClass('Вызов toString');
echo PHP_EOL ;
unset($class->agg1);
echo PHP_EOL;
echo $class;
echo PHP_EOL;
echo $class->runTest();
echo PHP_EOL;

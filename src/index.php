<?php

function inputparameter($label)
{
    $res = readline($label);
    if (is_numeric($res)) {
        return $res;
    } else {
        echo "Введенное значение не является числом. Повторите ввод" . PHP_EOL;
        return inputparameter($label);
    }
}

function separator()
{
    return str_repeat('*', 70) . PHP_EOL;
}
echo separator();
$a = inputparameter("Первое число: ");
$b = inputparameter("Второе число: ");

$operator = readline("Введите оператор(+,-,*,/):");

switch ($operator) {
    case "+":
        $result = $a + $b;
        break;
    case "-":
        $result = $a - $b;
        break;
    case "*":
        $result = $a * $b;
        break;
    case "/":
        if ($b <> 0) {
            $result = $a / $b;
        } else {
            $result = "На 0 делить нельзя";
        }
        break;
    default:
        $result = "Операция не поддерживается";
}
echo separator();
echo "Результат:" . PHP_EOL;
echo $a . " " . $operator . " " . $b . " = " . $result . PHP_EOL;
echo separator();

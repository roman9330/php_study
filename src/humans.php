<?php


//Имеется класс "Human" и наследуемый от него "AdultHuman". "AdultHuman" имеет дополнительное свойство "job"
//Программа по вводимому значению возраста создает соответствующий объект, формирует строку с информацией об объекте и выводит ее.
//Также существует отдельный класс "Robot" в который имплеменирован интерфейс "telAbout"

//Использованные принципы ООП
//Инкапсуляция: функция "iAmWork", устанавливающая свойство "working" влияющее на формирование строки информации об объекте
//Наследование: дочерний класс "AdultHuman", имеющий дополнительное свойство "job"
//Полиморфизм: метод "iAmWork" отличный у наследуемого класса от родителя, конструктор и метод "about"
//Абстракция: имплементация интерфейса "telAbout" в класс "Human" и "Robot" и разная реализация метода "about"

interface telAbout {
    public function about();
}

class Human implements telAbout //Класс человек
{
    protected $age;     //возраст
    protected $name;    //имя
    protected $working;    //признак того, работает ли человек

    public function __construct(int $age, string $name){
        $this->age = $age;
        $this->name = $name;
        $this->working = $this->iAmWork($this->age);
    }

    protected function iAmWork(int $age): bool
    {
        if($age >= 17){
            return true;
        }else{
            return false;
        }
    }

    public function about(): string
    {
        return "Меня зовут " . $this->name . ". Мне " . $this->age . " лет";
    }

}

class AdultHuman extends Human //Класс взрослый человек
{
    public $job;    //должность

    public function __construct(int $age, string $name, string $job){
        parent::__construct($age, $name);
        $this->job = $job;
    }

    public function iAmWork(int $age): bool
    {
        if($age < 65){
            return true;
        }else{
            return false;
        }
    }

    public function about(): string
    {
        $aboutString = "Меня зовут " . $this->name . ". Мне " . $this->age . " лет.";
        if ($this->working){
            $aboutString = $aboutString . " Я работаю " . $this->job;
        }else{
            $aboutString = $aboutString . " Я пенсионер.";
        }
        return $aboutString;
    }

}

class Robot implements telAbout
{

    public function about(): string
    {
        return "Я робот";
    }
}

$isHuman = readline("Вы человек? (Да/Нет): ");

switch (mb_strtolower($isHuman)){
    case "да":
        $age = readline("Введите возраст: ");
        if (!is_numeric($age)){
            $t = new Robot();
            echo $t->about();
            break;
        }
        $name = readline("Введите имя: ");
        if ($age <= 17){
            $t = new Human($age, $name);
        }else{
            $job = readline("Введите должность: ");
            $t = new AdultHuman($age, $name, $job);
        }
        echo  PHP_EOL;
        echo $t->about();
        break;
    case "нет":
        $t = new Robot();
        echo $t->about();
        break;
    default:
        echo "Не понятно";
}


echo  PHP_EOL;
//var_dump($t);

echo  PHP_EOL;
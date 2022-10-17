<?php


class Tweet {

    protected $id;
    protected $text;
    protected $read;

    public function __construct($id, $text)
    {
        $this->id = $id;
        $this->text = $text;
        $this->read = false;
    }

    public function __sleep()
    {
        return array('id', 'text');
    }

    public function __wakeup()
    {
        $this->storage->connect();
    }

    public function __invoke($user)
    {
        $user->addTweet($this);
        return $user;
    }

}



$users = array(new User('Ev'), new User('Jack'), new User('Biz'));
$tweet = new Tweet(123, 'Hello world');
$users = array_map($tweet, $users);

var_dump($users);

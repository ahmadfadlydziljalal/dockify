<?php

namespace app\jobs;

class HelloJob extends \yii\base\BaseObject implements \yii\queue\Job {

    public string $message = 'Hello World';

    public function execute($queue): void {
        // open the terminal and find that the message is printed 10 times because the job is executed in the background
        for ($i = 0; $i < 10; $i++) {
            echo "{$this->message}\n";
        }
    }
}

<?php

require(SERVER_ROOT . 'Model/TimeEntry.php');

class WorkingTime
{
    private $timeEntries;


    public function __construct($times)
    {
        $this->timeEntries = $times;
    }

    public function getEntries(){
        return $this->timeEntries;
    }

    public function getTotalMinutes(){
        if(!is_array($this->timeEntries)){
            return 0;
        }
        $sum = 0;
        foreach ($this->timeEntries as $timeEntry) {
            $sum += $timeEntry->getWorkingMinutes();
        }
        return $sum;
    }

}

?>
<?php

/**
 * Class WorkingTime
 * Ignoring naming conventions to allow loading from database
 * Aggregation of TimeEntries
 */
class WorkingTime
{
    private $timeEntries;

    /**
     * WorkingTime constructor. Create from array of time Entries
     * @param $times array
     */
    public function __construct($times)
    {
        $this->timeEntries = $times;
    }

    public function getEntries(){
        return $this->timeEntries;
    }

    /**
     * Sum of all entries
     * @return int
     */
    public function getTotalMinutes(){
        if(!is_array($this->timeEntries)){
            throw new Error("No entries");
        }
        $sum = 0;
        foreach ($this->timeEntries as $timeEntry) {
            $sum += $timeEntry->getWorkingMinutes();
        }
        return $sum;
    }

}

?>
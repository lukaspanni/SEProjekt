<?php


use PHPUnit\Framework\TestCase;

require "Model/WorkingTime.php";

class WorkingTimeTest extends TestCase
{
    private $workingTime;
    private $entries;
    private $expectedSum;

    function __construct(string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        srand(42);
        $reflection = new ReflectionClass(TimeEntry::class);
        $workingMinutesProperty = $reflection->getProperty("WorkingMinutes");
        $workingMinutesProperty->setAccessible(true);
        $this->entries = array();
        $this->expectedSum = 0;
        for($i = 0; $i < 10; $i++) {
            $this->entries[] = new TimeEntry();
            $randval = rand(0,360);
            $this->expectedSum += $randval;
            $workingMinutesProperty->setValue($this->entries[$i], $randval);
        }
        $this->workingTime = new WorkingTime($this->entries);
    }

    /**
     * Test if workingTime returns contained time entries
     */
    public function testGetEntries()
    {
        $this->assertIsArray($this->workingTime->getEntries());
        $this->assertEquals($this->entries, $this->workingTime->getEntries());
    }

    /**
     * Test if workingTime calculates correct sum
     */
    public function testGetTotalMinutes()
    {
        $this->assertEquals($this->expectedSum,$this->workingTime->getTotalMinutes());
    }
}

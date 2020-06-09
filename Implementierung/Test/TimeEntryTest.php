<?php


use PHPUnit\Framework\TestCase;

require "Model/TimeEntry.php";

class TimeEntryTest extends TestCase
{

    private $timeEntry;

    function __construct(string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->timeEntry = new TimeEntry();
    }

    /**
     * Test if correct stop stops the time recording
     */
    public function testStop()
    {
        $this->timeEntry->start();
        $this->assertNull($this->timeEntry->getEndTime());
        $this->timeEntry->stop();
        $expected = date("Y-m-d H:i:s");
        $this->assertEquals($expected, $this->timeEntry->getEndTime());
    }

    /**
     * Test if trying to stop before start throws error
     */
    public function testStopNotStarted()
    {
        $this->expectErrorMessage("cannot stop before start");
        $this->timeEntry->stop();
    }

    /**
     * Test if trying to stop on already stopped entry throws error
     */
    public function testStopAlreadyStopped()
    {
        $reflection = new ReflectionClass($this->timeEntry);
        $StartTime = $reflection->getProperty("StartTime");
        $StartTime->setAccessible(true);
        $currentValue = date("Y-m-d H:i:s", (time() - (20*60)));
        $StartTime->setValue($this->timeEntry, $currentValue);
        $this->timeEntry->stop();
        $this->expectErrorMessage("already stopped");
        $this->timeEntry->stop();
    }

    /**
     * Test if stop updates WorkingMinute count
     */
    public function testStopUpdateWorkingMinutes(){
        $reflection = new ReflectionClass($this->timeEntry);
        $StartTime = $reflection->getProperty("StartTime");
        $StartTime->setAccessible(true);
        $expected = 10;
        $startTime = date("Y-m-d H:i:s", (time() - ($expected*60)));
        $StartTime->setValue($this->timeEntry, $startTime);
        $this->timeEntry->stop();
        $this->assertEquals($expected, $this->timeEntry->getWorkingMinutes());
    }


    /**
     * Test Edit-function
     * Test if userId, and projectId are set correctly
     */
    public function testEdit()
    {
        //Initial Values
        $projectIdInitial = 13;
        $userIdInitial = 42;
        $reflection = new ReflectionClass($this->timeEntry);
        $UserId = $reflection->getProperty("UserId");
        $ProjectId = $reflection->getProperty("ProjectId");
        $UserId->setAccessible(true);
        $ProjectId->setAccessible(true);
        $UserId->setValue($this->timeEntry, $userIdInitial);
        $ProjectId->setValue($this->timeEntry, $projectIdInitial);
        //check inital
        $this->assertEquals($userIdInitial, $this->timeEntry->getUserId());
        $this->assertEquals($projectIdInitial, $this->timeEntry->getProjectId());
        $userIdEdited = 38;
        $projectIdEdited = 128;
        $this->timeEntry->edit($userIdEdited, $projectIdEdited);
        //check edit
        $this->assertEquals($userIdEdited, $this->timeEntry->getUserId());
        $this->assertEquals($projectIdEdited, $this->timeEntry->getProjectId());
    }

    /**
     * Test if start starts time recording
     */
    public function testStart()
    {
        $this->assertNull($this->timeEntry->getStartTime());
        $this->timeEntry->start();
        $expected = date("Y-m-d H:i:s");
        $this->assertEquals($expected, $this->timeEntry->getStartTime());
    }

    /**
     * Test if start called on already started time recording throws error
     */
    public function testStartAlreadyStarted()
    {
        $reflection = new ReflectionClass($this->timeEntry);
        $StartTime = $reflection->getProperty("StartTime");
        $StartTime->setAccessible(true);
        $currentValue = date("Y-m-d H:i:s", time() - (7 * 24 * 60 * 60));
        $StartTime->setValue($this->timeEntry, $currentValue);
        $this->expectErrorMessage("already started");
        $this->timeEntry->start();
    }

}

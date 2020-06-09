<?php

/**
 * Class TimeEntry
 * Ignoring naming conventions to allow loading from database
 */
class TimeEntry implements \JsonSerializable
{
    private $UserId;
    private $ProjectId;
    private $StartTime;
    private $EndTime;
    private $WorkingMinutes;

    public function getStartTime()
    {
        return $this->StartTime;
    }

    public function getProjectId()
    {
        return $this->ProjectId;
    }

    public function getUserId()
    {
        return $this->UserId;
    }

    public function getEndTime()
    {
        return $this->EndTime;
    }

    public function getWorkingMinutes()
    {
        return $this->WorkingMinutes;
    }

    public function edit($userId, $projectId)
    {
        $this->UserId = $userId;
        $this->ProjectId = $projectId;
    }


    /**
     * Start this time recording
     * @throws Exception
     */
    public function start()
    {
        if($this->StartTime == null) {
            $this->StartTime = date("Y-m-d H:i:s");
        }else{
            throw new Error("already started");
        }
    }

    /**
     * Stop running recording and calculate working minutes
     * @throws Exception
     */
    public function stop()
    {
        if($this->StartTime == null){
            throw new Error("cannot stop before start");
        }
        if($this->EndTime == null) {
            $this->EndTime = date("Y-m-d H:i:s");
            $startDate = new DateTime($this->StartTime);
            $interval = $startDate->diff(new DateTime($this->EndTime));
            $this->WorkingMinutes = $interval->i;
        }else{
            throw new Error("already stopped");
        }
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}

?>
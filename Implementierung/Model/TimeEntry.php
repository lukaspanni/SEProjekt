<?php


class TimeEntry
{
    private $UserId;
    private $ProjectId;
    private $StartTime;
    private $EndTime;
    private $WorkingMinutes;


    public function getUserId()
    {
        return $this->UserId;
    }

    public function getProjectId()
    {
        return $this->ProjectId;
    }

    public function getStartTime()
    {
        return $this->StartTime;
    }

    public function getEndTime()
    {
        return $this->EndTime;
    }

    public function getWorkingMinutes()
    {
        return $this->WorkingMinutes;
    }

    public function setUserId($UserId)
    {
        $this->UserId = $UserId;
    }

    public function setProjectId($ProjectId)
    {
        $this->ProjectId = $ProjectId;
    }

    public function setStartTime($StartTime)
    {
        $this->StartTime = $StartTime;
    }


}

?>
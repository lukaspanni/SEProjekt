<?php


class Invitation
{

    private $UserId;
    private $ProjectId;
    private $Accepted;

    public function getUserId()
    {
        return $this->UserId;
    }

    public function getProjectId()
    {
        return $this->ProjectId;
    }

    public function setUserId($UserId)
    {
        $this->UserId = $UserId;
    }

    public function setProjectId($ProjectId)
    {
        $this->ProjectId = $ProjectId;
    }

    public function isAccepted()
    {
        return $this->Accepted;
    }

    public function accept(){
        $this->Accepted = True;
    }


}
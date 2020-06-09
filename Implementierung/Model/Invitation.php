<?php

/**
 * Class Invitation
 * Ignoring naming conventions to allow loading from database
 */
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

    public function edit($userId, $projectId){
        $this->UserId = $userId;
        $this->ProjectId = $projectId;
    }

    public function isAccepted()
    {
        return $this->Accepted;
    }

    public function accept(){
        $this->Accepted = True;
    }


}
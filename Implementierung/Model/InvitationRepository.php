<?php

/**
 * Class InvitationRepository
 * adding, selecting and updating Invitaion-Objects in database
 */
class InvitationRepository extends Repository
{

    /**
     * @param $invitation Invitation
     * @return mixed
     */
    public function add($invitation)
    {
        $sql = "INSERT INTO invited_to_work_on (UserId, ProjectId) VALUES (:User, :Project)";
        $stmt = $this->dbConnection->prepare($sql);
        return $stmt->execute(array(":User" => $invitation->getUserId(), ":Project" => $invitation->getProjectId()));
    }

    /**
     * @param $invitation Invitation
     * @return mixed
     */
    public function update($invitation)
    {
        $sql = "UPDATE invited_to_work_on SET Accepted=:Accepted WHERE UserId=:User AND ProjectId=:Project";
        $stmt = $this->dbConnection->prepare($sql);
        return $stmt->execute(array(":User" => $invitation->getUserId(), ":Project" => $invitation->getProjectId(), ":Accepted" => $invitation->isAccepted()));
    }

    /**
     * @return int
     */
    public function getCount()
    {
        $stmt = $this->dbConnection->prepare("SELECT COUNT(ProjectId) FROM invited_to_work_on");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * @param $user User
     * @return array
     */
    public function getAcceptedByUser($user)
    {
        $sql = "SELECT * FROM invited_to_work_on WHERE UserId=:Id AND Accepted IS TRUE";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute(array(":Id" => $user->getUserId()));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Invitation');
        $res = $stmt->fetchAll();
        if ($res !== false) {
            return $res;
        }
        return null;
    }

    /**
     * @param $project Project
     * @return array
     */
    public function getAcceptedByProject($project)
    {
        $sql = "SELECT * FROM invited_to_work_on WHERE ProjectId=:Id AND Accepted IS TRUE";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute(array(":Id" => $project->getProjectId()));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Invitation');
        $res = $stmt->fetchAll();
        if ($res !== false) {
            return $res;
        }
        return null;
    }

    /**
     * @param $user User
     * @return array
     */
    public function getOpenByUser($user)
    {
        $sql = "SELECT * FROM invited_to_work_on WHERE UserId=:Id AND Accepted IS NOT TRUE";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute(array(":Id" => $user->getUserId()));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Invitation');
        $res = $stmt->fetchAll();
        if ($res !== false) {
            return $res;
        }
        return null;
    }

    /**
     * @param $user User
     * @param $project Project
     * @return Invitation
     */
    public function getInvitation($user, $project)
    {
        $sql = "SELECT * FROM invited_to_work_on WHERE UserId=:User AND ProjectId=:Project";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute(array(":User" => $user->getUserId(), ":Project"=>$project->getProjectId()));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Invitation');
        $res = $stmt->fetchAll();
        if ($res !== false) {
            return $res;
        }
        return null;
    }
}
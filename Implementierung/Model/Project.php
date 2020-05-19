<?php


class Project implements \JsonSerializable
{
    private $ProjectId;
    private $ProjectName;
    private $ProjectDescription;
    private $ProjectManager;

    public function getProjectId()
    {
        return $this->ProjectId;
    }

    public function getProjectName()
    {
        return $this->ProjectName;
    }

    public function getProjectDescription()
    {
        return $this->ProjectDescription;
    }

    public function getProjectManager()
    {
        return $this->ProjectManager;
    }

    public function getTeamMembers()
    {
        $repository = new ProjectRepository();
        return $repository->getMembers($this);
    }

    public function getTeamTimeSummary(){
        
    }

    public function setProjectName($name)
    {
        $this->ProjectName = $name;
    }

    public function setProjectDescription($description)
    {
        $this->ProjectDescription = $description;
    }

    public function setProjectManager($managerId)
    {
        $this->ProjectManager = $managerId;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }


    public function saveToSession()
    {
        $_SESSION["activeProject"] = serialize($this);
    }

    public static function loadFromSession()
    {
        return unserialize($_SESSION["activeProject"]);
    }
}

?>
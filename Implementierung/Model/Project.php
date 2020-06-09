<?php

/**
 * Class Project
 * Ignoring naming conventions to allow loading from database
 */
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

    /**
     * Edit current project id
     * @param $name string
     * @param $description string
     * @param $managerId null | int
     */
    public function edit($name, $description, $managerId=null){
        $this->ProjectName = $name;
        $this->ProjectDescription = $description;
        if($managerId != null){
            $this->ProjectManager = $managerId;
        }
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
        if (isset($_SESSION["activeProject"])) {

            return unserialize($_SESSION["activeProject"]);
        }
        return null;
    }
}

?>
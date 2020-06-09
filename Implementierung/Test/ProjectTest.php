<?php


use PHPUnit\Framework\TestCase;

require "Implementierung/Model/Project.php";


class ProjectTest extends TestCase
{

    private $project;

    function __construct(string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->project = new Project();
    }

    /**
     * Test Edit-function
     * Test if projectName, projectDescription, and projectManager are set correctly
     */
    public function testEdit()
    {
        //Initial values
        $projectNameInitial = "Project X";
        $projectDescriptionInitial = "Test Description of this project.";
        $projectManagerInitial = 36;
        $reflection = new ReflectionClass($this->project);
        $projectId = $reflection->getProperty("ProjectId");
        $projectName = $reflection->getProperty("ProjectName");
        $projectDescription = $reflection->getProperty("ProjectDescription");
        $projectManager = $reflection->getProperty("ProjectManager");
        $projectName->setAccessible(true);
        $projectDescription->setAccessible(true);
        $projectManager->setAccessible(true);
        //set initial values
        $projectName->setValue($this->project, $projectNameInitial);
        $projectDescription->setValue($this->project, $projectDescriptionInitial);
        $projectManager->setValue($this->project, $projectManagerInitial);
        //check initial values
        $this->assertEquals($projectNameInitial, $this->project->getProjectName());
        $this->assertEquals($projectDescriptionInitial, $this->project->getProjectDescription());
        $this->assertEquals($projectManagerInitial, $this->project->getProjectManager());
        //Edited values
        $projectNameEdited = "New Project";
        $projectDescriptionEdited = "New Description";
        $projectManagerEdited = 42;
        //edit object
        $this->project->edit($projectNameEdited, $projectDescriptionEdited, $projectManagerEdited);
        //check edited
        $this->assertEquals($projectNameEdited, $this->project->getProjectName());
        $this->assertEquals($projectDescriptionEdited, $this->project->getProjectDescription());
        $this->assertEquals($projectManagerEdited, $this->project->getProjectManager());
    }

    /**
     * Test if loading a saved object returns the same object
     */
    public function testSaveLoad()
    {
        $this->project->saveToSession();
        $loaded = Project::loadFromSession();
        $this->assertEquals($this->project, $loaded);
    }
}

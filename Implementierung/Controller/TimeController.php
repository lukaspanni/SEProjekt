<?php

require(SERVER_ROOT . 'Model/User.php');
require(SERVER_ROOT . 'Model/Project.php');
require(SERVER_ROOT . 'Model/Invitation.php');
require(SERVER_ROOT . 'Model/TimeEntry.php');
require(SERVER_ROOT . 'Model/WorkingTime.php');
require(SERVER_ROOT . 'Model/TimesRepository.php');
require(SERVER_ROOT . 'Model/ProjectRepository.php');
require(SERVER_ROOT . 'Model/InvitationRepository.php');

/**
 * Class TimeController
 * Controller for managing Time-Recodings
 * Validating Inputs and loading concrete views
 */
class TimeController extends Controller
{

    private $user;
    private $activeEntry;
    private $timesRepository;
    private $projectRepository;

    public function __construct($requestMethod)
    {
        parent::__construct($requestMethod);
        if (User::is_authenticated()) {
            $this->user = User::loadFromSession();
            $this->projectRepository = new ProjectRepository();
            $this->timesRepository = new TimesRepository();
            $this->activeEntry = $this->timesRepository->getActiveEntry($this->user);
            if ($this->activeEntry != null) {
                $project = $this->projectRepository->getById($this->activeEntry->getProjectId());
                if ($project != null) {
                    $project->saveToSession();
                }
            }
            $this->view = new TemplateView(null, "timeMain", "default", "header", "footer", array("time"));
            $this->view->set('page', Config::$_PAGES["TIME"]);
        } else {
            $this->view = new ErrorView(new ApplicationError(ErrorType::LOGIN_ERROR, "You have to be logged in to view this site."));
        }
    }

    /**
     * default action => show user-summary of all available projects and
     */
    public function index()
    {
        if ($this->user != null) {
            $invitationRepository = new InvitationRepository();
            $openInvitations = $invitationRepository->getOpenByUser($this->user);
            $recentProjectIds = $this->timesRepository->getLastWorkingProjects($this->user, 5);
            $acceptedInvitations = $invitationRepository->getAcceptedByUser($this->user);
            $invitationProjects = array();
            $recent = array();
            $acceptedProjects = array();
            $managerProjects = array();
            foreach ($openInvitations as $invitation) {
                $invitationProjects[] = $this->projectRepository->getById($invitation->getProjectId());
            }
            foreach ($recentProjectIds as $projectId) {
                $recent[] = $this->projectSummary($projectId);
            }
            foreach ($acceptedInvitations as $invitation) {
                $acceptedProjects[] = $this->projectSummary($invitation->getProjectId());
            }
            foreach ($this->projectRepository->getByProjectManager($this->user) as $project) {
                $managerProjects[] = $this->projectSummary($project->getProjectId());
            }

            $this->view->setMain("timesOverview");
            $this->view->set('title', "Times");
            $this->view->set('projectRepository', $this->projectRepository);
            $this->view->set('openInvitation', $openInvitations);
            $this->view->set('recent', $recent);
            $this->view->set('acceptedProjects', $acceptedProjects);
            if ($this->activeEntry != null) {
                $this->view->set('activeEntry', $this->activeEntry);
            }
            $this->view->set('managerProjects', $managerProjects);
        }
        $this->view->render();
    }

    /**
     * Alias for projectTimes
     * @param $param array
     */
    public function showProject($param)
    {
        $this->projectTimes($param);
    }

    /**
     * All Time-Entries for specific project
     * @param $param array
     */
    public function projectTimes($param)
    {
        if ((is_array($param) && count($param) == 1) && $this->user != null) {
            $projectId = $param[0];
        } else if (is_numeric($param)) {
            $projectId = $param;
        } else {
            header("Location: /time");
            return;
        }
        $project = $this->projectRepository->getById($projectId);
        $workingTime = $this->timesRepository->getAllEntries($project, $this->user);
        if ($project == null) {
            $this->view = new ErrorView(new ApplicationError(ErrorType::INVALID_INPUT, "There is no data for this Input."));
        } else {
            $this->view->setMain("timeDetails");
            $this->view->set('title', "Times for Project - " . $project->getProjectName());
            $this->view->set('workingTime', $workingTime);
            if ($this->activeEntry != null && $this->activeEntry->getProjectId() == $projectId) {
                $this->view->set('currentRecording', $this->activeEntry);
            }
            $this->view->setModel($project);
        }
        $this->view->render();
    }

    /**
     * Time-Entries for project in specific interval
     * @param $param array
     */
    public function projectTimesInterval($param)
    {
        if (is_array($param) && count($param) == 3 && $this->user != null) {
            $projectId = $param[0];
            $startTime = $param["start_time"];
            $endTime = $param["end_time"];
        } else {
            header("Location: /time");
            return;
        }
        $project = $this->projectRepository->getById($projectId);
        $workingTime = $this->timesRepository->getEntriesInterval($project, $this->user, $startTime, $endTime);
        if ($project == null) {
            $this->view = new ErrorView(new ApplicationError(ErrorType::INVALID_INPUT, "There is no data for this Input."));
        } else {
            $this->view->setMain("timeDetails");
            $this->view->set('title', "Times for Project - " . $project->getProjectName());
            $this->view->set('interval', array($startTime, $endTime));
            $this->view->set('workingTime', $workingTime);
            if ($this->activeEntry != null && $this->activeEntry->getProjectId() == $projectId) {
                $this->view->set('currentRecording', $this->activeEntry);
            }
            $this->view->setModel($project);
        }
        $this->view->render();
    }

    /**
     * Start Time-Recording
     * @param $param array | int
     */
    public function start($param = null)
    {
        if (is_array($param) && count($param) == 1 && $this->user != null) {
            $projectId = $param[0];
        } else if (is_numeric($param)) {
            $projectId = $param;
        } else {
            header("Location: /time");
        }
        if($this->activeEntry){
            $this->stop();
        }
        if ($this->activeEntry && $projectId == $this->activeEntry->getProjectId()) {
            header("Location: /time/showProject/" . $projectId);
        }
        $project = $this->projectRepository->getById($projectId);
        $entry = new TimeEntry();
        $entry->edit($this->user->getUserId(), $project->getProjectId());
        $entry->start();
        if ($this->timesRepository->add($entry)) {
            $project->saveToSession();
        }
        if ($this->requestMethod == "GET") {
            header("Location: /time");
        }
    }

    /**
     * Stop running time-recording
     */
    public function stop()
    {
        if ($this->activeEntry == null) {
            header("Location: /time");
        }
        $this->activeEntry->stop();
        if ($this->timesRepository->update($this->activeEntry)) {
            $_SESSION["activeProject"] = null;
        }
        if ($this->requestMethod == "GET") {
            header("Location: /time");
        }
    }

    /**
     * Get Summary of project
     * @param $param array | int
     * @return array | void | null
     */
    public function projectSummary($param)
    {
        if ($this->user == null) {
            $this->view->render();
            return;
        }
        $json = false;
        if (is_numeric($param)) {
            $projectId = $param;
        } else if ((is_array($param) && count($param) >= 1)) {
            $projectId = $param[0];
            if (count($param) == 1) {
                $this->showProject($projectId);
                return null;
            } else {
                $json = strtoupper($param[1]) == "JSON";
            }
        } else {
            header("Location: /time");
            return null;
        }
        $project = $this->projectRepository->getById($projectId);
        $data = array("timeSum" => $this->timesRepository->getSummary($project, $this->user), "project" => $project);
        if ($json) {
            $this->view = new JSONView($data);
            $this->view->render();
        }
        return $data;
    }

    /**
     * Get current recording as json
     * @param $param array
     */
    public function currentTimeRecording($param)
    {
        if ($this->activeEntry != null && is_array($param) && count($param) > 0 && strtoupper($param[0]) == "JSON") {
            $this->view = new JSONView($this->activeEntry);
            $this->view->render();
        }
        if (strtoupper($param[0]) != "JSON") {
            header("Location: /time");
        }
    }

    /**
     * alias for showProject/<current_project_id>
     */
    public function active()
    {
        if ($this->activeEntry != null) {
            $this->showProject($this->activeEntry->getProjectId());
            return;
        }
        $this->index();
    }


}
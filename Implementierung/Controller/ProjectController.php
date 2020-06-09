<?php

require(SERVER_ROOT . 'Model/User.php');
require(SERVER_ROOT . 'Model/Project.php');
require(SERVER_ROOT . 'Model/TimeEntry.php');
require(SERVER_ROOT . 'Model/WorkingTime.php');
require(SERVER_ROOT . 'Model/ProjectRepository.php');
require(SERVER_ROOT . 'Model/TimesRepository.php');
require(SERVER_ROOT . 'Model/UserRepository.php');

/**
 * Class ProjectController
 * Controller for managing Projects, Project-Team and Shared-Projects
 * Validating Inputs and loading concrete views
 */
class ProjectController extends Controller
{

    private $projectRepository;

    public function __construct($requestMethod)
    {
        parent::__construct($requestMethod);
        if (User::is_authenticated()) {
            $this->view = new TemplateView(null, null, "default", "header", "footer", array("project", "pagination","chartjs/Chart.bundle.min", "utility"));
            $this->projectRepository = new ProjectRepository();
            $this->view->set('page', Config::$_PAGES["PROJECT"]);
        } else {
            $this->view = new ErrorView(new ApplicationError(ErrorType::LOGIN_ERROR, "You have to be logged in to view this site."));
        }
    }

    public function index()
    {
        $this->all();
    }

    /**
     * Show Project with specific id
     * @param $param array
     */
    public function id($param)
    {
        if (is_array($param) && count($param) >= 1) {
            $id = $param[0];
        } else if (is_numeric($param)) {
            $id = $param;
        } else {
            header("Location: /project");
            return;
        }
        if ($this->projectRepository != null) {
            $project = $this->projectRepository->getById($id);
            $this->view->setMain('projectDetails');
            $this->view->setModel($project);
            $this->view->set('title', 'Project-' . $id);
            $this->view->set('projectRepository', $this->projectRepository);
            $this->view->set('timesRepository', new TimesRepository());
        }
        $this->view->render();

    }

    /**
     * Show all Projects
     * @param null $param array
     */
    public function all($param = null)
    {
        $page = 1;
        $interval = 10;
        if ($param != null) {
            if (is_array($param)) {
                $paramcount = count($param);
                if ($paramcount > 0) {
                    $page = $param[0];
                }
                if ($paramcount > 1) {
                    $interval = $param[1];
                }
            }
        }
        if ($this->projectRepository != null) {
            $projects = $this->projectRepository->getMultiple(($page - 1) * $interval, $interval);
            $this->view->setModel($projects);

            $this->view->setMain("projectList");
            $this->view->set('title', 'Project');
            $this->view->set('pagination_page', $page);
            $this->view->set('pagination_interval', $interval);
            $this->view->set('count', $this->projectRepository->getCount());
        }
        $this->view->render();
    }

    /**
     * create new project
     */
    public function add()
    {
        if ($this->requestMethod == "GET") {
            $this->view->set('showAddForm', 'true');
            $this->index();
        } else if ($this->requestMethod == "POST") {
            if (isset($_POST)) {
                $data = $this->escape_input_array($_POST);
                $project = new Project();
                $project->edit($data["project_name"], $data["project_description"], User::loadFromSession()->getUserId());
                $id = $this->projectRepository->add($project);
                if ($id != null) {
                    $this->id($id);
                }
            }
        }
    }

    /**
     * Edit a project where the current user is project manager
     */
    public function edit()
    {
        if ($this->projectRepository == null || $this->requestMethod == "GET" || !isset($_POST)) {
            header("Location: /project/");
            return;
        }
        $inputData = $this->escape_input_array($_POST);

        $project = $this->projectRepository->getById($inputData["id"]);
        if (!$this->checkProjectManager($project)) {
            http_response_code(400);
            return;
        }
        if ($inputData["name-input"] != null && $inputData["description-input"] != null) {
            $project->edit($inputData["name-input"], $inputData["description-input"]);
            $res = $this->projectRepository->update($project);
            if ($res == true) {
                $this->view = new JSONView($project);
                $this->view->render();
                return;
            }
        }
        http_response_code(400);
    }

    /**
     * Validate input for Invitation-Creation and create invitation
     */
    public function inviteMember()
    {
        if ($this->projectRepository == null || $this->requestMethod == "GET" || !isset($_POST)) {
            header("Location: /project/");
            return;
        }
        $inputData = $this->escape_input_array($_POST);

        $project = $this->projectRepository->getById($inputData["id"]);
        if (!$this->checkProjectManager($project)) {
            http_response_code(403);
            return;
        }
        if ($inputData["user_email"] == null) {
            http_response_code(400);
            return;
        }
        $userRepository = new UserRepository();
        $user = $userRepository->getByEmail($inputData["user_email"]);
        if ($user == null) {
            http_response_code(400);
            return;
        }
        $invitation = new Invitation();
        $invitation->edit($user->getUserId(), $project->getProjectId());
        $invitationRepository = new InvitationRepository();
        if ($invitationRepository->add($invitation) != true) {
            http_response_code(400);
            return;
        }
    }

    /**
     * Validate input for handling inviations (accept/decline) and perform selected action
     * @param $param array
     */
    public function invitation($param)
    {
        if ($this->projectRepository == null || !is_array($param) || count($param) != 2 || !is_numeric($param[0])) {
            header("Location: /project");
            return;
        }
        $input = $this->escape_input_array($param);
        $accept = false;
        if ($input[1] == "accept") {
            $accept = true;
        }
        $loggedInUser = unserialize($_SESSION['user']);

        $project = $this->projectRepository->getById($input[0]);
        $invitationRepository = new InvitationRepository();
        $invitation = $invitationRepository->getInvitation($loggedInUser, $project);
        if ($accept) {
            $invitation->accept();
        }
        $invitationRepository->update($invitation);

    }

    /**
     * Validate input for Creation of a shared project and share project
     */
    public function share()
    {
        if ($this->projectRepository == null || !isset($_POST)) {
            http_response_code(400);
            return;
        }
        $inputData = $this->escape_input_array($_POST);
        $projectId = $inputData["project_id"];
        $expiration = $inputData["expires_date"] . " " . $inputData["expires_time"];
        $project = $this->projectRepository->getById($projectId);
        if ($this->checkProjectManager($project)) {
            $token = md5(uniqid($projectId, true));
            if ($this->projectRepository->share($project, $expiration, $token)) {
                $this->view = new JSONView($token);
                $this->view->render();
                return;
            }
        }
        $this->view = new ErrorView(new ApplicationError(ErrorType::OTHER_ERROR, "", 400));
        $this->view->render();
    }

    /**
     * Show shared Project if allowed
     * @param $param array
     */
    public function showShared($param)
    {
        if (is_array($param) && count($param) >= 1) {
            $token = $param[0];
            $project = $this->projectRepository->getByToken($token);
            if ($project == null) {
                return_404_error();
                return;
            }
            $this->view = new TemplateView($project, "projectDetails", "default", "header", "footer", array("project", "pagination"));
            $this->view->set('page', Config::$_PAGES["PROJECT"]);
            $this->view->set('title', 'Project-' . $project->getProjectId());
            $this->view->set('shared', true);
        }
        $this->view->render();
    }

    /**
     * check if current user is ProjectManager of project
     * @param $project Project
     * @return bool
     */
    private function checkProjectManager($project)
    {
        if ($project != null) {
            $loggedInUser = User::loadFromSession();
            return ($project != null && $loggedInUser != null && $project->getProjectManager() == $loggedInUser->getUserId());
        }
        return false;
    }

}
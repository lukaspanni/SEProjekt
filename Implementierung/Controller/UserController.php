<?php

require(SERVER_ROOT . 'Model/User.php');
require(SERVER_ROOT . 'Model/UserRepository.php');

/**
 * Class UserController
 * Controller for managing Users
 * Validating Inputs and loading concrete views
 */
class UserController extends Controller
{
    private $userRepository;

    public function __construct($requestMethod)
    {
        parent::__construct($requestMethod);
        if (User::is_authenticated()) {
            $this->userRepository = new UserRepository();
            $this->view = new TemplateView(null, null, "default", "header", "footer", array("pagination", "user", "utility"));
            $this->view->set('page', Config::$_PAGES["USER"]);
        } else {
            $this->view = new ErrorView(new ApplicationError(ErrorType::LOGIN_ERROR, "You have to be logged in to view this site."));
        }
    }

    public function index()
    {
        $this->all();
    }

    /**
     * Get specific user
     * @param $param array
     */
    public function id($param)
    {
        if (is_array($param) && count($param) >= 1) {
            $id = $param[0];
        } else if (is_numeric($param)) {
            $id = $param;
        }
        if ($this->userRepository != null) {
            $user = $this->userRepository->getById($id);
            $this->view->setModel($user);
            $this->view->setMain('userDetails');
            $this->view->set('title', 'User-' . $id);
            $this->view->set('id', $id);
        }
        $this->view->render();

    }

    /**
     * Get all users
     * @param null $param
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
        if ($this->userRepository != null) {
            $users = $this->userRepository->getMultiple(($page - 1) * $interval, $interval);
            $this->view->setModel($users);

            $this->view->setMain('userList');
            $this->view->set('title', 'User');
            $this->view->set('count', $this->userRepository->getCount());
            $this->view->set('pagination_page', $page);
            $this->view->set('pagination_interval', $interval);
        }
        $this->view->render();
    }

    /**
     * Alias for id/<current_user_id>
     * Get Current user
     * @param $param
     */
    public function current($param)
    {
        if ($this->userRepository != null) {
            if (is_array($param) && count($param) > 0 && strtoupper($param[0]) == "JSON") {
                $this->view = new JSONView(User::loadFromSession());
                $this->view->render();
                return;
            }
            $this->id(User::loadFromSession()->getUserId());
        }
    }

    /**
     * Edit current user
     */
    public function edit()
    {
        if ($this->userRepository == null) {
            $this->view->render();
            return;
        }
        if ($this->requestMethod == "GET") {
            header("Location: /user/id/" . User::loadFromSession()->getId());
            return;
        }
        if (isset($_POST)) {
            $inputData = $this->escape_input_array($_POST);
            $name = array_filter(explode(" ", $inputData['name-input']));
            if (filter_var($inputData['email-input'], FILTER_VALIDATE_EMAIL) && count($name) == 2) {
                $firstname = implode(" ", array_slice($name,0,-1));
                $lastname = end($name);
                $user = User::loadFromSession();
                $user->edit($firstname, $lastname, $inputData['email-input'], $inputData['breakReminder-input']);
                if ($this->userRepository->update($user)) {
                    $user->saveToSession();
                    http_response_code(200);
                    return;
                }
            }
        }
        http_response_code(400);
    }

    /**
     * Search user with search-string
     */
    public function search()
    {
        if (isset($_POST)) {
            $searchString = $this->escape_input($_POST['searchString']);

            $result = $this->userRepository->find($searchString);
            $this->view->setModel($result);
            $this->view->setMain("userList");
            $this->view->set('title', 'User-Search');
            $this->view->set('searchResult', true);
            $this->view->render();
        }
    }


}
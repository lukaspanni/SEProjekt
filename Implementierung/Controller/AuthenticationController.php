<?php

require(SERVER_ROOT . 'Model/User.php');
require(SERVER_ROOT . 'Model/UserRepository.php');


class AuthenticationController extends Controller
{
    private $userRepository;

    public function __construct($requestMethod)
    {
        parent::__construct($requestMethod);
        if (User::is_authenticated()) {
            header("Location: /");
        } else {
            $this->view = new TemplateView(null, "login", "default", "header", "footer", array("login_register"), array("login"));
            $this->userRepository = new UserRepository();
        }
    }

    public function index()
    {
        $this->view->set('page', Config::getInstance()->getPages()["LOGIN"]);
        $this->view->set('title', "Login/Registrieren");
        $this->view->render();
    }

    public function register()
    {
        if ($this->requestMethod == "POST") {
            $data = $this->escape_input_array($_POST);
            if (isset($data["user_email"]) && isset($data["user_firstname"]) && isset($data["user_lastname"])
                && isset($data["user_password"]) && isset($data["user_password_retype"])) {
                if (filter_var($data["user_email"], FILTER_VALIDATE_EMAIL) && strlen($data["user_password"]) != 0) {
                    if ($data["user_password"] == $data["user_password_retype"]) {
                        $user = new User();
                        $user->setEmailAddress($data["user_email"]);
                        $user->setFirstname($data["user_firstname"]);
                        $user->setLastname($data["user_lastname"]);
                        if ($user->register($data["user_password"], $this->userRepository)) {
                            $_SESSION["login"] = true;
                            $user->saveToSession();
                            header("Location: /");
                        }
                    }
                }
                $this->view->set('registerError', true);
                $this->view->set('email', $data["user_email"]);
                $this->view->set('firstname', $data["user_firstname"]);
                $this->view->set('lastname', $data["user_lastname"]);
                $this->view->set('password', $data["user_password"]);
            }
        }
        $this->view->set('register', true);
        $this->index();
    }

    public function login()
    {
        //30 seconds timeout
        if(isset($_SESSION['login_locked_unil']) && time() < $_SESSION['login_locked_unil']){
            $this->view->set('loginError', "true");
            $this->view->set('register', false);
            $this->index();
            return;
        }
        if ($this->requestMethod == "POST") {
            $data = $this->escape_input_array($_POST);
            if (isset($data["user_email"]) && isset($data["user_password"])) {
                $user = $this->userRepository->getByEmail($data["user_email"]);
                if ($user != null && $user->login($data["user_password"])) {
                    $_SESSION["login"] = true;
                    $user->saveToSession();
                    header("Location: /");
                    return;
                } else {
                    if(!isset($_SESSION['login_tries'])){
                        $_SESSION['login_tries'] = 1;
                    }else{
                        $_SESSION['login_tries'] += 1;
                    }
                    if($_SESSION['login_tries'] >= 3){
                        $_SESSION['login_locked_unil'] = time() + 30;
                    }
                    $this->view->set('loginError', true);
                    $this->view->set('email', $data["user_email"]);
                    $this->view->set('password', $data["user_password"]);
                }
            }
        }
        $this->view->set('register', false);
        $this->index();
    }

    public function logout()
    {
        //https://www.php.net/session_destroy
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy();
        header("Location: /");
    }

}
<?php

/**
 * Class User
 * Ignoring naming conventions to allow loading from database
 */
class User implements \JsonSerializable
{
    private $UserId;
    private $Firstname;
    private $Lastname;
    private $EmailAddress;
    private $PasswordHash;
    private $BreakReminder;

    public function getUserId()
    {
        return $this->UserId;
    }

    public function getFirstname()
    {
        return $this->Firstname;
    }

    public function getLastname()
    {
        return $this->Lastname;
    }

    public function getEmailAddress()
    {
        return $this->EmailAddress;
    }

    public function getPasswordHash()
    {
        if (isset($this->PasswordHash)) {
            return $this->PasswordHash;
        }
        return null;
    }

    public function getBreakReminder()
    {
        return $this->BreakReminder;
    }

    /**
     * Edit current user object
     * @param $firstname string
     * @param $lastname string
     * @param $email string
     * @param $breakReminder null | int
     */
    public function edit($firstname, $lastname, $email, $breakReminder = null)
    {
        $this->Firstname = $firstname;
        $this->Lastname = $lastname;
        $this->EmailAddress = $email;
        if ($breakReminder != null && $breakReminder >= 1) {
            $this->BreakReminder = $breakReminder;
        }
    }

    /**
     * Hash Password and save current user-Object in database
     * @param $password string
     * @param $repository UserRepository
     * @return mixed
     */
    public function register($password, $repository)
    {
        $this->PasswordHash = password_hash($password, PASSWORD_DEFAULT);
        $result = $repository->add($this);
        if ($result) {
            //Set UserId From DB
            $this->UserId = $repository->getByEmail($this->EmailAddress)->getUserId();
        }
        return $result;
    }

    /**
     * Verify password to match saved password hash
     * If correct, unset password hash from user object
     * @param $password string
     * @return bool
     */
    public function verifyPassword($password)
    {
        if (isset($this->PasswordHash) && password_verify($password, $this->PasswordHash)) {
            unset($this->PasswordHash);
            return true;
        }
        return false;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function saveToSession()
    {
        $_SESSION["login"] = true;
        $_SESSION["user"] = serialize($this);
    }

    public static function loadFromSession()
    {
        return unserialize($_SESSION["user"]);
    }

    public static function is_authenticated()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (!isset($_SESSION['login'])) {
            $_SESSION['login'] = false;
        }
        return $_SESSION['login'];
    }
}

?>
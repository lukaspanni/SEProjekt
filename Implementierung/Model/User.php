<?php


class User implements \JsonSerializable
{
    private $UserId;
    private $Firstname;
    private $Lastname;
    private $EmailAddress;
    private $PasswordHash;

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

    public function getFullName()
    {
        return $this->Firstname . " " . $this->Lastname;
    }

    public function getEmailAddress()
    {
        return $this->EmailAddress;
    }

    public function getPasswordHash()
    {
        if(isset($this->PasswordHash)) {
            return $this->PasswordHash;
        }
        return null;
    }

    public function setFirstname($firstname)
    {
        $this->Firstname = $firstname;
    }

    public function setLastname($lastname)
    {
        $this->Lastname = $lastname;
    }

    public function setEmailAddress($email)
    {
        $this->EmailAddress = $email;
    }

    public function register($password, $repository)
    {
        $this->PasswordHash = password_hash($password, PASSWORD_DEFAULT);
        $result = $repository->add($this);
        if(!$result){
            //Set UserId From DB
            $this->UserId = $repository->getByEmail($this->EmailAddress)->getUserId();
        }
        return $result;
    }

    public function login($password)
    {
        if(password_verify($password, $this->PasswordHash)){
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
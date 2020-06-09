<?php

/**
 * Class UserRepository
 * adding, selecting and updating User-Objects from database
 */
class UserRepository extends Repository
{
    /**
     * @param $user User
     * @return mixed
     */
    public function add($user)
    {
        $sql = "INSERT INTO user (Firstname, Lastname, EmailAddress, PasswordHash) VALUES (:firstname,:lastname,:email,:password)";
        $stmt = $this->dbConnection->prepare($sql);
        var_dump($user);
        return $stmt->execute(array(":firstname" => $user->getFirstname(), ":lastname" => $user->getLastname(), ":email" => $user->getEmailAddress(), ":password" => $user->getPasswordHash()));
    }

    /**
     * @param $user User
     * @return mixed
     */
    public function update($user)
    {
        $sql = "UPDATE user SET Firstname=:Firstname, Lastname=:Lastname, EmailAddress=:Mail, BreakReminder=:BreakReminder WHERE UserId=:User";
        $stmt = $this->dbConnection->prepare($sql);
        return $stmt->execute(array(":Firstname" => $user->getFirstname(), ":Lastname" => $user->getLastname(), ":Mail" => $user->getEmailAddress(), ":User" => $user->getUserId(), ":BreakReminder"=>$user->getBreakReminder()));
    }

    /**
     * @return int
     */
    public function getCount()
    {
        $stmt = $this->dbConnection->prepare("SELECT COUNT(UserId) FROM user");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * @param $id int
     * @return User
     */
    public function getById($id)
    {
        $sql = "SELECT UserId, Firstname, Lastname, EmailAddress, BreakReminder FROM user WHERE UserId = :Id";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute(array(":Id" => $id));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $res = $stmt->fetch();
        if ($res !== false) {
            return $res;
        }
        return null;
    }

    /**
     * @param $start int: startindex
     * @param $count int: total number of elements to retrieve
     * @return array
     */
    public function getMultiple($start, $count)
    {
        $sql = "SELECT UserId, Firstname, Lastname, EmailAddress FROM user LIMIT :Start, :Rows";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->bindParam(":Start", $start, PDO::PARAM_INT);
        $stmt->bindParam(":Rows", $count, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $res = $stmt->fetchAll();
        if ($res !== false) {
            return $res;
        }
        return null;
    }

    /**
     * @param $email string
     * @return User
     */
    public function getByEmail($email)
    {
        $sql = "SELECT * FROM user WHERE EmailAddress = :Mail";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute(array(":Mail" => $email));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $res = $stmt->fetch();
        if ($res !== false) {
            return $res;
        }
        return null;
    }

    /**
     * @param $searchString string
     * @return array
     */
    public function find($searchString)
    {
        $sql = "SELECT UserId, Firstname, Lastname, EmailAddress FROM user WHERE EmailAddress LIKE :Search OR Firstname LIKE :Search OR Lastname LIKE :Search";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute(array(":Search" => "%" . $searchString . "%"));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $res = $stmt->fetchAll();
        if ($res !== false) {
            return $res;
        }
        return null;
    }
    
}


?>
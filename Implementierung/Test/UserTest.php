<?php


use PHPUnit\Framework\TestCase;

require "Implementierung/Model/User.php";
require "Implementierung/Model/Repository.php";
require "Implementierung/Model/UserRepository.php";

class UserTest extends TestCase
{

    private $user;

    function __construct(string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->user = new User();
    }

    /**
     * Test if registration uses UserRepository correctly to add new user
     */
    public function testRegister()
    {
        //test data
        $firstname = "John";
        $lastname = "Doe";
        $password = "passw0rdTest!$";
        $mail = "test@mail.com";
        $id = 42;
        // user for return from mockrepo
        $idUser = new User();
        $reflection = new ReflectionClass($idUser);
        $property = $reflection->getProperty("UserId");
        $property->setAccessible(true);
        $property->setValue($idUser, $id);
        // create mocked userRepository
        $mockedRepo = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $mockedRepo->expects($this->once())->method('add')->with($this->user)->willReturn(true);
        $mockedRepo->expects($this->once())->method('getByEmail')->with($mail)->willReturn($idUser);
        //check function
        $this->user->edit($firstname, $lastname, $mail);
        $this->user->register($password, $mockedRepo);
        $this->assertTrue($this->user->verifyPassword($password));
        $this->assertEquals($id, $this->user->getUserId());
    }

    /**
     * Test if loading a saved object returns the same object
     */
    public function testSaveLoadSession()
    {
        $this->user->saveToSession();
        $loadedUser = User::loadFromSession();
        $this->assertEquals($this->user, $loadedUser);
    }

    /**
     * Test if is_authenticated returns true after user is saved to session and false otherwise
     */
    public function testIs_authenticated()
    {
        $_SESSION = array();
        $this->assertFalse($this->user->is_authenticated());
        $this->user->saveToSession();
        $this->assertTrue($this->user->is_authenticated());
    }

    /**
     * Test Edit-function
     * Test if firstname, lastname, email and breakReminder are set correctly
     */
    public function testEdit()
    {
        //Initial values
        $firstnameInitial = "Test";
        $lastnameInitial = "User";
        $emailInitial = "test@user.com";
        $breakReminderInitial = 120;
        $reflection = new ReflectionClass($this->user);
        $firstname = $reflection->getProperty("Firstname");
        $lastname = $reflection->getProperty("Lastname");
        $email = $reflection->getProperty("EmailAddress");
        $breakReminder = $reflection->getProperty("BreakReminder");
        $firstname->setAccessible(true);
        $lastname->setAccessible(true);
        $email->setAccessible(true);
        $breakReminder->setAccessible(true);
        //set initial values
        $firstname->setValue($this->user, $firstnameInitial);
        $lastname->setValue($this->user, $lastnameInitial);
        $email->setValue($this->user, $emailInitial);
        $breakReminder->setValue($this->user, $breakReminderInitial);
        //check initial values
        $this->assertEquals($firstnameInitial, $this->user->getFirstname());
        $this->assertEquals($lastnameInitial, $this->user->getLastname());
        $this->assertEquals($emailInitial, $this->user->getEmailAddress());
        $this->assertEquals($breakReminderInitial, $this->user->getBreakReminder());
        //Edited values
        $firstnameEdited = "John";
        $lastnameEdited = "Doe";
        $emailEdited = "mail@test.de";
        $breakReminderEdited = 90;
        //edit object
        $this->user->edit($firstnameEdited, $lastnameEdited, $emailEdited, $breakReminderEdited);
        //check edited
        $this->assertEquals($firstnameEdited, $this->user->getFirstname());
        $this->assertEquals($lastnameEdited, $this->user->getLastname());
        $this->assertEquals($emailEdited, $this->user->getEmailAddress());
        $this->assertEquals($breakReminderEdited, $this->user->getBreakReminder());

    }

    /**
     * Test if verifyPassword verifies the password hash and unsets passwordhash after success
     */
    public function testVerifyPassword()
    {
        $password = "testP4ssw0rd";
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $reflection = new ReflectionClass($this->user);
        $passwordHash = $reflection->getProperty("PasswordHash");
        $passwordHash->setAccessible(true);
        $passwordHash->setValue($this->user, $hash);

        $this->assertFalse($this->user->verifyPassword("testP4ssword"));
        $this->assertFalse($this->user->verifyPassword("Password"));
        $this->assertTrue($this->user->verifyPassword($password));
        $this->assertFalse($this->user->verifyPassword($password));
    }
}

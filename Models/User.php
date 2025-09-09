<?php

class User{

    private ?int $id;
    private ?string $userName;
    private string $email;
    private string $password;
    private ?\DateTime $created_at;
    private ?\DateTime $updated_at;

    public function __construct(string $email, string $password, ?string $userName = null, ?\DateTime $created_at = null, ?\datetime $updated_at = null, ?int $id = null )
    {
        $this->userName = $userName;
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at) {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTime $updated_at) {
        $this->updated_at = $updated_at;
    }
    
    public function addNewUser(){
        try{
            $connexion = connexion();
            $request = $connexion->prepare("INSERT INTO user (username,email, password, created_at, updated_at) VALUES(:username, :email, :password, :created_at, :updated_at);");

            $userName = $this->getUserName();
            $userEmail = $this->getEmail();
            $userPassword = password_hash($this->getPassword(),PASSWORD_ARGON2I);
            $created_at = new \DateTime('now');
            $updated_at = new \DateTime('now');
            $createdAtFormated = $created_at->format('Y-m-d H:i:s');
            $updatedAtFormated = $updated_at->format('Y-m-d H:i:s');

            $request->bindParam(":username",$userName);
            $request->bindParam(":email",$userEmail);
            $request->bindParam(":password",$userPassword);
            $request->bindParam(":created_at",$createdAtFormated);
            $request->bindParam(":updated_at",$updatedAtFormated);

            $request->execute();

            $getIdInserted = $connexion->lastInsertId();
            $this->setId($getIdInserted);
            $this->setCreatedAt($created_at);
            $this->setUpdatedAt($updated_at);

            return $this;

        }catch(PDOException $e){
            die('User Add Error : '.$e);
        }
    }

    public function verificationAuth(){
        try{
            $connexion = connexion();
            $request = $connexion->prepare("SELECT * FROM user WHERE email=:email;");

            $userEmail = $this->getEmail();
            $userPassword = $this->getPassword();

            $request->bindParam(":email",$userEmail);

            $request->execute();

            $userInfo = $request->fetch(PDO::FETCH_ASSOC);

            if(!empty($userInfo)){
                if(password_verify($userPassword, $userInfo['password'])){
                    $this->setId($userInfo['id']);
                    $this->setUserName($userInfo['username']);
                    $created_at = new DateTime($userInfo['created_at']);
                    $updated_at = new DateTime($userInfo['updated_at']);
                    $this->setCreatedAt($created_at);
                    $this->setUpdatedAt($updated_at);
                    $this->setPassword($userInfo['password']);
                    return $this;
                }else{
                    return false;
                }
            }else{
                return false;
            }

        }catch(PDOException $e){
            die('User Verify Access Error : '.$e);
        }
    }
}
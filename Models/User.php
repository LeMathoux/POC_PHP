<?php

class User{

    private ?int $id;
    private ?string $userName;
    private string $email;
    private string $password;
    private ?\DateTime $created_at;
    private ?\DateTime $updated_at;

    /**
     * Constructeur de la classe User.
     * 
     * @param string $email
     * @param string $password
     * @param string $userName
     * @param \Datetime $created_at
     * @param \Datetime|null $updated_at
     * @param int|null $id
     * 
     */
    public function __construct(string $email, string $password, ?string $userName = null, ?\DateTime $created_at = null, ?\DateTime $updated_at = null, ?int $id = null )
    {
        $this->userName = $userName;
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * récupére le nom du compte
     * 
     * @return string
     */
    public function getUserName() {
        return $this->userName;
    }

    /**
     * Définit le nom du compte
     * 
     * @param string $userName
     *
     */
    public function setUserName(string $userName) {
        $this->userName = $userName;
    }

    /**
     * récupére l'identifiant du compte
     * 
     * @return int 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Définit l'id du compte
     * 
     * @param int $id
     *
     */
    public function setId(int $id) {
        $this->id = $id;
    }

    /**
     * récupére l'email du compte
     * 
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Définit l'email du compte
     * 
     * @param string $email
     *
     */
    public function setEmail(string $email) {
        $this->email = $email;
    }

    /**
     * récupére le mot de passe hashé du compte
     * 
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Définit le mot de passe du compte
     * 
     * @param string $password
     *
     */    
    public function setPassword(string $password) {
        $this->password = $password;
    }

    /**
     * récupére la date de création du compte
     * 
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->created_at;
    }

    /**
     * Définit la date de création du compte
     * 
     * @param \DateTime $created_at
     *
     */    
    public function setCreatedAt(\DateTime $created_at) {
        $this->created_at = $created_at;
    }

    /**
     * récupére la date de mise à jour du compte
     * 
     * @return \DateTime 
     */
    public function getUpdatedAt() {
        return $this->updated_at;
    }

    /**
     * Définit la date de mise à jour du compte
     * 
     * @param \DateTime $updated_at
     *
     */   
    public function setUpdatedAt(\DateTime $updated_at) {
        $this->updated_at = $updated_at;
    }
    
    /**
     * Ajoute un nouvel utilisateur dans la base de donnée.
     * 
     * @return User $this Objet User 
     */
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

    /**
     * Verifie si l'utilisateur existe et que ses informations sont corrects.
     * 
     * @return User si l'utilisateur est autorisé.
     * @return boolean false si l'utilisateur est refusé.
     */
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
<?php
require_once __DIR__ . '/db.php';

class User
{
    public $id;
    public $email;
    public $password;
    public $role;
	const DB = 'users';
	

    public function __construct($id=null){
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function create(){
        global $db;
        $sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':password', $this->hashedPass());
        $stmt->bindValue(':role', $this->role ? $this->role : 0);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }


    /**
     * @param $id integer User Id
     * @return null|User
     */
    public static function find($id){
        global $db;
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        if($stmt->execute()){
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $user = new self($id);
            $user->email = $result['email'];
            $user->password = $result['password'];
            $user->role = $result['role'];
            return $user;
        }
        return null;
    }

    /**
     * @param $id integer User Id
     * @return null|User
     */
    public static function findByEmail($email){
        global $db;
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email);
        if($stmt->execute()){
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $user = new self($result['id']);
            $user->email = $result['email'];
            $user->password = $result['password'];
            $user->role = $result['role'];
            return $user;
        }
        return null;
    }

    /**
     * @return string returns hashed password
     */
    private function hashedPass(){
        return password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function unhashPass($pass){
        return password_verify($pass, $this->password);
    }

    public function makeAdmin(){
		global $db;
		
        $sql = 'UPDATE '.self::DB. ' SET role=1 WHERE email=:email LIMIT 1';
        $stmt = $db->prepare($sql);
		return $stmt->execute([':email' => $this->email]);
   
	}

}

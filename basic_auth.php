<?php

class Auth
{
    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function login($username, $password) {
        $query = "SELECT * FROM customers WHERE username = :username AND password = :password";
        $statement = $this->db->prepare($query);
        $statement->execute([
            'username' => $username,
            'password' => $password
        ]);
        
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $_SESSION['auth']['user'] = $user['id'];
            return true;
        }
        
        return false;
    }

    public function register($username, $password) {
        $checkQuery = "SELECT COUNT(*) as count FROM customers WHERE username = :username";
        $checkStatement = $this->db->prepare($checkQuery);
        $checkStatement->execute(['username' => $username]);
        $result = $checkStatement->fetch(PDO::FETCH_ASSOC);
        $count = $result['count'];
    
        if ($count > 0) {
            return false;
            exit;
        }
    
        $insertQuery = "INSERT INTO customers (username, password) VALUES (:username, :password)";
        $insertStatement = $this->db->prepare($insertQuery);
        $insertStatement->execute([
            'username' => $username,
            'password' => $password
        ]);

        $lastId = $this->db->lastInsertId();
        $_SESSION['auth']['user'] = $lastId;
    
        return true;
    }
    

    public function check(){
        return isset($_SESSION['auth']['user']);
    }
    
    

}


?>

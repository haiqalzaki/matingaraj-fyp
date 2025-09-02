<?php

namespace App\Models;

class SessionHandler
{
    private $id;
    private $nameEmail;
    private $password;

    public function __construct($nameEmail, $password) 
    {
        $this->nameEmail = $nameEmail;
        $this->password = $password;
    }

    public function login() 
    {
        if (!session_id()) {
            session_start();
        }

        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);  

        $sql = "SELECT * FROM garaj_user WHERE (u_name = ? OR u_email = ?) AND isDeleted = 0";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for user insert: {$db->error}");
        }

        $stmt->bind_param("ss", $this->nameEmail, $this->nameEmail);
        if (!$stmt->execute()) {
            throw new \Exception("Error executing insert statement! Log: {$db->error}");
        }

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return false;
        } else {
            $row = $result->fetch_assoc();
            if ((($row['u_name'] === $this->nameEmail) || ($row['u_email'] === $this->nameEmail))){
                if (!password_verify($this->password, $row['u_password'])) {
                    return false;
                }
            
                if ($row['u_isAdmin'] === 1) {
                    $_SESSION['id'] = $row['u_id'];
                    $_SESSION['username'] = $row['u_name'];
                    $_SESSION['isAdmin'] = true;
                    return true;
                } else {
                    $_SESSION['id'] = $row['u_id'];
                    $_SESSION['username'] = $row['u_name'];
                    $_SESSION['isAdmin'] = false;
                    return true;
                }
            } else {
                return false;
            }
        }   
    }

    public static function logout() {
        setcookie(session_name(), '', time() - 3600, '/');
        session_unset();
        session_destroy();
        $_SESSION = [];
    }
}

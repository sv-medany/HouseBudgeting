<?php
require_once('config.php');


class User
{
    private $id, $email, $username, $password, $alert;

    public function __construct($username, $email, $password)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->alert = 100;
    }

    // setters and getters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setAlert($alert)
    {
        $this->alert = $alert;
    }
    public function getAlert()
    {
        return $this->alert;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    // build a user - to increase abstraction level
    public static function build_user($user)
    {
        $u = new User($user->name, $user->email, $user->password);
        $u->setAlert($user->alert_percent);
        $u->setId($user->user_id);
        return $u;
    }

    //creates new user (in case of signup)
    public function create()
    {
        try {
            $connect = pdo_connect();
            //use prepare to prevent SQL injection

            // Insert the user to the database
            $statment = $connect->prepare("INSERT INTO `user`(`name`, `email`, `password`, `alert_percent`)
          VALUES (:username,:email,:password, 100)");
            $statment->bindValue("username", $this->username);
            $statment->bindValue("email", $this->email);
            $statment->bindValue("password", md5($this->password));
            $statment->execute();

            // retrieve the created id for this user
            $statment = $connect->prepare("select user_id from `user` where email = :email");
            $statment->bindValue("email", $this->email);
            $statment->execute();

            $user = $statment->fetchObject();
            $id = $user->user_id;

            // insert a record for them in the budget table for the current month
            // $month = date('m');
            // $year = date('Y');
            // $statment = $connect->prepare("INSERT INTO `budget`(`user_id`, `budget_month`, `initial_budget`, `consumed_budget`, `goal_budget`,`yr`)
            // VALUES (:id,:budget_month,0,0,0,:year)");
            // $statment->bindValue("id", $id);
            // $statment->bindValue("budget_month", $month);
            // $statment->bindValue("year", $year);

            // $statment->execute();

            $connect = null; //to end connection
            return $id; // return the id of the retrieved user
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }

    // function to check if this user exists in system or not (for login)
    public static function find_login($email, $password)
    {
        try {
            $connect = pdo_connect();
            $statment = $connect->prepare("select * FROM `user` WHERE email = :email and password = :password");
            $statment->bindValue("email", $email);
            $statment->bindValue("password", md5($password));
            $statment->execute();
            // if found
            if ($user = $statment->fetchObject()) {
                $connect = null; //end connection before return                
                return $user;
            } else {
                $connect = null;
                return null;
            }
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }

    // function to check if this user already exists in system or not (for signup)
    public static function find_signup($email)
    {
        try {
            $connect = pdo_connect();
            $statment = $connect->prepare("select * FROM `user` WHERE (email = :email)");
            $statment->bindValue("email", $email);
            $statment->execute();

            // if found
            if ($user = $statment->fetchObject()) {
                $connect = null; //end connection before return
                return true;
            } else {
                $connect = null;
                return false;
            }
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }


    public static function find_user($id)
    {
        try {
            $connect = pdo_connect();
            $statment = $connect->prepare("select * FROM `user` WHERE user_id = :id");
            $statment->bindValue("id", $id);
            $statment->execute();
            // if found
            if ($user = $statment->fetchObject()) {
                $u = User::build_user($user);
                $connect = null; //end connection before return
                return $u;
            } else {
                $connect = null;
                return null;
            }
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }


    public static function update_user($id, $name, $alert)
    {
        try {
            $connect = pdo_connect();

            $statment = $connect->prepare("update `user` set name =:name, alert_percent =:alert WHERE (user_id = :id)");
            $statment->bindValue("name", $name);
            $statment->bindValue("alert", $alert);
            $statment->bindValue("id", $id);
            $statment->execute();
            $connect = null; //end connection before return

            // update credentials
            $_SESSION['name'] = $name;
            setcookie('name', null, time(), "/");
            setcookie('name', $name, time() + 60 * 60 * 24 * 365, '/');
            
            return true;
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }


}

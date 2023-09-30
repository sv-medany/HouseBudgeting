<?php
require_once('config.php');


class Budget
{

    private $month, $initial, $consumed, $goal, $user_id, $year;

    public function __construct($month, $initial, $consumed, $goal, $user_id, $year)
    {
        $this->month = $month;
        $this->initial = $initial;
        $this->consumed = $consumed;
        $this->goal = $goal;
        $this->user_id = $user_id;
        $this->year = $year;
    }

    // setters and getters

    public function getMonth()
    {
        return $this->month;
    }
    public function getInitial()
    {
        return $this->initial;
    }

    public function getConsumed()
    {
        return $this->consumed;
    }
    public function getGoal()
    {
        return $this->goal;
    }
    public function getYear()
    {
        return $this->year;
    }


    // retrieve budget details for a specific user and month
    public static function budget_details($id, $month, $year)
    {
        try {
            $connect = pdo_connect();
            $statment = $connect->prepare("select * FROM `budget` WHERE (user_id = :id and budget_month = :month and yr = :year)");
            $statment->bindValue("id", $id);
            $statment->bindValue("month", $month);
            $statment->bindValue("year", $year);

            $statment->execute();

            // if found
            if ($budget = $statment->fetchObject()) {
                $connect = null; //end connection before return
                $b = new Budget($month, $budget->initial_budget, $budget->consumed_budget, $budget->goal_budget, $id, $year);
                return $b;
            } else {
                $connect = null;
                return false;
            }
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }



    // we'll eliminate this function
    public static function delete_old_budgets($id, $curr_month)
    {
        $range_temp = months_range($curr_month);
        $range = implode(',', $range_temp);
        try {
            $connect = pdo_connect();

            $statment = $connect->prepare("delete FROM `budget` WHERE (user_id = :id and budget_month NOT IN(" . $range . ") )");
            $statment->bindValue("id", $id);
            $statment->execute();
            $connect = null; //end connection before return

        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }


    public static function budgetsOfThisYear($id, $year)
    {

        try {
            $connect = pdo_connect();

            // retireve this year's budgets
            $statment = $connect->prepare("select * FROM `budget` WHERE (user_id = :id and yr = :year)");
            $statment->bindValue("id", $id);
            $statment->bindValue("year", $year);

            $statment->execute();

            while ($budget = $statment->fetchObject()) {
                $b = new Budget($budget->budget_month, $budget->initial_budget, $budget->consumed_budget, $budget->goal_budget, $id, $budget->yr);
                $budgets[] = $b; //appends in php
            }
            $connect = null; //end connection before return
            if (!empty($budgets)){
                return $budgets;
            }
            else{
                return false;
            }
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }


    public static function update_budget($id, $init, $goal)
    {
        $month = date('m');
        $year = date('Y');

        try {
            $connect = pdo_connect();

            $statment = $connect->prepare("update `budget` set initial_budget =:init, goal_budget =:goal WHERE (user_id = :id and budget_month = :month and yr = :year)");
            $statment->bindValue("init", $init);
            $statment->bindValue("goal", $goal);
            $statment->bindValue("month", $month);
            $statment->bindValue("id", $id);
            $statment->bindValue("year", $year);

            $statment->execute();
            $connect = null; //end connection before return
            return true;
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }

    public static function update_consumption($id, $consumed)
    {
        $month = date('m');
        $year = date('Y');

        try {
            $connect = pdo_connect();
            $statment = $connect->prepare("update `budget` set consumed_budget =:consumed WHERE (user_id = :id and budget_month = :month and yr =:year)");
            $statment->bindValue("consumed", $consumed);
            $statment->bindValue("month", $month);
            $statment->bindValue("year", $year);
            $statment->bindValue("id", $id);
            $statment->execute();
            $connect = null; //end connection before return
            return true;
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }

    public static function get_budget_years($id){
        try {
            $connect = pdo_connect();
            $statment = $connect->prepare("select distinct yr FROM `budget` WHERE user_id = :id order by yr DESC");
            $statment->bindValue("id", $id);
            $statment->execute();
            // if found
            while ($result = $statment->fetchObject()) {
                $res[] = $result->yr;
            }
            $connect = null;
            if (!empty($res))
            {
                return $res;
            }
            else{
                return false;
            }
            
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }

    // a function to create new budget record automatically for each new month
    public static function create_budget_record($id){
        $month = date('m');
        $year = date('Y');
        try {
            $connect = pdo_connect();

            $statment = $connect->prepare("select * FROM `budget` WHERE (user_id = :id and yr = :year and budget_month= :month)");
            $statment->bindValue("id", $id);
            $statment->bindValue("year", $year);
            $statment->bindValue("month", $month);
            $statment->execute();

            if (!($statment->fetchObject())) { //if not found - create a budget record
                $statment = $connect->prepare("INSERT INTO `budget`(`user_id`, `budget_month`, `initial_budget`, `consumed_budget`, `goal_budget`,`yr`)
                VALUES (:id,:budget_month,0,0,0,:year)");
                $statment->bindValue("id", $id);
                $statment->bindValue("budget_month", $month);
                $statment->bindValue("year", $year);
                $statment->execute();
            }
            return true;
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }
}


function months_range($curr)
{
    $curr = (int)$curr;
    $range = array();
    for ($i = 0; $i < 6; $i++) {
        $range[$i] = $curr;
        $curr -= 1;
        if ($curr == 0) {
            $curr = 12;
        }
    }
    return $range;
}

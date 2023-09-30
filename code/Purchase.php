<?php

require_once('Budget.php');

class Purchase
{

    private $id, $user_id, $date, $name, $category, $price, $payment;

    public function __construct($id, $user_id, $date, $name, $category, $price, $payment)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->date = $date;
        $this->name = $name;
        $this->category = $category;
        $this->price = $price;
        $this->payment = $payment;
    }

    // getters and setters
    public function getId()
    {
        return $this->id;
    }
    public function getDate()
    {
        return $this->date;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getCategory()
    {
        return $this->category;
    }
    public function getPrice()
    {
        return $this->price;
    }

    public function getPayment()
    {
        return $this->payment;
    }

    public static function most_purchased_category($month, $user_id, $year)
    {
        try {
            $connect = pdo_connect();
            $start = "'" . $year . "-" . $month . "-1'";
            $end = "'" . $year . "-" . $month . "-31'";
            $statment = $connect->prepare("select product_category, SUM(price) AS total_spending FROM purchase WHERE (user_id = :id and purchase_date between " . $start . " and " . $end . " ) GROUP BY product_category ORDER BY total_spending DESC LIMIT 1");
            $statment->bindValue("id", $user_id);
            $statment->execute();

            // if found
            if ($purchase = $statment->fetchObject()) {
                $connect = null; //end connection before return
                return $purchase->product_category;
            } else {
                $connect = null;
                return "<span style='font-size:0.7rem;'>No Purchases Are Recorded For This Month</span>";
            }
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }

    public static function visa_cash_count($month, $user_id, $year)
    {
        try {
            $connect = pdo_connect();
            $start = "'" . $year . "-" . $month . "-1'";
            $end = "'" . $year . "-" . $month . "-31'";
            $statment = $connect->prepare("select payment,count(*) as theirCount from purchase where user_id = :id and purchase_date between " . $start . " and " . $end . " GROUP BY payment");
            $statment->bindValue("id", $user_id);

            $statment->execute();

            // if found
            $p = array();
            while ($purchase = $statment->fetchObject()) {
                $p += array($purchase->payment => $purchase->theirCount);
            }
            // 3 cases : no purchases at all, visa or cash only, or both
            $len = count($p);
            if ($len == 0) {
                $p = array("visa" => 0, "cash" => 0);
            } else if ($len == 1) {
                $key = array_keys($p)[0];
                $p += ($key == "visa" ? array("cash" => 0) : array("visa" => 0));
            }
            $connect = null; //end connection before return
            return $p;
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }

    public static function add_purchase($month, $user_id, $year)
    {
        $date = $_POST['date'];
        $item = $_POST['item'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $payment = $_POST['paymethod'];


        // validate date
        if (empty($date) || !validateDate($date, $month, $year)) {
            $date = date('Y-m-d'); // khod date enhrda ka default
        }

        if (!is_numeric($price)) {
            return false;
        }

        try {
            $connect = pdo_connect();
            // 1st: insert
            $statment = $connect->prepare("insert into purchase (user_id, purchase_date, product_name, product_category, price, payment)
            VALUES (:user_id, :date, :item, :category, :price, :payment)");
            $statment->bindValue("user_id", $user_id);
            // $statment->bindValue("month", $month);
            $statment->bindValue("item", $item);
            $statment->bindValue("date", $date);
            $statment->bindValue("category", $category);
            $statment->bindValue("price", $price);
            $statment->bindValue("payment", $payment);
            $statment->execute();

            // 2nd: update budget
            $b = Budget::budget_details($user_id, $month, $year);
            $totalc = $b->getConsumed() + $price;
            Budget::update_consumption($user_id, $totalc);

            return true;
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }

    public static function get_purchases($month, $year, $user_id, $category = null, $above = 0, $below = 0)
    {

        $start = "'" . $year . "-" . $month . "-1'";
        $end = "'" . $year . "-" . $month . "-31'";
        $query = "select * from purchase where purchase_date between " . $start . " and " . $end . " and user_id = :id ";
        if ($category) {
            $query = $query . "and product_category = '" . $category . "' ";
        }
        if ($above != 0) {
            $query = $query . "and price >= " . $above . " ";
        }
        if ($below != 0) {
            $query = $query . "and price <= " . $below . " ";
        }
        $query = $query." order by purchase_date DESC";


        try {
            $connect = pdo_connect();
            $statment = $connect->prepare($query);
            $statment->bindValue("id", $user_id);
            $statment->execute();


            while ($p = $statment->fetchObject()) {
                $purchase = new Purchase($p->purchase_id, $p->user_id, $p->purchase_date, $p->product_name, $p->product_category, $p->price, $p->payment);
                $purchases[] = $purchase;
            }
            $connect = null;
            if (!empty($purchases))
                return $purchases;
            else
                return false;
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }

    public static function get_categories()
    {

        try {
            $connect = pdo_connect();
            $statment = $connect->prepare("select distinct product_category from purchase");
            $statment->execute();
            while ($cat = $statment->fetchObject()) {
                $categories[] = $cat->product_category;
            }

            return $categories;
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }

    public static function delete_purchase($id)
    {
        try {
            $connect = pdo_connect();
            $statment = $connect->prepare("select * from purchase where purchase_id = :id");
            $statment->bindValue("id", $id);
            $statment->execute();
            if ($p = $statment->fetchObject()) {
                $val = $p->price;
                $statment = $connect->prepare("delete FROM `purchase` WHERE (purchase_id = :id)");
                $statment->bindValue("id", $id);
                $statment->execute();
                $connect = null; //end connection before return
                return $val;
            } else {
                $connect = null; //end connection before return
                return false;
            }
        } catch (PDOException $e) {
            catchErrorToFile($e->getMessage(), $e->getCode());
            return false;
        }
    }
}



function validateDate($dateString, $month, $year)
{
    // Convert the date string to a Unix timestamp
    $timestamp = strtotime($dateString);

    // Get the month from the timestamp
    $dateMonth = date('n', $timestamp);
    $dateYear = date('Y', $timestamp);

    // Validate that the date is not in the future
    if ($timestamp > time()) {
        return false;
    }

    // Validate that the date is in the specified month & year (user can't add purchases to past months or future months or any other year)
    if ($dateMonth == $month && $dateYear == $year) {
        return true;
    } else {
        return false;
    }
}

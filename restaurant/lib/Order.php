<?php

require_once __DIR__ . '/Meal.php';
require_once __DIR__ . '/Ticket.php';

class Order
{
    public $id;
    public $user_id;
    public $meal_id;
    public $ticket_id;
    public $drink;
    public $dessert;
    public $department;
    public $status;
    public $created;
    const DB = 'orders';

    public function __construct($id=null){
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function create(){
        global $db;
        $sql = "INSERT INTO ".self::DB
             ." (user_id, meal_id, ticket_id, drink, dessert, department)"
             ." VALUES (:user_id, :meal_id, :ticket_id, :drink, :dessert, :department)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $this->user_id);
        $stmt->bindValue(':meal_id', $this->meal_id);
        $stmt->bindValue(':ticket_id', $this->ticket_id ? $this->ticket_id : null);
        $stmt->bindValue(':drink', trim($this->drink));
        $stmt->bindValue(':dessert', trim($this->dessert));
        $stmt->bindValue(':department', trim($this->department));
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }


    /**
     * @param $id integer Meal Id
     * @return null|Meal
     */
    public static function find($id){
        global $db;
        $sql = "SELECT * FROM ".self::DB." WHERE id = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        if($stmt->execute()){
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $meal = new self($id);
            $meal->user_id = $result['user_id'];
            $meal->meal_id = $result['meal_id'];
            $meal->ticket_id = $result['ticket_id'];
            $meal->status = $result['status'];
            $meal->created = $result['created'];
            return $meal;
        }
        return null;
    }

    /**
     * @return Array<Order>
     */
    public static function findAll(){
        global $db;
        $sql = "SELECT *, ".self::DB.".id AS id FROM ".self::DB
             . " LEFT JOIN ".Meal::DB
             . " ON ".self::DB.".meal_id = ".Meal::DB.".id"
             ." ORDER BY status DESC, created DESC";
        $stmt = $db->prepare($sql);
        if($stmt->execute()){
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
            $orders = [];
            foreach($result as $orders){
                $o = new self($orders['id']);
                $o->meal_id = $orders['meal_id'];
                $o->user_id = $orders['user_id'];
                $o->ticket_id = $orders['ticket_id'];
                $o->status = $orders['status'];
                $o->created = $orders['created'];
                $orders[] = $o;
            }
            return $orders;
        }
        return [];
    }

    /**
     * @return Array<Order>
     */
    public static function findAllByUser($id){
        global $db;
        $sql = "SELECT *, "
             .self::DB.".id AS id, "
             .self::DB.".drink AS oDrink, "
             .self::DB.".dessert AS oDessert FROM " . self::DB
             . " LEFT JOIN " . Meal::DB
             .' ON '. self::DB .'.meal_id = '.Meal::DB.'.id'
             ." WHERE user_id = $id GROUP BY ".self::DB.'.id'
             ." ORDER BY created DESC";
        $stmt = $db->prepare($sql);
        if($stmt->execute()){
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        return [];
    }

    /**
     * @return bool
     */
    public function remove(){
        global $db;
        $sql = "DELETE FROM ".self::DB." WHERE id=:id AND user_id=:user_id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $this->id);
        $stmt->bindValue(':user_id', $this->user_id);
        if($stmt->execute()){
            return true;
        }
    }

    /**
     * @return bool
     */
    public function cancel(){
        global $db;
        $sql = "UPDATE ".self::DB
             ." SET status=:status"
             ." WHERE id=:id AND user_id=:user_id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $this->id);
        $stmt->bindValue(':user_id', $this->user_id);
        $stmt->bindValue(':status', 2);
        if($this->ticket_id){
            Ticket::refund($this->ticket_id);
        }
        if($stmt->execute()){
            return true;
        }
    }

    public function markAsComplete(){
        global $db;
        $sql = "UPDATE ".self::DB." SET status=1 WHERE id=:id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $this->id);
        if($stmt->execute()){
            return true;
        }
    }

    public static function getLastOrder($id){
        global $db;
        $sql = "SELECT * FROM ".self::DB." WHERE status != 2 AND user_id=:id ORDER BY created DESC LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id'=> $id]);
        $result = $stmt->fetch();
        if($result){
            $meal = new self($result['id']);
            $meal->user_id = $result['user_id'];
            $meal->meal_id = $result['meal_id'];
            $meal->status = $result['status'];
            $meal->created = $result['created'];
            return $meal;
        }
    }

    /**
     * @param id
     * @return bool
     */
    public static function orderedToday($id){
		$lastOrder = Order::getLastOrder($id);
		if ($lastOrder) {
		$lastOrder = new DateTime((Order::getLastOrder($id))->created);
        $now = new Datetime();
        $lastOrder = $lastOrder->format('d-m-Y');
        $now = $now->format('d-m-Y');
        return $now === $lastOrder;
		}
    }
}
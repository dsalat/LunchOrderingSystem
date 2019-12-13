<?php

require_once __DIR__ . '/db.php';

class Meal
{
    public $id;
    public $restaurant_id;
    public $course1;
    public $course2;
    public $course3;
    public $drink;
    public $dessert;
    public $price;
    const DB = 'meals';

    public function __construct($id=null){
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function create(){
        global $db;
        $sql = "INSERT INTO ".self::DB." 
                (restaurant_id, course1, course2, course3, drink, dessert, price) VALUES 
                (:restaurant_id, :course1, :course2, :course3, :drink, :dessert, :price)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':course1', $this->course1);
        $stmt->bindValue(':course2', $this->course2);
        $stmt->bindValue(':course3', $this->course3);
        $stmt->bindValue(':drink', $this->drink);
        $stmt->bindValue(':dessert', $this->dessert);
        $stmt->bindValue(':price', $this->price);
        $stmt->bindValue(':restaurant_id', $this->restaurant_id);
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
            $meal->id = $result['id'];
            $meal->restaurant_id = $result['restaurant_id'];
            $meal->course1 = $result['course1'];
            $meal->course2 = $result['course2'];
            $meal->course3 = $result['course3'];
            $meal->drink = $result['drink'];
            $meal->dessert = $result['dessert'];
            $meal->price = $result['price'];
            return $meal;
        }
        return null;
    }

    /**
     * @return Array<Meal>
     */
    public static function findAll(){
        global $db;
        $sql = "SELECT * FROM ".self::DB;
        $stmt = $db->prepare($sql);
        if($stmt->execute()){
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $meals = [];
            foreach($result as $meal){
                $m = new self($meal['id']);
                $m->id = $meal['id'];
                $m->restaurant_id = $meal['restaurant_id'];
                $m->course1 = $meal['course1'];
                $m->course2 = $meal['course2'];
                $m->course3 = $meal['course3'];
                $m->drink = $meal['drink'];
                $m->dessert = $meal['dessert'];
                $m->price = $meal['price'];
                $meals[] = $m;
            }
            return $meals;
        }
        return [];
    }

    /**
     * @return bool
     */
    public function remove(){
        global $db;
        $sql = "DELETE FROM".self::DB."WHERE id=:id LIMIT 1";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id'=>$this->id]);
    }
}
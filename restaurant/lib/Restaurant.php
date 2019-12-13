<?php

require_once __DIR__ . '/db.php';
require_once __DIR__ .'/Meal.php';

class Restaurant
{
    public $id;
    public $name;
    public $address;
    public $telephone;
    const DB = 'restaurant';

    public function __construct($id=null){
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function create(){
        global $db;
        $sql = "INSERT INTO restaurant (name, address, telephone) VALUES (:name, :address, :telephone)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':name', $this->name);
        $stmt->bindValue(':address', $this->address);
        $stmt->bindValue(':telephone', $this->telephone);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }


    /**
     * @param $id integer Restaurant Id
     * @return null|Restaurant
     */
    public static function find($id){
        global $db;
        $sql = "SELECT * FROM restaurant WHERE id = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        if($stmt->execute()){
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $restaurant = new self($id);
            $restaurant->name = $result['name'];
            $restaurant->address = $result['address'];
            $restaurant->telephone = $result['telephone'];
            return $restaurant;
        }
        return null;
    }

    /**
     * @return Array<Menu>
     */
    public function getMenu(){
        global $db;
        $sql = "SELECT * FROM restaurant "
             ." LEFT JOIN ".Meal::DB." ON ".Meal::DB.".restaurant_id=".self::DB.".id"
             ." WHERE ".self::DB.".id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $this->id);
        if($stmt->execute()){
            $restaurant = [];
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public static function findAll(){
        global $db;
        $restaurants = [];
        $sql = "SELECT * FROM restaurant";
        if($stmt = $db->query($sql)){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($results as $r){
                $restaurant = new self($r['id']);
                $restaurant->name = $r['name'];
                $restaurant->address = $r['address'];
                $restaurant->telephone = $r['telephone'];
                $restaurants[] = $restaurant;
            }
            return $restaurants;
        }
        return [];
    }
}
<?php


class Ticket
{
    public $id;
    public $user_id;
    public $created;
    public $used;
    const DB = 'tickets';

    public function __construct($id=null){
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function create(){
        global $db;
        $sql = "INSERT INTO ".self::DB." (user_id) VALUES (:user_id)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $this->user_id);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }


    /**
     * @param $id integer Meal Id
     * @return Array<Meal>
     */
    public static function find($id){
        global $db;
        $sql = "SELECT * FROM ".self::DB." WHERE id = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        if($stmt->execute()){
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $ticket = new self($id);
            $ticket->user_id= $result['user_id'];
            $ticket->created = $result['created'];
            return $ticket;
        }
        return [];
    }

    /**
     * @param $id integer User Id
     * @return Array<Meal>
     */
    public static function findByUser($id){
        global $db;
        $sql = "SELECT * FROM ".self::DB." WHERE user_id=:id AND used=0 ";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        if($stmt->execute()){
            $tickets = [];
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $ticket){
                $t = new self($id);
                $t->user_id= $ticket['user_id'];
                $t->used = $ticket['used'];
                $t->created = $ticket['created'];
                $tickets[] = $t;
            }
            return $tickets;
        }
        return [];
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
            $tickets = [];
            foreach($result as $ticket){
                $ticket = new self($ticket['id']);
                $ticket->user_id= $result['user_id'];
                $ticket->created = $result['created'];
                $tickets[] = $ticket;
            }
            return $tickets;
        }
        return [];
    }

    /**
     * @return bool
     */
    public function avail(){
        global $db;
        $sql = "UPDATE ".self::DB." SET used=1 WHERE id=:id LIMIT 1";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id'=>$this->id]);
    }

    public static function getLastTicket($id){
        global $db;
        $sql = "SELECT * FROM ".self::DB." WHERE user_id=:user_id ORDER BY created DESC LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':user_id'=>$id]);
        $result = $stmt->fetch();
        if($result){
            $ticket = new self($result['id']);
            $ticket->user_id = $result['user_id'];
            $ticket->used = $result['used'];
            $ticket->created = $result['created'];
            return $ticket;
        }
    }

    /**
     * @return bool
     */
    public function gotTicketToday(){
        $lastTicket = self::getLastTicket($this->user_id);
        if($lastTicket){
            $lastOrder = new DateTime(($lastTicket)->created);
            $now = new Datetime();
            $lastOrder = $lastOrder->format('d-m-Y');
            $now = $now->format('d-m-Y');
            return $now === $lastOrder;
        }
        return false;
    }

    public static function refund($id){
        global $db;
        $sql = "UPDATE ".self::DB." SET used=0 WHERE id=:id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }

    public function awardTicket(){
        if(!self::gotTicketToday()){
            $this->user_id = $this->user_id;
            $this->create();
        }
    }
}
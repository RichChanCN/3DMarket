<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "3dmarket";

class Item{
    var $id;
    var $use_case;
    var $type;
    var $name;
    var $price;
    var $discount;
    var $length;
    var $width;
    var $height;
    var $area;
    var $brand;
    var $materials;
    var $description;
    var $made_in;
    var $stock;
    var $style_num;
    var $is_new;
    var $style;
    var $introduction;
    var $current_price;

    function __construct($id,$use_case,$type,$name,$price,$discount,$length,$width,$height,$weight,$brand,$materials,$description,$made_in,$stock,$style_num,$is_new,$style,$introduction)
    {
        $this->id           = $id;
        $this->use_case     = $use_case;
        $this->type         = $type;
        $this->name         = $name;
        $this->price        = $price;
        $this->discount     = $discount;
        $this->length       = $length;
        $this->width        = $width;
        $this->height       = $height;
        $this->area         = $weight;
        $this->brand        = $brand;
        $this->materials    = $materials;
        $this->description  = $description;
        $this->made_in      = $made_in;
        $this->stock        = $stock;
        $this->style_num    = $style_num;
        $this->is_new       = $is_new;
        $this->style        = $style;
        $this->introduction = $introduction;

        $this->current_price= $this->getCurrentPrice();
    }

    function getCurrentPrice(){
        return $this->price * $this->discount;
    }
}

class Order{
    var $id;
    var $item_id;
    var $name;
    var $price;
    var $amount;
    var $discount;
    var $style;

    function __construct($id,$item_id,$name,$price,$amount,$discount,$style)
    {
        $this->id       =$id;
        $this->item_id  =$item_id;
        $this->name     =$name;
        $this->price    =$price;
        $this->amount   =$amount;
        $this->discount =$discount;
        $this->style    =$style;
    }

    function getOrderPrice(){
        return $this->discount * $this->price * $this->amount;
    }

}

class Order_Plus{
    var $id;
    var $item_id;
    var $name;
    var $price;
    var $amount;
    var $discount;
    var $style;
    var $brand;
    var $materials;
    var $order_num;
    var $is_paid;

    function __construct($id,$item_id,$name,$price,$amount,$discount,$style,$brand,$materials,$is_paid)
    {
        $this->id       =$id;
        $this->item_id  =$item_id;
        $this->name     =$name;
        $this->price    =$price;
        $this->amount   =$amount;
        $this->discount =$discount;
        $this->style    =$style;
        $this->brand    =$brand;
        $this->materials=$materials;
        $this->is_paid  =$is_paid;
    }

    function getOrderPrice(){
        return $this->discount * $this->price * $this->amount;
    }

    function getUnitPrice(){
        return $this->discount * $this->price;
    }
}

class Review{
    static $amount = 0;
    static $all_score = 0;
    var $id;
    var $item_id;
    var $user_id;
    var $user_name;
    var $time;
    var $review;
    var $score;

    function __construct($id,$item_id,$user_id,$user_name,$time,$review,$score)
    {
        $this->id           =$id;
        $this->item_id      =$item_id;
        $this->user_id      =$user_id;
        $this->user_name    =$user_name;
        $this->time         =$time;
        $this->review       =$review;
        $this->score        =$score;

        self::$amount+=1;
        self::$all_score+=$score;
    }

    static function getAverageScore(){
        if (self::$amount<1)
            return 0;
        else
            return round(self::$all_score/self::$amount);
    }

    static function clearAmountScore(){
        self::$all_score=0;
        self::$amount=0;
    }

}
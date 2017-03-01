<?php
namespace Actions;

use Library\DBHelper;
use Library\Presentation;

class BuyerAndSellerModel extends Presentation {
    public static $table_buyer  = 'buyer';
    public static $table_seller = 'seller';

    /**
     * Buyer Data Received from Database
     *
     */
    public static function buyer(){

        return DBHelper::getArrayRow('*', self::$table_buyer);

    }

    /**
     * Seller Data Received from Database
     *
     */
    public static function seller(){

        return DBHelper::getArrayRow('*', self::$table_seller);

    }

    
}
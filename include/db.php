<?php

// composer //
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Database
{    
  private static $connection = null;
  
  public static function connect()
  {
    if(self::$connection == null)
    {
      try
      {
        //self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName , self::$dbUsername, self::$dbUserpassword);
        //self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName , self::$dbUsername, self::$dbUserpassword, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        self::$connection = new PDO("mysql:host=" . $_ENV['dbHost'] . ";dbname=" . $_ENV['dbname'] , $_ENV['dbUserName'], $_ENV['dbUserpassword'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
      }
      catch(PDOException $e)
      {
        die($e->getMessage());
      }
    }
    return self::$connection;
  }
  
  public static function disconnect()
  {
    self::$connection = null;
  }

}

?>
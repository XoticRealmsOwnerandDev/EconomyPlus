<?php
namespace EconomyPlus\Provider;

use pocketmine\utils\Config;

use EconomyPlus\EconomyPlus;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, December 2016
 */

class MySQLProvider extends EconomyProvider{

  protected $plugin, $mysql_settings, $connection;

  public function __construct(EconomyPlus $plugin, array $mysql_settings)
  {
    $this->plugin = $plugin;
    $this->mysql_settings = $mysql_settings;
    $this->connection = @mysqli_connect($mysql_settings['host'], $mysql_settings['user'], $mysql_settings['password'], $mysql_settings['db_name']);
    if(!$this->connection)
    {
    	$this->plugin->getLogger()->warning("Unable to connect to MySQL" . PHP_EOL);
    	$this->plugin->getLogger()->warning("Error: " . mysqli_connect_error() . PHP_EOL);
    	return;
    }
    $this->plugin->getLogger()->info("MySQL Connection Successful!");
    $this->plugin->getLogger()->info("Host Info: " . mysqli_get_host_info($this->connection));
    $this->plugin->getLogger()->info("Creating necessary needed tables.....");
    $this->makeQuery();
    $this->plugin->getLogger()->info("All done!");

  }

  public function __destruct()
  {
    $this->plugin->getLogger()->info("Provider set as JSON.");
    $this->plugin->provider = new JsonProvider($this->plugin);
  }

  public function makeQuery(){
    $query = $this->plugin->getResource("query.sql");
    $this->connection->query(stream_get_contents($query));
  }
  
  public function setMoney($player, int $ammount)
  {
    if($player instanceof Player){
      $this->connection->query("UPDATE EconomyPlus SET money = $ammount WHERE username = '".strtolower($this->db->escape_string($player->getName()))."'");
    }
    elseif(is_string($player))
    {
      $this->connection->query("UPDATE EconomyPlus SET money = $ammount WHERE username = '".strtolower($this->db->escape_string($player))."'");
    }
    else{
      throw new \InvalidArgumentException("Arugment 1 passed to MySQLProvider::setMoney() must be type of pocketmine\Player");
    }
  }
  
  public function getMoney($player)
  {
    if($player instanceof Player)
    {
      $result = $this->connection->query("SELECT * FROM EconomyPlus WHERE username = '".$this->connection->escape_string(strtolower($player->getName))."'");

      if($result instanceof \mysqli_result){
        $data = $result->fetch_assoc();
        $result->free();
        if(isset($data["username"]) and strtolower($data["username"]) === $name)
        {
          unset($data["username"]);
          $money = $data["money"]; ??
          return $money;
        }
      }
      else
      {
       $this->plugin->getLogger()->warning("Unable to make connection to MySQL Database!");
       return false;
      }
    }
    elseif(is_string($player)
    {
      $result = $this->connection->query("SELECT * FROM EconomyPlus WHERE username = '".$this->connection->escape_string(strtolower($name))."'");

      if($result instanceof \mysqli_result){
        $data = $result->fetch_assoc();
        $result->free();
        if(isset($data["username"]) and strtolower($data["username"]) === $name)
        {
          unset($data["username"]);
          $money = $data["money"]; ??
          return $money;
        }
      }
      else
      {
       $this->plugin->getLogger()->warning("Unable to make connection to MySQL Database!");
       return false;
      }
    }
    else
    {
      throw new \InvalidArgumentException("Arugment 1 passed to MySQLProvider::getMoney() must be type of pocketmine\Player");
    }
  }
}

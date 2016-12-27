<?php
namespace EconomyPlus\Shop;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\item\Item;

use EconomyPlus\EconomyPlus;

use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockBreakEvent;

use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat as C;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, September 2016
 */

class ShopListener extends PluginBase implements Listener{

  protected $plugin;

  public function __construct(EconomyPlus $plugin, String $prefix)
  {
    $this->plugin = $plugin;
    $this->prefix = $prefix;
  }

  public function onCreate(SignChangeEvent $event){
    /*
    * Format
    * [Prefix]
    * [Item]
    * [Amount]
    * [Cost]
    */
    $text = $event->getLines();
    if($text[0] === "[Shop]"){
    $item = Item::fromString($text[1]);
    if(!$item instanceof Item){
      $event->getPlayer()->sendMessage(C::RED . $this->plugin->translate("INVALID-ITEM"));
      return;
    }
    if(!is_numeric($text[2])){
      $event->getPlayer()->sendMessage(C::RED . $this->plugin->translate("INVALID-AMOUNT"));
      return;
    }
    if(!is_numeric($text[3])){
      $event->getPlayer()->sendMessage(C::RED . $this->plugin->translate("INVALID-PRICE"));
      return;
    }
    if(!$event->getPlayer()->hasPermission("economyplus.shop.create")){
      $event->getPlayer()->sendMessage(C::RED . $this->plugin->translate("INVALID-PERMISSION"));
      $event->setCancelled();
      return;
    }
    $event->setLine(0, $this->prefix);
    $event->setLine(1, "Item: " . $text[1]);
    $event->setLine(2, "amount: " . $text[2]);
    $event->setLine(3, "Price: " . $text[3]);
  }
  }

  public function onInteract(PlayerInteractEvent $event){
    $player = $event->getPlayer();
    $blk = $event->getBlock();
    $tile = $player->getLevel()->getTile($blk);
    if($tile instanceof Sign){
      $text = $tile->getText();
      if($text[0] == $this->prefix){
        $item = substr($text[1], strpos($text[1], "Item: ") + 6);   
        if(Item::fromString($item) instanceof Item){
          $amount = substr($text[2], strpos($text[2], "Amount: ") + 8);
          $price = substr($text[3], strpos($text[3], "Price: ") + 7);
          if(EconomyPlus::getProvider()->getMoney($player) >= $price){ 
            Economypus::buy($player, $item, $amount, $price);
          }
          else{
            $eplayer->sendMessage(C::RED . "Invalid Balance");
          }
        }
      }
    }
  }

  public function onBreak(BlockBreakEvent $event){
    $player = $event->getPlayer();
    $blk = $event->getBlock();
    $tile = $player->getLevel()->getTile($blk);
    if($tile instanceof Sign){
      $text = $tile->getText();
      if($text[0] == $this->prefix){
        $item = substr($text[1], strpos($text[1], "Item: ") + 1);   
        if(Item::fromString($item) instanceof Item){
          if(!$player->isOp()){
            $event->setCancelled();
            return false;
          }
          return true;
        }
      }
    }
}
}

<?php
namespace EconomyPlus\Commands;

use EconomyPlus\BaseFiles\BaseCommand;
use EconomyPlus\Main;
use pocketmine\Player;
use pocketmine\command\CommandSender;

use pocketmine\utils\TextFormat as C;

use EconomyPlus\EconomyPlayer;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class PayMoneyCommand extends BaseCommand{

    private $plugin;

    public function __construct(Main $plugin){
        parent::__construct("pay", $plugin);
        $this->plugin = $plugin;
        $this->setUsage(C::RED . "/pay <player> <ammount>");
        $this->setDescription("Take money from a player!");
    }

    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if(!$sender->isOp()){
            $sender->sendMessage(C::RED . "You dont have permission to use this command");
            return;
        }
        if(!count($args) == 2){
            $sender->sendMessage(C::RED . "Usage: /pay <player> <ammount>");
            return;
        }
        if(strtolower($sender->getName()) === strtolower($args[1])){
            $sender->sendMessage(C::RED . "Invalid Player");
        }
        if(!is_numeric($args[1])){
            $sender->sendMessage(C::RED . "Invalid ammount");
            return;
        }
        $player = new EconomyPlayer($this->plugin, $args[0]);
        $player->pay($args[1], $sender->getName());
        $sender->sendMessage(C::GREEN . "Payed $" C::YELLOW . $args[1] . C::GREEN . " to " . C::YELLOW . $args[0]);
    }
}
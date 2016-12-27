<?php
namespace EconomyPlus\Commands;

use EconomyPlus\EconomyPlus;
use pocketmine\Player;
use pocketmine\command\CommandSender;

use pocketmine\utils\TextFormat as C;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class AddMoneyCommand extends BaseCommand{

    private $plugin;

    public function __construct(EconomyPlus $plugin){
        parent::__construct("addmoney", $plugin);
        $this->plugin = $plugin;
        $this->setUsage(C::RED . "/addmoney <player> <amount>");
        $this->setDescription("Add money to a player!");
    }

    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if(!$sender->isOp()){
            $sender->sendMessage(C::RED . $this->plugin->translate("INVALID-PERMISSION"));
            return;
        }
        if(!count($args) == 2){
            $sender->sendMessage(C::RED . "Usage: /addmoney <player> <amount>");
            return;
        }
            EconomyPlus::getProvider()->addMoney($args[0], EconomyPlus::getProvider()->getMoney($args[0]) + $args[1]);
            $sender->sendMessage(C::GREEN . "Added $" . C::YELLOW . $args[1] . C::GREEN . " to " . C::YELLOW . $args[0]);
        }
    }

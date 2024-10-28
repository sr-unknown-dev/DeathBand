<?php

declare(strict_types=1);

namespace Ghost\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Ghost\Loader;

class LiveCommand extends BaseCommand {

    public function __construct(Loader $plugin) {parent::__construct($plugin, "lives", "Comando para ver y gestionar vidas", "/lives");}

    protected function prepare(): void {
    	$this->setPermission("lives.command");
    }

    public function onRun(CommandSender $sender, string $label, array $args): void {
    	$lives = Loader::getInstance()->getLivesManager()->getLives($sender);
    	$sender->sendMessage("§gLives: §f".$lives);
    }
}

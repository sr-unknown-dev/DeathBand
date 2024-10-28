<?php

declare(strict_types=1);

namespace Ghost\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Ghost\Loader;
use Ghost\commands\subcommands\RemLivesSubCommand;
use Ghost\commands\subcommands\AddLivesSubCommand;

class LiveCommand extends BaseCommand {

    public function __construct(Loader $plugin) {parent::__construct($plugin, "lives", "Comando para ver y gestionar vidas", "/lives <add|remove> <player> [amount]");}

    protected function prepare(): void {
    	$this->setPermission("lives.manage");
    	$this->registerSubCommand(new AddLivesSubCommand(Loader::getInstance(), "add"));
    	$this->registerSubCommand(new RemoveLivesSubCommand(Loader::getInstance(), "remove"));
    }

    public function onRun(CommandSender $sender, string $label, array $args): void {
    	$sender->sendMessage("§gUse: /lives <add|remove> <player> [amount]");
    }
}

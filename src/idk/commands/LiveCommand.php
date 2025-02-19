<?php

declare(strict_types=1);

namespace idk\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use idk\Loader;
use idk\commands\subcommands\RemLivesSubCommand;
use idk\commands\subcommands\AddLivesSubCommand;

class LiveCommand extends BaseCommand {

    public function __construct(Loader $plugin) {parent::__construct($plugin, "live", "Comando para ver y gestionar vidas", []);}

    protected function prepare(): void {
    	$this->setPermission("lives.manage");
    	$this->registerSubCommand(new AddLivesSubCommand("add", "Añade vidas a un jugador"));
    	$this->registerSubCommand(new RemLivesSubCommand("remove", "Remueve vidas a un jugador"));
    }

    public function onRun(CommandSender $sender, string $label, array $args): void {
    	$sender->sendMessage("§gUse: /lives <add|remove> <player> [amount]");
    }

    public function getPermission()
    {
        return "lives.manage";
    }
}

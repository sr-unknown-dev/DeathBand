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
use idk\Item;

class GiveItemLiveCommand extends BaseCommand {

    public function __construct(Loader $plugin) {parent::__construct($plugin, "itemlive", "Comando para recivir el item que te da vidas", []);}

    protected function prepare(): void {
    	$this->setPermission("lives.manage");
    }

    public function onRun(CommandSender $sender, string $label, array $args): void {
    	Item::give($sender);
    }

    public function getPermission()
    {
        return "lives.manage";
    }
}

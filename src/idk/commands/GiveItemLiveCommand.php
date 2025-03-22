<?php

declare(strict_types=1);

namespace idk\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player; // Corrected namespace
use pocketmine\utils\TextFormat;
use idk\Loader;
use idk\Item;

class GiveItemLiveCommand extends BaseCommand {

    public function __construct(Loader $plugin) {
        parent::__construct($plugin, "itemlive", "Command to receive the item that gives lives", []);
    }

    protected function prepare(): void {
        $this->setPermission("lives.manage");
    }

    public function onRun(CommandSender $sender, string $label, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command can only be used in-game.");
            return;
        }
        Item::give($sender);
    }

    public function getPermission(): ?string
    {
        return "lives.manage";
    }
}
<?php

namespace Ghost\commands;

use Ghost\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class UnClaimCommand extends Command {

    public function __construct() {
        parent::__construct("claim", "Claim your territory", "/claim");
        $this->setPermission("deathband.command");
    }

    public function execute(CommandSender $sender, string $label, array $args) {
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game.");
            return;
        }

        if (!$sender->hasPermission("deathband.command")) {
            $sender->sendMessage(TextFormat::RED . "You do not have permission to use this command.");
            return;
        }

        if (!isset($args[0])) {
            $sender->sendMessage(TextFormat::RED . "Please specify the claim ID.");
            return;
        }

        $claimManager = Loader::getInstance()->getClaimManager();

        if (!$claimManager->claimNotExists($args[0])) {
            $sender->sendMessage(TextFormat::RED . "The claim does not exist.");
            return;
        }

        $claimManager->deleteClaim($args[0]);
        $sender->sendMessage(TextFormat::GREEN . "Claim successfully deleted.");
    }
}
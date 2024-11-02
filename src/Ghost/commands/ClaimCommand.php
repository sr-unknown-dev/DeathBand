<?php

namespace Ghost\commands;

use Ghost\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class ClaimCommand extends Command {

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
            $sender->sendMessage(TextFormat::RED."You do not have permission to use this command.");
            return;
        }

        if (Loader::getInstance()->getClaimManager()->claimExists($args[0])) {
            $sender->sendMessage(TextFormat::RED."The claim already exists.");
            return;
        }
        $x = $sender->getPosition()->getX();
        $z = $sender->getPosition()->getZ();
        Loader::getInstance()->getClaimManager()->createClaim($args[0], $x - 10, $z - 10, $x + 10, $z + 10);
    }
}
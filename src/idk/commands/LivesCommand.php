<?php

declare(strict_types=1);

namespace idk\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player; // Corrected namespace
use pocketmine\utils\TextFormat;
use idk\Loader;

class LivesCommand extends BaseCommand {

    public function __construct(Loader $plugin) {
        parent::__construct($plugin, "lives", "Command to view and manage lives", []);
    }

    protected function prepare(): void {
        $this->setPermission("lives.command");
    }

    public function onRun(CommandSender $sender, string $label, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command can only be used in-game.");
            return;
        }
        $lives = Loader::getInstance()->getLivesManager()->getLives($sender);
        $sender->sendMessage("§gLives: §f" . $lives);
    }

    public function getPermission(): ?string
    {
        return "lives.command";
    }
}

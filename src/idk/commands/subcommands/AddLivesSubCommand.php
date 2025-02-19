<?php

declare(strict_types=1);

namespace idk\commands\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use idk\Loader;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

class AddLivesSubCommand extends BaseSubCommand {
    
    public function __construct() {
        parent::__construct("add", "Add lives to a player");
    }

    protected function prepare(): void {
        $this->setPermission("lives.manage");
        $this->registerArgument(0, new RawStringArgument("player", false));
        $this->registerArgument(1, new IntegerArgument("amount", false));
    }

    public function onRun(CommandSender $sender, string $label, array $args): void {
        if (!isset($args["player"]) || !isset($args["amount"])) {
            $sender->sendMessage(TF::RED . "Usage: /lives add <player> <amount>");
            return;
        }

        try {
            Loader::getInstance()->getLivesManager()->addLives($args["player"], (int)$args["amount"]);
            $sender->sendMessage(TF::GREEN . "Added {$args["amount"]} lives to {$args["player"]}");
        } catch (\Exception $e) {
            $sender->sendMessage(TF::RED . "An error occurred while adding lives.");
        }
    }
}

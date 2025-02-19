<?php

declare(strict_types=1);

namespace idk\commands\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use idk\Loader;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

class RemLivesSubCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct("remove", "Remove lives from a player");
    }

    protected function prepare(): void {
        $this->setPermission("lives.manage");
        $this->registerArgument(0, new RawStringArgument("player", false));
        $this->registerArgument(1, new IntegerArgument("amount", false));
    }

    public function onRun(CommandSender $sender, string $label, array $args): void {
        if (!isset($args["player"]) || !isset($args["amount"])) {
            $sender->sendMessage(TF::RED . "Usage: /lives remove <player> <amount>");
            return;
        }

        try {
            Loader::getInstance()->getLivesManager()->removeLives($args["player"], (int)$args["amount"]);
            $sender->sendMessage(TF::GREEN . "Removed {$args["amount"]} lives from {$args["player"]}");
        } catch (\Exception $e) {
            $sender->sendMessage(TF::RED . "An error occurred while removing lives.");
        }
    }
}

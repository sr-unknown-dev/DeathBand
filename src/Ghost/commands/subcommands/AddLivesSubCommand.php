<?php

declare(strict_types=1);

namespace Ghost\commands\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use LifeSystemPlugin\Main;

class AddLivesSubCommand extends BaseSubCommand {
	
		public function __construct(string $name, string $description = "", array $aliases = []){parent::__construct($name, $description, $aliases);}

    protected function prepare(): void {
    	$this->setPermission("lives.manage");
    	$this->registerArgument(0, new RawStringArgument("player", true));
    	$this->registerArgument(1, new IntegerArgument("amount", true));
    }

    public function onRun(CommandSender $sender, string $label, array $args): void {
        if (!$sender->hasPermission("lives.manage")) {
            $sender->sendMessage(TextFormat::RED . "No tienes permiso para usar este comando.");
            return;
        }

        $player = $args["player"];
        $amount = $args["amount"];
        Loader::getInstance()->getLivesManager()->addLives($player, $amount);
        $sender->sendMessage(TextFormat::GREEN . "Has agregado " . $amount . " vidas a " . $playerName);
    }
}

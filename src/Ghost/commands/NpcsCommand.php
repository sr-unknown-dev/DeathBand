<?php

namespace Ghost\commands;

use Ghost\Npcs\Kit;
use Ghost\Npcs\Modality;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class NpcsCommand extends Command{

    public function __construct()
    {
        parent::__construct("npcsdeathband", "Manage npcs", "/npcs <kit|modality>");
        $this->setPermission("deathband.command");
    }

    public function execute(CommandSender $player, string $label, array $args)
    {
        if (!$player instanceof Player)
        return;

        switch (strtolower($args[0])) {
            case 'kit':
                $kit = Kit::create($player);
                $kit->spawnToAll();
                $player->sendMessage("§aKit spawned");
                break;
            case 'modality':
                $modality = Modality::create($player);
                $modality->spawnToAll();
                $player->sendMessage("§aModality spawned");
                break;
            
            default:
                $player->sendMessage("§cUsage: /npcs <kit|modality>");
                break;
        }
    }
}
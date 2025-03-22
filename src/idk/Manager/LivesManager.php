<?php

/*
 * Copyright (c) 2024 Sr.Idk. All rights reserved.
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

namespace idk\Manager;

use idk\DatabaseManager;
use idk\Loader;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\WorldManager;

class LivesManager
{
    private Loader $loader;
    private DatabaseManager $dbManager;
    private Config $pluginConfig;
    private WorldManager $worldManager;

    public function __construct() {
        $this->loader = Loader::getInstance();
        $this->dbManager = $this->loader->getDatabaseManager();
        $this->pluginConfig = $this->loader->getConfig();
        $this->worldManager = Server::getInstance()->getWorldManager();
    }

    public function getLives(Player $player): int {
        $stmt = $this->dbManager->getDatabase()->prepare("SELECT lives FROM players WHERE name = ?");
        $stmt->bind_param("s", $player->getName());
        $stmt->execute();
        $result = $stmt->get_result();
        $lives = $result->fetch_assoc()["lives"] ?? 0;
        $stmt->close();
        return $lives;
    }

    public function addLives(Player|string $player, int $amount): void {
        $player = $this->getPlayer($player);
        if ($player !== null) {
            $currentLives = $this->getLives($player);
            $newLives = $currentLives + $amount;
            $this->updateLives($player, $newLives);
        }
    }

    public function removeLives(Player|string $player, int $amount = 1): void {
        $player = $this->getPlayer($player);
        if ($player !== null) {
            $currentLives = $this->getLives($player);
            $newLives = max(0, $currentLives - $amount);
            $this->updateLives($player, $newLives);
        }
    }

    private function updateLives(Player $player, int $lives): void {
        $stmt = $this->dbManager->getDatabase()->prepare("UPDATE players SET lives = ? WHERE name = ?");
        $stmt->bind_param("is", $lives, $player->getName());
        $stmt->execute();
        $stmt->close();
    }

    public function addDeathBand(Player $player): void {
        $worldName = $this->pluginConfig->get("name-world-modaly");
        $world = $this->worldManager->getWorldByName($worldName) ?? $this->worldManager->loadWorld($worldName);
        
        if ($world !== null) {
            $player->teleport($world->getSafeSpawn());
        }
    }

    public function isInDeathBandMap(Player $player): bool {
        return $player->getWorld()->getFolderName() === $this->pluginConfig->get("teleport.name.deathband");
    }

    private function getPlayer(Player|string $player): ?Player {
        if ($player instanceof Player) {
            return $player;
        }
        return Server::getInstance()->getPlayerExact($player);
    }
}
<?php

/*
 * Copyright (c) 2024 Sr.Idk. All rights reserved.
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

namespace idk\Manager;

use idk\Loader;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\WorldManager;

class LivesManager
{
    private Loader $loader;
    private Config $config;
    private Config $pluginConfig;
    private WorldManager $worldManager;

    public function __construct() {
        $this->loader = Loader::getInstance();
        $this->config = new Config($this->loader->getDataFolder() . "lives.yml", Config::YAML);
        $this->pluginConfig = $this->loader->getConfig();
        $this->worldManager = Server::getInstance()->getWorldManager();
    }

    public function getLives(Player $player): int {
        return $this->config->get($player->getName(), 0);
    }

    public function addLives(Player|string $player, int $amount): void {
        $player = $this->getPlayer($player);
        if ($player !== null) {
            $this->updateLives($player, $this->getLives($player) + $amount);
        }
    }

    public function removeLives(Player|string $player, int $amount = 1): void {
        $player = $this->getPlayer($player);
        if ($player !== null) {
            $this->updateLives($player, max(0, $this->getLives($player) - $amount));
        }
    }

    private function updateLives(Player $player, int $lives): void {
        $this->config->set($player->getName(), $lives);
        $this->config->save();
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
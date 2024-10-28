<?php

namespace Ghost\Manager;

use Ghost\Loader;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class LivesManager
{
    private Loader $loader;
    private Config $config;

    public function __construct() {
        $this->loader = Loader::getInstance();
        $this->config = new Config($this->loader->getDataFolder() . "lives.yml", Config::YAML);
    }

    public function getLives(Player $player): int {
        return $this->config->get($player->getName(), null);
    }

    public function addLives($player, int $amount): void {
        $player = $this->getPlayer($player);
        if ($player !== null) {
            $this->updateLives($player, $this->getLives($player) + $amount);
        }
    }

    public function removeLives($player, int $amount = 1): void {
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
        $config = $this->loader->getConfig();
        $worldName = $config->get("teleport.name.deathband");
        $wm = Server::getInstance()->getWorldManager();
        $world = $wm->getWorldByName($worldName);

        if ($world === null) {
            $wm->loadWorld($worldName);
            $world = $wm->getWorldByName($worldName);
        }

        if ($world !== null) {
            $player->teleport($world->getSafeSpawn());
        }
    }

    public function isInDeathBandMap(Player $player): bool {
        $config = $this->loader->getConfig();
        $deathBandWorldName = $config->get("teleport.name.deathband");
        return $player->getWorld()->getFolderName() === $deathBandWorldName;
    }

    private function getPlayer($player): ?Player {
        if ($player instanceof Player) {
            return $player;
        } elseif (is_string($player)) {
            return Server::getInstance()->getPlayerExact($player);
        }
        return null;
    }
}
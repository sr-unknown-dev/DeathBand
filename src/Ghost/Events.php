<?php

/*
 * Copyright (c) 2024 Ghost. All rights reserved.
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

namespace Ghost;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;

class Events implements Listener
{
    public function handleDeath(PlayerDeathEvent $event): void
    {
        $player = $event->getPlayer();
        $livesManager = Loader::getInstance()->getLivesManager();
        $lives = $livesManager->getLives($player);
        $cause = $player->getLastDamageCause();
        $worldName = $player->getWorld()->getFolderName();
        $configWorldName = Loader::getInstance()->getConfig()->get("teleport.name.deathband");

        if ($lives === 0) {
            if ($worldName !== $configWorldName) {
                $livesManager->removeLives($player, 1);
            }
        } else {
            $livesManager->addDeathBand($player);
        }

        if ($cause instanceof EntityDamageByEntityEvent) {
            $killer = $cause->getDamager();
            if ($killer instanceof Player) {
                $livesManager->addLives($killer, 1);
            }
        }
    }

    public function handleJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        $livesManager = Loader::getInstance()->getLivesManager();
        $lives = $livesManager->getLives($player);

        if ($lives === null) {
            $livesManager->addLives($player, 3);
        }
    }
}
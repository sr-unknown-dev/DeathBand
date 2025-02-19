<?php

/*
 * Copyright (c) 2024 idk. All rights reserved.
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

namespace idk;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use pocketmine\Server;

class Events implements Listener
{

    private $positions = [];

    public function handleInteract(PlayerInteractEvent $event): void{
        $player = $event->getPlayer();

        if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag("live") === "+1") {
            Loader::getInstance()->getLivesManager()->addLives($player, 1);
            return;
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

    public function handleDeath(PlayerDeathEvent $event): void
    {
        $player = $event->getPlayer();
        $livesManager = Loader::getInstance()->getLivesManager();
        $lives = $livesManager->getLives($player);
        $cause = $player->getLastDamageCause();
        $worldName = $player->getWorld()->getFolderName();
        $configWorldName = Loader::getInstance()->getConfig()->get("teleport.name.deathband");

        if ($lives === 0) {
            $livesManager->addDeathBand($player);
        } elseif ($lives >= 1) {
            if ($worldName !== $configWorldName) {
                $livesManager->removeLives($player, 1);
            }
        }

        if ($cause instanceof EntityDamageByEntityEvent) {
            $killer = $cause->getDamager();
            if ($killer instanceof Player) {
                $livesManager->addLives($killer, 1);
                $livesManager->removeLives($player, 1);
            }
        }
    }
}
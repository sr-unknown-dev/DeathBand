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

    public function handleJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        $livesManager = Loader::getInstance()->getLivesManager();
        $lives = $livesManager->getLives($player);

        if ($lives === null) {
            $livesManager->addLives($player, 3);
        }
    }

    public function handleDamage(EntityDamageEvent $event): void
    {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            $worldName = $entity->getWorld()->getFolderName();
            $claimName = Loader::getInstance()->getClaimManager()->getClaim($entity->getPosition()->getX(), $entity->getPosition()->getZ());

            if ($claimName === "SafeZone") {
                $event->cancel();
            }
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

    public function onPlayerInteract(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        $item = $event->getItem();

        if ($item->getCustomName() === "§3SkyBase Selector") {
            $position = $event->getBlock()->getPosition();

            if ($event->getAction() === PlayerInteractEvent::LEFT_CLICK_BLOCK) {
                $this->positions[$player->getName()]['pos1'] = $position;
                $player->sendMessage("§aFirst position placed");
            } elseif ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
                $this->positions[$player->getName()]['pos2'] = $position;
                $player->sendMessage("§aSecond position placed");

                if (isset($this->positions[$player->getName()]['pos1'])) {
                    $pos1 = $this->positions[$player->getName()]['pos1'];
                    $pos2 = $this->positions[$player->getName()]['pos2'];

                    $sizeX = abs($pos1->getX() - $pos2->getX()) + 1;
                    $sizeZ = abs($pos1->getZ() - $pos2->getZ()) + 1;

                    if ($sizeX > 50 || $sizeZ > 50) {
                        $player->sendMessage("§cNo se pudo crear la SkyBase porque la selección es mayor a 50 bloques.");
                        return;
                    }

                    Loader::getInstance()->getClaimManager()->createClaim($this->positions[$player->getName()]['pos1'], $this->positions[$player->getName()]['pos2'], $player);
                }
            }
        }
    }
}
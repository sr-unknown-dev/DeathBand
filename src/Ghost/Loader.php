<?php

/*
 * Copyright (c) 2024 Ghost. All rights reserved.
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

namespace Ghost;

use Ghost\claims\ClaimManager;
use Ghost\commands\ClaimCommand;
use Ghost\Manager\LivesManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use Ghost\commands\LivesCommand;
use Ghost\commands\LiveCommand;
use Ghost\commands\NpcsCommand;
use Ghost\commands\UnClaimCommand;
use Ghost\Npcs\Kit;
use Ghost\Npcs\Modality;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;

class Loader extends PluginBase
{
    use SingletonTrait;
    private $LivesManager;
    private $claimManager;

    protected function onLoad(): void{self::setInstance($this);}
    protected function onEnable(): void
    {
        $this->LivesManager = new LivesManager();
        $this->claimManager = new ClaimManager();
        $this->getLogger()->info("Deatband is Enable");
        $this->getServer()->getPluginManager()->registerEvents(new Events(), $this);
        $this->getServer()->getCommandMap()->register("deatband", new LivesCommand($this));
        $this->getServer()->getCommandMap()->register("deatband", new LiveCommand($this));
        $this->getServer()->getCommandMap()->register("deatband", new NpcsCommand($this));
        $this->getServer()->getCommandMap()->register("deatband", new ClaimCommand($this));
        $this->getServer()->getCommandMap()->register("deatband", new UnClaimCommand($this));
        $this->saveDefaultConfig();
        EntityFactory::getInstance()->register(Kit::class, function (World $world, CompoundTag $nbt): Kit {
        	return new Kit(EntityDataHelper::parseLocation($nbt, $world), Kit::parseSkinNBT($nbt), $nbt);
        }, ['Kit']);

        EntityFactory::getInstance()->register(Modality::class, function (World $world, CompoundTag $nbt): Modality {
            return new Modality(EntityDataHelper::parseLocation($nbt, $world), Modality::parseSkinNBT($nbt), $nbt);
        }, ['Modality']);
    }

    public function getLivesManager(): LivesManager
    {
        return $this->LivesManager;
    }

    public function getClaimManager(): ClaimManager
    {
        return $this->claimManager;
    }
}
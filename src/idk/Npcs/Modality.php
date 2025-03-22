<?php

namespace idk\Npcs;

use idk\Loader;
use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\World;

class Modality extends Human
{
    public bool $canCollide = false;
    protected bool $immobile = true;

    protected function getInitialDragMultiplier(): float { return 0.00; }

    protected function getInitialGravity(): float { return 0.00; }

    public static function create(Player $player): self
    {
        $nbt = CompoundTag::create()
            ->setTag("Pos", new ListTag([
                new DoubleTag($player->getLocation()->x),
                new DoubleTag($player->getLocation()->y),
                new DoubleTag($player->getLocation()->z)
            ]))
            ->setTag("Motion", new ListTag([
                new DoubleTag($player->getMotion()->x),
                new DoubleTag($player->getMotion()->y),
                new DoubleTag($player->getMotion()->z)
            ]))
            ->setTag("Rotation", new ListTag([
                new FloatTag($player->getLocation()->yaw),
                new FloatTag($player->getLocation()->pitch)
            ]));
        return new self($player->getLocation(), $player->getSkin(), $nbt);
    }

    public function canBeMovedByCurrents(): bool
    {
        return false;
    }

    public function onUpdate(int $currentTick): bool
    {
        $text = TextFormat::colorize("--------------------\n&gHCF\n--------------------");

        $this->setNameTagAlwaysVisible();
        $this->setNameTag($text);
        return parent::onUpdate($currentTick);
    }

    public function attack(EntityDamageEvent $source): void
{
    $source->cancel();

    if ($source instanceof EntityDamageByEntityEvent) {
        $damager = $source->getDamager();

        if ($damager instanceof Player) {
            $livesManager = Loader::getInstance()->getLivesManager();
            $lives = $livesManager->getLives($damager);

            if ($lives >= 1) {
                $config = Loader::getInstance()->getConfig();
                $worldName = $config->get("name-world-modaly");
                $wm = Server::getInstance()->getWorldManager();
                $world = $wm->getWorldByName($worldName);

                if ($world === null) {
                    $wm->loadWorld($worldName);
                    $world = $wm->getWorldByName($worldName);
                }

                if ($world !== null) {
                    $damager->teleport($world->getSafeSpawn());
                    $damager->getInventory()->clearAll();
                    $damager->getArmorInventory()->clearAll();
                }
            } else {
                $damager->sendMessage(TextFormat::RED . "You have no lives left!");
            }
        }
    }
}
}
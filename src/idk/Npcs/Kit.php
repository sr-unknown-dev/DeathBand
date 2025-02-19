<?php

namespace idk\Npcs;

use Himbeer\LibSkin\SkinConverter;
use hcf\utils\inventorie\Inventories;
use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\PotionType;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\protocol\types\Enchant;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class Kit extends Human
{
    public bool $canCollide = false;
    protected bool $immobile = true;

    protected function getInitialDragMultiplier() : float{ return 0.00; }

    protected function getInitialGravity() : float{ return 0.00; }

    /** @var int|null */

    /**
     * @param Player $player
     *
     * @return self
     */
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
        $entity = new self($player->getLocation(), $player->getSkin(), $nbt);
        
        $entity->getArmorInventory()->setHelmet(VanillaItems::DIAMOND_HELMET());
        $entity->getArmorInventory()->setChestplate(VanillaItems::DIAMOND_CHESTPLATE());
        $entity->getArmorInventory()->setLeggings(VanillaItems::DIAMOND_LEGGINGS());
        $entity->getArmorInventory()->setBoots(VanillaItems::DIAMOND_BOOTS());
        
        return $entity;
    }

    public function canBeMovedByCurrents(): bool
    {
        return false;
    }

    /**
     * @param int $currentTick
     *
     * @return bool
     * @throws \Exception
     */
    public function onUpdate(int $currentTick): bool
    {
        $text = TextFormat::colorize("--------------------\n&gKit\n--------------------");

        $this->setNameTagAlwaysVisible();
        $this->setNameTag($text);
        return parent::onUpdate($currentTick);
    }

    /**
     * @param EntityDamageEvent $source
     */
    public function attack(EntityDamageEvent $source): void {

        $source->cancel();

        if ($source instanceof EntityDamageByEntityEvent) {
            $damager = $source->getDamager();

            if ($damager instanceof Player) {
                if ($damager->hasPermission('npc.command') && $damager->getInventory()->getItemInHand()->getCustomName() === "§l§3Removed") {
                    $this->kill();
                    return;
                }
                
                /* Kit */
                $damager->getInventory()->clearAll();
                $helmet = VanillaItems::DIAMOND_HELMET();
                $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2));
                $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 2));

                $chestplate = VanillaItems::DIAMOND_CHESTPLATE();
                $chestplate->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2));
                $chestplate->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 2));

                $leggings = VanillaItems::DIAMOND_LEGGINGS();
                $leggings->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2));
                $leggings->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 2));

                $boots = VanillaItems::DIAMOND_BOOTS();
                $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2));
                $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 2));

                $sword = VanillaItems::DIAMOND_SWORD();
                $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 2));
                $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 2));

                $potion = VanillaItems::SPLASH_POTION()->setType(PotionType::STRONG_HEALING());
                $potion->setCount(36);
                $perl = VanillaItems::ENDER_PEARL()->setCount(16);
                $Apples = VanillaItems::GOLDEN_APPLE()->setCount(32);
                $damager->getInventory()->setItem(8, $Apples);
                $damager->getInventory()->addItem($potion);
                $damager->getInventory()->setItem(1, $perl);
                $damager->getInventory()->setItem(0, $sword);
                $damager->getArmorInventory()->setHelmet($helmet);
                $damager->getArmorInventory()->setChestplate($chestplate);
                $damager->getArmorInventory()->setLeggings($leggings);
                $damager->getArmorInventory()->setBoots($boots);
            }
        }
    }
}
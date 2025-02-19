<?php

namespace idk;

use pocketmine\block\utils\DyeColor;
use pocketmine\item\Dye;
use pocketmine\item\Item as ItemItem;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class Item {

    public function give(Player $player){
        $live = VanillaItems::DYE()->setColor(DyeColor::BLUE())->setLore(["§7The life of the player"]);
        $live->getNamedTag()->setString("lives", "1");
        $player->getInventory()->addItem($live);
    }
}
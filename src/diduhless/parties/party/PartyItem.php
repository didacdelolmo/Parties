<?php


namespace diduhless\parties\party;


use diduhless\parties\session\SessionFactory;
use diduhless\parties\utils\ConfigGetter;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemUseResult;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class PartyItem extends Item {

    public function __construct() {
        $item = ConfigGetter::getPartyItemValues();
        parent::__construct(new ItemIdentifier($item[0], $item[1]), $item[2]);
        $this->setCustomName(TextFormat::GREEN . $item[2]);
        $this->getNamedTag()->setByte("parties", 1);
    }

    public function onClickAir(Player $player, Vector3 $directionVector): ItemUseResult {
        if(SessionFactory::hasSession($player)) {
            SessionFactory::getSession($player)->openPartyForm();
            return ItemUseResult::SUCCESS();
        }
        return ItemUseResult::FAIL();
    }

}
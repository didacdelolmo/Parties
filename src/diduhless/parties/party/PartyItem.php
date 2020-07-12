<?php


namespace diduhless\parties\party;


use diduhless\parties\session\SessionFactory;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PartyItem extends Item {

    public function __construct() {
        $this->setCustomName(TextFormat::GREEN . "Party");
        parent::__construct(Item::BOOK, 0, "Party");
    }

    public function onClickAir(Player $player, Vector3 $directionVector): bool {
        if(SessionFactory::hasSession($player)) {
            SessionFactory::getSession($player)->openPartyForm();
        }
        return false;
    }

}
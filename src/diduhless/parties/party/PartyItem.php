<?php


namespace diduhless\parties\party;


use diduhless\parties\session\SessionFactory;
use diduhless\parties\utils\ConfigGetter;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PartyItem extends Item {

    public function __construct() {
        $item = ConfigGetter::getPartyItemValues();
        $this->setCustomName(TextFormat::GREEN . $item[2]);
        parent::__construct($item[0], $item[1], $item[2]);
    }

    public function onClickAir(Player $player, Vector3 $directionVector): bool {
        if(SessionFactory::hasSession($player)) {
            SessionFactory::getSession($player)->openPartyForm();
            return true;
        }
        return false;
    }

}
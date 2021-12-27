<?php


namespace diduhless\parties\form\element;


use diduhless\parties\session\SessionFactory;
use EasyUI\element\Button;
use pocketmine\player\Player;

class GoBackPartyButton extends Button {

    public function __construct() {
        parent::__construct("Go back", null, function(Player $player) {
            SessionFactory::getSession($player)->openPartyForm();
        });
    }

}
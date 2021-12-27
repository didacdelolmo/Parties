<?php


namespace diduhless\parties\form\element;


use EasyUI\element\Button;
use EasyUI\Form;
use pocketmine\player\Player;

class GoBackButton extends Button {

    public function __construct(Form $form) {
        parent::__construct("Go back", null, function(Player $player) use ($form) {
            $player->sendForm($form);
        });
    }

}
<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\form\PartySimpleForm;
use pocketmine\Player;

class PartyMenuForm extends PartySimpleForm {

    public function onCreation(): void {
        $this->setTitle("Party Menu");
        $this->setContent("You do not have a party! Create a party or accept an invitation to join a party.");
        $this->addButton("Create a party");
        $this->addButton("Invitations [" . count($this->getSession()->getInvitations()) . "]");
    }

    public function setCallback(Player $player, ?int $result): void {
        if($result === null) return;

        switch($result) {
            case 0:
                // Send the create party form
                break;
            case 1:
                // Send the invitations form
                break;
        }
    }
}
<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\form\PartySimpleForm;
use diduhless\parties\party\Invitation;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class InvitationsForm extends PartySimpleForm {

    /** @var Invitation[] */
    private $invitations;

    public function onCreation(): void {
        $this->invitations = $this->getSession()->getInvitations();

        $this->setTitle("Party Invitations");
        if(empty($this->invitations)) {
            $this->setContent("You do not have any invitations! :(");
            return;
        }

        $this->setContent("These are your party invitations:");
        foreach($this->invitations as $invitation) {
            $this->addButton(TextFormat::GREEN . $invitation->getSender()->getPlayer()->getName() . "'s Party");
        }
    }

    public function setCallback(Player $player, ?int $result): void {
        if($result !== null and isset($this->invitations[$result])) {
            // Send confirmation form (yes or no)
        }
    }
}
<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\form\PartySimpleForm;
use diduhless\parties\party\Invitation;
use pocketmine\utils\TextFormat;

class InvitationsForm extends PartySimpleForm {

    /** @var Invitation[] */
    private $invitations;

    public function onCreation(): void {
        $this->invitations = $this->getSession()->getInvitations();

        $this->setTitle("Party Invitations");
        if(empty($this->invitations)) {
            $this->setContent("You do not have any invitations! :(");
        } else {
            $this->setContent("These are your party invitations:");
            foreach($this->invitations as $invitation) {
                $this->addButton(TextFormat::GREEN . $invitation->getSender()->getUsername() . "'s Party");
            }
        }
        $this->addButton("Go back");
    }

    public function setCallback(?int $result): void {
        $session = $this->getSession();
        if($result === null) {
            return;
        } elseif(empty($this->invitations) and $result === 0 or !empty($this->invitations) and $result === count($this->invitations) + 1) {
            $session->openPartyForm();
        } elseif(isset($this->invitations[$result])) {
            $session = $this->getSession();
            $session->getPlayer()->sendForm(new ConfirmInvitationForm($this->invitations[$result], $session));
        }
    }
}
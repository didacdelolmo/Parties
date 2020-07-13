<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\form\PartySimpleForm;
use diduhless\parties\party\Invitation;

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
                $this->addButton($invitation->getSender()->getUsername() . "'s Party");
            }
        }
        $this->addButton("Go back");
    }

    public function setCallback(?int $result): void {
        $session = $this->getSession();
        if($result === null) {
            return;
        } elseif((empty($this->invitations) and $result === 0) or (!empty($this->invitations) and $result === count($this->invitations))) {
            $session->openPartyForm();
        } else {
            $session = $this->getSession();
            $session->getPlayer()->sendForm(new ConfirmInvitationForm(array_values($this->invitations)[$result], $session));
        }
    }
}
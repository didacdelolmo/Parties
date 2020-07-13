<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\form\PartySimpleForm;
use diduhless\parties\party\Invitation;
use diduhless\parties\party\Party;
use diduhless\parties\party\PartyFactory;

class PublicPartiesForm extends PartySimpleForm {

    /** @var Party[] */
    private $parties;

    public function onCreation(): void {
        $parties = PartyFactory::getParties();
        foreach($parties as $party) {
            if($party->isPublic()) {
                $this->parties[] = $party;
                $this->addButton($party->getLeaderName() . "'s Party");
            }
        }
        $this->setTitle("Join a public party");
        if(empty($this->parties)) {
            $this->setContent("There are no public parties to join! :(");
        } else {
            $this->setContent("Press on the party you want to join!");
        }
        $this->addButton("Go back");
    }

    public function setCallback(?int $result): void {
        if($result === null) return;
        $session = $this->getSession();

        if(empty($this->parties) and $result === 0 or !empty($this->parties) and $result === count($this->parties) + 1) {
            $session->openPartyForm();
        } else {
            $party = $this->parties[$result];
            $session->getPlayer()->sendForm(new ConfirmInvitationForm(new Invitation($party->getLeader(), $session, $party->getId()), $session));
        }
    }
}
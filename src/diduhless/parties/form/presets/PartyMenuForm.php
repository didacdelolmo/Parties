<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\event\PartyCreateEvent;
use diduhless\parties\form\PartySimpleForm;
use diduhless\parties\party\Party;
use diduhless\parties\party\PartyFactory;
use pocketmine\Player;

class PartyMenuForm extends PartySimpleForm {

    public function onCreation(): void {
        $this->setTitle("Party Menu");
        $this->setContent("You do not have a party! Create a party or accept an invitation to join a party.");
        $this->addButton("Create a party");
        $this->addButton("Join a public party");
        $this->addButton("Invitations [" . count($this->getSession()->getInvitations()) . "]");
    }

    public function setCallback(Player $player, ?int $result): void {
        if($result === null) return;
        switch($result) {
            case 0:
                $this->onPartyCreate();
                break;
            case 1:
                $this->onOpenPublicParties();
                break;
            case 2:
                $this->onOpenInvitations();
                break;
        }
    }

    private function onPartyCreate(): void {
        $session = $this->getSession();
        $party = new Party(uniqid(), [$session], $session);
        $event = new PartyCreateEvent($party, $session);

        $event->call();
        if(!$event->isCancelled()) {
            PartyFactory::addParty($party);
            $session->setParty($party);
        }
    }

    private function onOpenPublicParties(): void {
        $session = $this->getSession();
        $session->getPlayer()->sendForm(new PublicPartiesForm($session));
    }

    private function onOpenInvitations(): void {
        $session = $this->getSession();
        $session->getPlayer()->sendForm(new InvitationsForm($session));
    }
}
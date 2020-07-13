<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\event\PartyLockEvent;
use diduhless\parties\event\PartyUnlockEvent;
use diduhless\parties\event\PartyUpdateSlotsEvent;
use diduhless\parties\form\PartyCustomForm;
use diduhless\parties\party\Party;

class PartyOptionsForm extends PartyCustomForm {

    public function onCreation(): void {
        $party = $this->getSession()->getParty();

        $this->setTitle("Party Options");
        $this->addLabel("Change the party options in this window.");
        $this->addToggle("Do you want to set your party public?", $party->isPublic());
        $this->addSlider("Set your maximum party slots", 1, Party::MAXIMUM_PARTY_MEMBERS, 1, $party->getSlots());
    }

    public function setCallback(?array $options): void {
        $session = $this->getSession();
        if($options === null or !$session->hasParty() or !isset($options[1]) or !isset($options[2])) return;
        $party = $session->getParty();

        if($options[1]) {
            $event = new PartyLockEvent($party, $session);
            $event->call();
            if(!$event->isCancelled()) {
                $party->setPublic(true);
            }
        } else {
            $event = new PartyUnlockEvent($party, $session);
            $event->call();
            if(!$event->isCancelled()) {
                $party->setPublic(false);
            }
        }

        $event = new PartyUpdateSlotsEvent($party, $session, $options[2]);
        $event->call();
        if(!$event->isCancelled()) {
            $party->setSlots($options[2]);
        }
    }

}
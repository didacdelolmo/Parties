<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\event\PartyLockEvent;
use diduhless\parties\event\PartyUnlockEvent;
use diduhless\parties\event\PartyUpdateSlotsEvent;
use diduhless\parties\form\PartyCustomForm;

class PartyOptionsForm extends PartyCustomForm {

    public function onCreation(): void {
        $party = $this->getSession()->getParty();
        $slots = $party->getSlots();

        $this->setTitle("Party Options");
        $this->addLabel("Change the party options in this window.");
        $this->addToggle("Do you want to set your party locked?", $party->isLocked());
        $this->addSlider("Set your maximum party slots:    (0 = no limit)", 0, 8, $slots !== null ? $slots : 0);
    }

    public function setCallback(?array $options): void {
        $session = $this->getSession();
        if($options === null or !$session->hasParty() or !isset($options[1]) or !isset($options[2])) return;
        $party = $session->getParty();

        if($options[1]) {
            $event = new PartyLockEvent($party, $session);
            $event->call();
            if(!$event->isCancelled()) {
                $party->setLocked(true);
            }
        } else {
            $event = new PartyUnlockEvent($party, $session);
            $event->call();
            if(!$event->isCancelled()) {
                $party->setLocked(false);
            }
        }

        $event = new PartyUpdateSlotsEvent($party, $session, $options[2]);
        $event->call();
        if(!$event->isCancelled()) {
            $party->setSlots($options[2] === 1 ? null : $options[2]);
        }
    }

}
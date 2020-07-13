<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\event\PartySetPublicEvent;
use diduhless\parties\event\PartySetPrivateEvent;
use diduhless\parties\event\PartyUpdateSlotsEvent;
use diduhless\parties\form\PartyCustomForm;
use diduhless\parties\utils\ConfigGetter;

class PartyOptionsForm extends PartyCustomForm {

    public function onCreation(): void {
        $party = $this->getSession()->getParty();

        $this->setTitle("Party Options");
        $this->addLabel("Change the party options in this window.");
        $this->addToggle("Do you want to set your party public?", $party->isPublic());
        $this->addSlider("Set your maximum party slots", 1, ConfigGetter::getMaximumSlots(), 1, $party->getSlots());
    }

    public function setCallback(?array $options): void {
        $session = $this->getSession();
        if($options === null or !$session->hasParty() or !isset($options[1]) or !isset($options[2])) return;
        $party = $session->getParty();

        if($options[1]) {
            $event = new PartySetPublicEvent($party, $session);
            $event->call();
            if(!$event->isCancelled()) {
                $party->setPublic(true);
            }
        } else {
            $event = new PartySetPrivateEvent($party, $session);
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
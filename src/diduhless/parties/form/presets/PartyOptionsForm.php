<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\event\PartyLockEvent;
use diduhless\parties\event\PartyUnlockEvent;
use diduhless\parties\event\PartyUpdateSlotsEvent;
use diduhless\parties\form\PartyCustomForm;
use pocketmine\Player;

class PartyOptionsForm extends PartyCustomForm {

    public function onCreation(): void {
        $this->setTitle("Party Options");
        $this->addLabel("Change the party options in this window.");
        $this->addToggle("Do you want to set your party locked?");
        $this->addSlider("Set your maximum party slots (1 = none):", 1, 8);
    }

    public function setCallback(Player $player, ?array $options): void {
        $session = $this->getSession();
        if($options === null or !$session->hasParty()) return;
        $party = $session->getParty();

        if(isset($options[0])) {
            if($options[0]) {
                $event = new PartyLockEvent($party);
                $event->call();
                if(!$event->isCancelled()) {
                    $party->setLocked(true);
                    // Send a message
                }
            } else {
                $event = new PartyUnlockEvent($party);
                $event->call();
                if(!$event->isCancelled()) {
                    $party->setLocked(false);
                    // Send a message
                }
            }

        } elseif(isset($options[1])) {
            $event = new PartyUpdateSlotsEvent($party, $options[1]);
            $event->call();
            if(!$event->isCancelled()) {
                $party->setSlots($options[1] === 1 ? null : $options[1]);
                // Send a message
            }
        }
    }
}
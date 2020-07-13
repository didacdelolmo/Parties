<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\event\PartyJoinEvent;
use diduhless\parties\form\PartySimpleForm;
use diduhless\parties\party\Party;
use diduhless\parties\party\PartyFactory;

class PublicPartiesForm extends PartySimpleForm {

    /** @var Party[] */
    private $parties;

    public function onCreation(): void {
        $this->setTitle("Join a public party");

        $parties = PartyFactory::getParties();
        if(empty($parties)) {
            $this->setContent("There are no public parties to join! :(");
        } else {
            $this->setContent("Press on the party you want to join!");
        }
        foreach($parties as $party) {
            if(!$party->isLocked()) {
                $this->parties[] = $party;
                $this->addButton($party->getLeaderName() . "'s Party");
            }
        }
        $this->addButton("Go back");
    }

    public function setCallback(?int $result): void {
        if($result === null) return;
        $session = $this->getSession();

        if(empty($this->parties) and $result === 0 or !empty($this->parties) and $result === count($this->parties) + 1) {
            $session->openPartyForm();
            return;
        } elseif(!isset($this->parties[$result]) or !$this->parties[$result]->isFull()) {
            return;
        }
        $party = $this->parties[$result];

        $event = new PartyJoinEvent($party, $session);
        $event->call();
        if(!$event->isCancelled()) {
            $party->add($session);
        }
    }
}
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
    }

    public function setCallback(?int $result): void {
        if($result === null or !isset($this->parties[$result])) return;
        $party = $this->parties[$result];

        if(!$party->isFull()) return;
        $session = $this->getSession();

        $event = new PartyJoinEvent($party, $session);
        $event->call();
        if(!$event->isCancelled()) {
            $party->add($session);
        }
    }
}
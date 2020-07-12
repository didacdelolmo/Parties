<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\form\PartySimpleForm;
use diduhless\parties\party\Party;
use diduhless\parties\party\PartyFactory;

class PublicPartiesForm extends PartySimpleForm {

    /** @var Party[] */
    private $parties;

    public function onCreation(): void {
        $this->setTitle("Join a public party");

        foreach(PartyFactory::getParties() as $party) {
            if(!$party->isLocked()) {
                $this->parties[] = $party;
                $this->addButton($party->getLeaderName() . "'s Party");
            }
        }
    }

    public function setCallback(?int $result): void {
        if($result === null or !isset($this->parties[$result])) return;
        //
    }
}
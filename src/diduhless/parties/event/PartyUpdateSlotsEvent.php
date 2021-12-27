<?php


namespace diduhless\parties\event;


use diduhless\parties\party\Party;
use diduhless\parties\session\Session;

class PartyUpdateSlotsEvent extends PartyEvent {

    private int $slots;

    public function __construct(Party $party, Session $session, int $slots) {
        $this->slots = $slots;
        parent::__construct($party, $session);
    }

    public function getSlots(): ?int {
        return $this->slots;
    }

}
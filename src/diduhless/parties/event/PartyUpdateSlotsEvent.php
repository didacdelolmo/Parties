<?php


namespace diduhless\parties\event;


use diduhless\parties\party\Party;
use diduhless\parties\session\Session;

class PartyUpdateSlotsEvent extends PartyEvent {

    /** @var null|int */
    private $slots;

    public function __construct(Party $party, Session $session, ?int $slots) {
        $this->slots = $slots;
        parent::__construct($party, $session);
    }

    /**
     * If the slots are set to null, it means there is no limit.
     *
     * @return int|null
     */
    public function getSlots(): ?int {
        return $this->slots;
    }

}
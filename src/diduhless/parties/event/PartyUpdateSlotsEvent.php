<?php


namespace diduhless\parties\event;


use diduhless\parties\party\Party;

class PartyUpdateSlotsEvent extends PartyEvent {

    /** @var null|int */
    private $slots;

    public function __construct(Party $party, ?int $slots) {
        $this->slots = $slots;
        parent::__construct($party);
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
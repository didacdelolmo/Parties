<?php


namespace diduhless\parties\event;


use diduhless\parties\party\Party;
use pocketmine\event\Cancellable;
use pocketmine\event\Event;

abstract class PartyEvent extends Event implements Cancellable {

    /** @var Party */
    private $party;

    public function __construct(Party $party) {
        $this->party = $party;
    }

    public function getParty(): Party {
        return $this->party;
    }

}
<?php


namespace diduhless\parties\event;


use diduhless\parties\party\Party;
use diduhless\parties\session\Session;

class PartyInviteEvent extends PartyEvent {

    private Session $target;

    public function __construct(Party $party, Session $session, Session $target) {
        $this->target = $target;
        parent::__construct($party, $session);
    }

    public function getTarget(): Session {
        return $this->target;
    }

}
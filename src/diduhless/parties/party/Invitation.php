<?php

declare(strict_types=1);


namespace diduhless\parties\party;


use diduhless\parties\session\Session;

class Invitation {

    /** @var Session */
    private $sender;

    /** @var Session */
    private $target;

    /** @var string */
    private $partyId;

    public function __construct(Session $sender, Session $target, string $partyId) {
        $this->sender = $sender;
        $this->target = $target;
        $this->partyId = $partyId;
    }

}
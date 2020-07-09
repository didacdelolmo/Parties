<?php

declare(strict_types=1);


namespace diduhless\parties\party;


use diduhless\parties\session\Session;

class Party {

    /** @var string */
    private $id;

    /** @var Session[] */
    private $members;

    /** @var Session */
    private $leader;

    /** @var null|int */
    private $slots = null;

    /** @var bool */
    private $locked = false;

    public function __construct(string $id) {
        $this->id = $id;
    }

    public function getId(): string {
        return $this->id;
    }

}
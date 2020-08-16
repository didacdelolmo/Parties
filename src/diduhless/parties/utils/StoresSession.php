<?php


namespace diduhless\parties\utils;


use diduhless\parties\session\Session;

trait StoresSession {

    /** @var null|Session */
    protected $session = null;

    public function getSession(): ?Session {
        return $this->session;
    }

    public function setSession(?Session $session): void {
        $this->session = $session;
    }

}
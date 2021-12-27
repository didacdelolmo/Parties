<?php


namespace diduhless\parties\utils;


use diduhless\parties\session\Session;

trait StoresSession {

    protected ?Session $session = null;

    public function getSession(): ?Session {
        return $this->session;
    }

    public function setSession(?Session $session): void {
        $this->session = $session;
    }

}
<?php


namespace diduhless\parties\form;


use diduhless\parties\session\Session;
use jojoe77777\FormAPI\ModalForm;
use pocketmine\Player;

abstract class PartyModalForm extends ModalForm {

    /** @var Session */
    private $session;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct(function(Player $player, ?bool $result) {
            $this->setCallback($result);
        });
        $this->onCreation();
    }

    abstract public function onCreation(): void;

    abstract public function setCallback(?bool $result): void;

    public function getSession(): Session {
        return $this->session;
    }

}
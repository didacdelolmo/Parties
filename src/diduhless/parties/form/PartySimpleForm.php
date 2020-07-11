<?php


namespace diduhless\parties\form;


use diduhless\parties\session\Session;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;

abstract class PartySimpleForm extends SimpleForm {

    /** @var Session */
    private $session;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct(function(Player $player, ?int $result) {
            $this->setCallback($player, $result);
        });
        $this->onCreation();
    }

    abstract public function onCreation(): void;

    abstract public function setCallback(Player $player, ?int $result): void;

    public function getSession(): Session {
        return $this->session;
    }

}
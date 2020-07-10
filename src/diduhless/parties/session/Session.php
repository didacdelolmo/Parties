<?php

declare(strict_types=1);


namespace diduhless\parties\session;


use diduhless\parties\party\Invitation;
use diduhless\parties\party\Party;
use diduhless\parties\utils\Colors;
use pocketmine\Player;

class Session {

    /** @var Player */
    private $player;

    /** @var null|Party */
    private $party = null;

    /** @var Invitation[] */
    private $invitations = [];

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function getParty(): ?Party {
        return $this->party;
    }

    public function getInvitations(): array {
        return $this->invitations;
    }

    public function hasParty(): bool {
        return $this->party !== null;
    }

    public function setParty(?Party $party): void {
        $this->party = $party;
    }

    public function addInvitation(Invitation $invitation): void {
        $this->invitations[] = $invitation;
    }

    public function message(string $message): void {
        $this->getPlayer()->sendMessage(Colors::translate($message));
    }

}
<?php

declare(strict_types=1);


namespace diduhless\parties\session;


use diduhless\parties\form\presets\PartyMenuForm;
use diduhless\parties\form\presets\YourPartyForm;
use diduhless\parties\party\Invitation;
use diduhless\parties\party\Party;
use diduhless\parties\party\PartyItem;
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

    public function getUsername(): string {
        return $this->player->getName();
    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function getParty(): ?Party {
        return $this->party;
    }

    /**
     * Removes all the invitations that have passed more than the provided invitation length
     *
     * @return Invitation[]
     */
    public function getInvitations(): array {
        foreach($this->invitations as $time => $invitation) {
            if(microtime(true) - $time >= Invitation::INVITATION_LENGTH) {
                $this->removeInvitation($invitation);
            }
        }
        return $this->invitations;
    }

    public function hasInvitation(Invitation $invitation): bool {
        return in_array($invitation, $this->invitations, true);
    }

    public function hasParty(): bool {
        return $this->party !== null;
    }

    public function isPartyLeader(): bool {
        return $this->hasParty() ? $this->party->getLeaderName() === $this->getUsername() : false;
    }

    public function setParty(?Party $party): void {
        $this->party = $party;
    }

    public function addInvitation(Invitation $invitation): void {
        $this->invitations[microtime(true)] = $invitation;
    }

    public function removeInvitation(Invitation $invitation): void {
        if(!$this->hasInvitation($invitation)) {
            unset($this->invitations[array_search($invitation, $this->invitations, true)]);
        }
    }

    public function openPartyForm(): void {
        if($this->hasParty()) {
            $this->player->sendForm(new YourPartyForm($this));
        } else {
            $this->player->sendForm(new PartyMenuForm($this));
        }
    }

    public function givePartyItem(?int $slot = null): void {
        $inventory = $this->player->getInventory();
        $item = new PartyItem();
        if($slot !== null) {
            $inventory->setItem($slot, $item);
        } else {
            $inventory->addItem($item);
        }
    }

    public function message(string $message): void {
        $this->getPlayer()->sendMessage(Colors::translate($message));
    }

}
<?php

declare(strict_types=1);


namespace diduhless\parties\session;


use diduhless\parties\form\PartyMenuForm;
use diduhless\parties\form\YourPartyForm;
use diduhless\parties\party\Invitation;
use diduhless\parties\party\Party;
use diduhless\parties\party\PartyItem;
use diduhless\parties\utils\ColorUtils;
use pocketmine\player\Player;

class Session {

    private Player $player;

    private ?Party $party = null;
    private bool $party_chat = false;

    /** @var Invitation[] */
    private array $invitations = [];

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function getUsername(): string {
        return $this->player->getName();
    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function isOnline(): bool {
        return SessionFactory::hasSession($this->player);
    }

    public function getParty(): ?Party {
        return $this->party;
    }

    public function setParty(?Party $party): void {
        $this->party = $party;
    }

    public function hasParty(): bool {
        return $this->party !== null;
    }

    public function isPartyLeader(): bool {
        return $this->hasParty() and $this->party->getLeaderName() === $this->getUsername();
    }

    public function hasPartyChat(): bool {
        return $this->party_chat;
    }

    public function setPartyChat(bool $party_chat): void {
        $this->party_chat = $party_chat;
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

    public function hasSessionInvitation(Session $session): bool {
        foreach($this->getInvitations() as $invitation) {
            if($invitation->getSender()->getUsername() === $session->getUsername()) {
                return true;
            }
        }
        return false;
    }

    public function addInvitation(Invitation $invitation): void {
        $this->invitations[microtime(true)] = $invitation;
    }

    public function removeInvitation(Invitation $invitation): void {
        if($this->hasInvitation($invitation)) {
            unset($this->invitations[array_search($invitation, $this->invitations, true)]);
        }
    }

    public function removeInvitationsFromParty(Party $party): void {
        foreach($this->getInvitations() as $invitation) {
            if($invitation->getPartyId() === $party->getId()) {
                $this->removeInvitation($invitation);
            }
        }
    }

    public function openPartyForm(): void {
        $this->hasParty() ? $this->player->sendForm(new YourPartyForm($this)) : $this->player->sendForm(new PartyMenuForm($this));
    }

    public function givePartyItem(int $index): void {
        $this->getPlayer()->getInventory()->setItem($index, new PartyItem());
    }

    public function message(string $message): void {
        $this->getPlayer()->sendMessage(ColorUtils::translate($message));
    }

}
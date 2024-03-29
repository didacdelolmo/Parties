<?php

declare(strict_types=1);


namespace diduhless\parties\listener;


use diduhless\parties\event\PartyDisbandEvent;
use diduhless\parties\event\PartyLeaveEvent;
use diduhless\parties\party\Party;
use diduhless\parties\party\PartyFactory;
use diduhless\parties\session\Session;
use diduhless\parties\session\SessionFactory;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

class SessionListener implements Listener {

    /**
     * @param PlayerLoginEvent $event
     * @priority HIGHEST
     * @ignoreCancelled
     */
    public function onLogin(PlayerLoginEvent $event): void {
        SessionFactory::createSession($event->getPlayer());
    }

    /**
     * @param PlayerQuitEvent $event
     * @priority LOWEST
     */
    public function onQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        if(SessionFactory::hasSession($player)) {
            $this->checkForParty(SessionFactory::getSession($player));
        }

        SessionFactory::removeSession($player);
    }

    private function checkForParty(Session $session): void {
        if(!$session->hasParty()) {
            return;
        }

        $party = $session->getParty();
        if($session->isPartyLeader()) {
            $this->disbandParty($party);
        } else {
            $event = new PartyLeaveEvent($party, $session);
            $event->call();

            $party->remove($session);
        }
    }

    private function disbandParty(Party $party): void {
        $event = new PartyDisbandEvent($party, $party->getLeader());
        $event->call();

        foreach($party->getMembers() as $member) {
            $party->remove($member);
        }
        PartyFactory::removeParty($party);
    }

}
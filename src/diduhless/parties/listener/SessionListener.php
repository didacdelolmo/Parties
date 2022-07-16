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
            $session = SessionFactory::getSession($player);
            if($session->isPartyLeader()) {
                $this->disbandParty($session->getParty());
            }else {
                if($session->hasParty()) {
                    $this->removeFromParty($session->getParty(), $session);
                }
            }
        }
        SessionFactory::removeSession($player);
    }

    private function removeFromParty(Party $party, Session $session): void {
        $event = new PartyLeaveEvent($party, $session);
        $event->call();

        $session->getParty()->remove($session);
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
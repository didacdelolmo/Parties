<?php

declare(strict_types=1);


namespace diduhless\parties\listener;


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
        if(SessionFactory::hasSession($player = $event->getPlayer())) {
            SessionFactory::removeSession($player);
        }
    }

}
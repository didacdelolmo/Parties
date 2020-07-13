<?php


namespace diduhless\parties\listener;


use diduhless\parties\session\SessionFactory;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class PlayerJoinListener implements Listener {

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        if(SessionFactory::hasSession($player)) {
            SessionFactory::getSession($player)->givePartyItem(8);
        }
    }

}
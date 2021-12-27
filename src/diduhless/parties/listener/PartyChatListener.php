<?php


namespace diduhless\parties\listener;


use diduhless\parties\session\SessionFactory;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class PartyChatListener implements Listener {

    /**
     * @param PlayerChatEvent $event
     * @priority HIGHEST
     */
    public function onChat(PlayerChatEvent $event): void {
        $player = $event->getPlayer();
        if(!SessionFactory::hasSession($player)) {
            return;
        }

        $session = SessionFactory::getSession($player);
        if($session->hasPartyChat() and $session->hasParty()) {
            $session->getParty()->sendColoredMessage($session, $event->getMessage());

            $event->cancel();
        }
    }

}
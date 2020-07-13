<?php


namespace diduhless\parties\listener;


use diduhless\parties\event\PartyCreateEvent;
use pocketmine\event\Listener;

class PartyEventListener implements Listener {

    public function onCreate(PartyCreateEvent $event): void {
        $event->getSession()->message("{GREEN}You have created a party!");
    }

}
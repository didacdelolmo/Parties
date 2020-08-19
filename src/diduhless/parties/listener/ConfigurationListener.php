<?php


namespace diduhless\parties\listener;


use diduhless\parties\session\SessionFactory;
use diduhless\parties\utils\ConfigGetter;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerTransferEvent;
use pocketmine\Player;
use pocketmine\Server;

class ConfigurationListener implements Listener {

    public function onFight(EntityDamageByEntityEvent $event): void {
        $entity = $event->getEntity();
        $damager = $event->getDamager();

        if(!$entity instanceof Player or !$damager instanceof Player) {
            return;
        }
        $session = SessionFactory::getSession($damager);
        if(!$session->hasParty()) {
            return;
        }
        $party = $session->getParty();
        if(!$party->isPvp() and $party->hasMemberByName($entity->getName())) {
            $event->setCancelled();
        }
    }

    public function onLevelChange(EntityLevelChangeEvent $event): void {
        $entity = $event->getEntity();
        if(!$entity instanceof Player) {
            return;
        }
        $session = SessionFactory::getSession($entity);
        if(!$session->isPartyLeader()) {
            return;
        }
        $party = $session->getParty();
        if($party->isLeaderWorldTeleport()) {
            foreach($party->getMembers() as $member) {
                if(!$member->isPartyLeader()) {
                    $member->getPlayer()->teleport($event->getTarget()->getSafeSpawn());
                }
            }
        }
    }

    public function onTransfer(PlayerTransferEvent $event): void {
        if(!ConfigGetter::isTransferTeleportEnabled()) {
            return;
        }
        $session = SessionFactory::getSession($event->getPlayer());
        if($session->isPartyLeader()) {
            return;
        }
        foreach($session->getParty()->getMembers() as $member) {
            if(!$member->isPartyLeader()) {
                $member->getPlayer()->transfer($event->getAddress(), $event->getPort(), $event->getMessage());
            }
        }
    }

    public function onCommandPreprocess(PlayerCommandPreprocessEvent $event): void {
        if(!ConfigGetter::areLeaderCommandsEnabled()) {
            return;
        }
        $command = str_replace("/", "", $event->getMessage());
        if(!in_array($command, ConfigGetter::getSelectedCommands())) {
            return;
        }
        $session = SessionFactory::getSession($event->getPlayer());
        if($session->isPartyLeader()) {
            foreach($session->getParty()->getMembers() as $member) {
                if(!$member->isPartyLeader()) {
                    Server::getInstance()->dispatchCommand($member->getPlayer(), $command);
                }
            }
        }
    }

    public function onJoin(PlayerJoinEvent $event): void {
        if(ConfigGetter::isPartyItemEnabled()) {
            SessionFactory::getSession($event->getPlayer())->givePartyItem(ConfigGetter::getPartyItemIndex());
        }
    }

    public function onTransaction(InventoryTransactionEvent $event): void {
        if(ConfigGetter::isPartyItemFixed()) {
            foreach($event->getTransaction()->getActions() as $action) {
                if($action->getSourceItem()->getNamedTag()->hasTag("parties")) {
                    $event->setCancelled();
                }
            }
        }
    }

}
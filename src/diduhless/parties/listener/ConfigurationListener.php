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

        if(ConfigGetter::isPvpDisabled() and $entity instanceof Player and $damager instanceof Player and SessionFactory::hasSession($damager)) {
            $session = SessionFactory::getSession($damager);

            if($session->hasParty() and $session->getParty()->hasMemberByName($entity->getName())) {
                $event->setCancelled();
            }
        }
    }

    public function onLevelChange(EntityLevelChangeEvent $event): void {
        $player = $event->getEntity();
        if(ConfigGetter::isWorldTeleportEnabled() and $player instanceof Player and SessionFactory::hasSession($player)) {
            $session = SessionFactory::getSession($player);

            if($session->isPartyLeader()) {
                foreach($session->getParty()->getMembers() as $member) {
                    if(!$member->isPartyLeader()) {
                        $member->getPlayer()->teleport($event->getTarget()->getSafeSpawn());
                    }
                }
            }
        }
    }

    public function onTransfer(PlayerTransferEvent $event): void {
        $player = $event->getPlayer();
        if(ConfigGetter::isTransferTeleportEnabled() and SessionFactory::hasSession($player)) {
            $session = SessionFactory::getSession($player);

            if($session->isPartyLeader()) {
                foreach($session->getParty()->getMembers() as $member) {
                    if(!$member->isPartyLeader()) {
                        $member->getPlayer()->transfer($event->getAddress(), $event->getPort(), $event->getMessage());
                    }
                }
            }
        }
    }

    public function onCommandPreprocess(PlayerCommandPreprocessEvent $event): void {
        $player = $event->getPlayer();
        $commandLine = str_replace("/", "", $event->getMessage());

        if(ConfigGetter::areLeaderCommandsEnabled() and in_array($commandLine, ConfigGetter::getSelectedCommands()) and SessionFactory::hasSession($player)) {
            $session = SessionFactory::getSession($player);

            if($session->isPartyLeader()) {
                foreach($session->getParty()->getMembers() as $member) {
                    if(!$member->isPartyLeader()) {
                        Server::getInstance()->dispatchCommand($member->getPlayer(), $commandLine);
                    }
                }
            }
        }
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        if(ConfigGetter::isPartyItemEnabled() and SessionFactory::hasSession($player)) {
            SessionFactory::getSession($player)->givePartyItem(ConfigGetter::getPartyItemIndex());
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
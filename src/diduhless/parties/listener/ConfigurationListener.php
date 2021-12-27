<?php


namespace diduhless\parties\listener;


use diduhless\parties\session\SessionFactory;
use diduhless\parties\utils\ConfigGetter;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerTransferEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\World;

class ConfigurationListener implements Listener {

    public function onFight(EntityDamageByEntityEvent $event): void {
        $entity = $event->getEntity();
        $damager = $event->getDamager();

        if(!$entity instanceof Player or !$damager instanceof Player or !SessionFactory::hasSession($entity)) {
            return;
        }
        $session = SessionFactory::getSession($damager);
        if(!$session->hasParty()) {
            return;
        }
        $party = $session->getParty();
        if(!$party->isPvp() and $party->hasMemberByName($entity->getName())) {
            $event->cancel();
        }
    }

    public function onLevelChange(EntityTeleportEvent $event): void {
        $world = $event->getTo()->getWorld();
        if($event->getFrom()->getWorld()->getFolderName() === $world->getFolderName()) {
            return;
        }

        $entity = $event->getEntity();
        if(!$entity instanceof Player or !SessionFactory::hasSession($entity)) {
            return;
        }
        $this->checkPartyItem($entity, $world);
        $session = SessionFactory::getSession($entity);
        if(!$session->isPartyLeader()) {
            return;
        }
        $party = $session->getParty();
        if($party->isLeaderWorldTeleport()) {
            foreach($party->getMembers() as $member) {
                if(!$member->isPartyLeader()) {
                    $member->getPlayer()->teleport($world->getSafeSpawn());
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

    public function onTransaction(InventoryTransactionEvent $event): void {
        if(ConfigGetter::isPartyItemFixed()) {
            foreach($event->getTransaction()->getActions() as $action) {
                if($action->getSourceItem()->getNamedTag()->getTag("parties") !== null) {
                    $event->cancel();
                }
            }
        }
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $this->checkPartyItem($player, $player->getWorld());
    }

    public function onRespawn(PlayerRespawnEvent $event): void {
        $player = $event->getPlayer();
        $this->checkPartyItem($player, $event->getRespawnPosition()->getWorld());
    }

    private function checkPartyItem(Player $player, World $world): void {
        if(ConfigGetter::isPartyItemEnabled() and SessionFactory::hasSession($player) and in_array($world->getFolderName(), ConfigGetter::getPartyItemWorldNames())) {
            SessionFactory::getSession($player)->givePartyItem(ConfigGetter::getPartyItemIndex());
        }
    }

}
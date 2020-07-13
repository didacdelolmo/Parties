<?php


namespace diduhless\parties\listener;


use diduhless\parties\event\PartyCreateEvent;
use diduhless\parties\event\PartyDisbandEvent;
use diduhless\parties\event\PartyInviteEvent;
use diduhless\parties\event\PartyJoinEvent;
use diduhless\parties\event\PartyLeaderPromoteEvent;
use diduhless\parties\event\PartyLeaveEvent;
use diduhless\parties\event\PartyMemberKickEvent;
use diduhless\parties\event\PartySetPrivateEvent;
use diduhless\parties\event\PartySetPublicEvent;
use diduhless\parties\event\PartyUpdateSlotsEvent;
use pocketmine\event\Listener;

class PartyEventListener implements Listener {

    public function onCreate(PartyCreateEvent $event): void {
        $event->getSession()->message("{GREEN}You have created a party!");
    }

    public function onDisband(PartyDisbandEvent $event): void {
        $session = $event->getSession();
        $party = $event->getParty();

        $session->message("{RED}You have disbanded the party!");
        $party->message("{RED}The party has been disbanded because {WHITE}" . $party->getLeaderName() . " {RED}left the party.", $session);
    }

    public function onInvite(PartyInviteEvent $event): void {
        $session = $event->getSession();
        $target = $event->getTarget();

        $targetName = $target->getUsername();

        $session->message("{GREEN}You have invited {WHITE}$targetName {GREEN}to the party! He has got 1 minute to accept the invitation.");
        $target->message("{GREEN}You have received an invitation to join {WHITE}" . $session->getUsername() . "{GREEN}'s party!");
        $event->getParty()->message("$targetName {GREEN}has been invited to the party!", $session);
    }

    public function onJoin(PartyJoinEvent $event): void {
        $session = $event->getSession();
        $party = $event->getParty();

        $session->message("{GREEN}You have joined {WHITE}" . $party->getLeaderName() . "{GREEN}'s party!");
        $party->message($session->getUsername() . " {GREEN}has joined the party!");
    }

    public function onLeaderPromote(PartyLeaderPromoteEvent $event): void {
        $session = $event->getSession();
        $newLeader = $event->getNewLeader();
        $party = $event->getParty();

        $sessionName = $session->getUsername();
        $newLeaderName = $newLeader->getUsername();

        $session->message("{GREEN}You have promoted {WHITE}$newLeaderName {GREEN}to party leader!");
        $newLeader->message("{GREEN}You have been promoted by {WHITE}$sessionName {GREEN}to party leader!");
        $party->message("$sessionName {GREEN}has promoted {WHITE}$newLeaderName {WHITE}to party leader!", $session);
    }

    public function onLeave(PartyLeaveEvent $event): void {
        $session = $event->getSession();
        $party = $event->getParty();

        $session->message("{RED}You have left {WHITE}" . $party->getLeaderName() . "{RED}'s party!");
        $party->message($session->getUsername() . " {RED}has left the party!", $session);
    }

    public function onMemberKick(PartyMemberKickEvent $event): void {
        $member = $event->getMember();

        $member->message("{RED}You have been kicked from {WHITE}" . $event->getSession()->getUsername() . "{RED}'s party!");
        $event->getParty()->message($member->getUsername() . " {RED}has been kicked from the party!", $member);
    }

    public function onLock(PartySetPrivateEvent $event): void {
        $event->getParty()->message("{GREEN}The party has been set to {WHITE}private{GREEN}!");
    }

    public function onUnlock(PartySetPublicEvent $event): void {
        $event->getParty()->message("{GREEN}The party has been set to {WHITE}public{GREEN}!");
    }

    public function onUpdateSlots(PartyUpdateSlotsEvent $event): void {
        $event->getParty()->message("{GREEN}The party slots have been set to {WHITE}" . $event->getSlots() . "{GREEN}!");
    }

}
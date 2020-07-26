Download the plugin on poggit: [![Poggit](https://poggit.pmmp.io/shield.state/Parties)](https://poggit.pmmp.io/p/Parties) <br>
Join my GitHub discord server: [![Discord](https://img.shields.io/discord/732039634745425972?color=%2392e5fc&label=discord)](https://discord.gg/bsFSwTR)

# Parties
🎉 A parties plugin for PocketMine-MP servers<br><br>

## Available features

- A complete parties API for developers (with custom **events**)
- Usage of **forms** to manage the parties
- Customizable party options:
  - Set the maximum party slots
  - **Disable the pvp** (player versus player) within the members of the party.
  - Teleport the party members to the party leader when the leader gets teleported to a different world.
  - Teleport the party members to the party leader when the leader gets **transfered** to another **server**.
  - Make all the party members execute the same command as the party leader.

## In-game pictures

![Party Menu](https://i.imgur.com/r3KWqoD.png) ![Your Party](https://i.imgur.com/1nIpVEu.png)
![Party Member](https://i.imgur.com/kShbHCY.png) ![Party Options](https://i.imgur.com/FT24li1.png)
![Invite a player](https://i.imgur.com/W00fnSz.png) ![Party Chat](https://i.imgur.com/bAqQ0PP.png) 

## Commands

- /party — Opens the party form
- /party (message) — Sends a message to the party chat

## Code examples

Setting the gamemode to spectator to all the members of the party when the party leader invites a player to join their party:
```php
public function onPartyInvite(PartyInviteEvent $event): void {
    $session = $event->getSession();
    if($session->isPartyLeader()) {
        foreach($session->getParty()->getMembers() as $member) {
            $member->getPlayer()->setGamemode(Player::SPECTATOR);
        }
    }
} 
```

Allow only players with the permission 'slots.limit' to set the maximum slots to more than 3:
```php
public function onUpdateSlots(PartyUpdateSlotsEvent $event): void {
    $session = $event->getSession();
    if(!$session->getPlayer()->hasPermission("slots.limit") and $event->getSlots() > 3) {
        $event->setCancelled();
        $session->message("{RED}You do not have permissions to set the maximum slots to more than 3!");
    }
}
```

Setting the party public when an operator joins the party:
```php
public function onPartyJoin(PartyJoinEvent $event): void {
    if($event->getSession()->getPlayer()->isOp()) {
        $event->getParty()->setPublic(true);
    }
}
```




<?php

declare(strict_types=1);


namespace diduhless\parties\session;



use pocketmine\Player;

class SessionFactory {

    /** @var Session[] */
    static private $sessions = [];

    static public function getSessionByName(string $username): ?Session {
        return self::$sessions[$username] ?? null;
    }

    static public function getSession(Player $player): ?Session {
        return self::getSessionByName($player->getName()) ?? null;
    }

    static public function hasSession(Player $player): bool {
        return array_key_exists($player->getName(), self::$sessions);
    }

    static public function createSession(Player $player): void {
        $username = $player->getName();
        if(self::hasSession($player)) {
           throw new \InvalidArgumentException("Can't open a session for $username because they already have one");
        } else {
            self::$sessions[$username] = $player;
        }
    }

    static public function removeSession(Player $player): void {
        $username = $player->getName();
        if(self::hasSession($player)) {
            unset(self::$sessions[$username]);
        } else {
            throw new \InvalidArgumentException("Can't remove $username's session because they don't have an open session");
        }
    }

}
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
        if(!self::hasSession($player)) {
            self::$sessions[$player->getName()] = new Session($player);
        }
    }

    static public function removeSession(Player $player): void {
        if(self::hasSession($player)) {
            unset(self::$sessions[$player->getName()]);
        }
    }

}
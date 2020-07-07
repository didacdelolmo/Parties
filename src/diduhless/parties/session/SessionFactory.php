<?php

declare(strict_types=1);


namespace diduhless\parties\session;



use pocketmine\Player;

class SessionFactory {

    /** @var Session[] */
    static private $sessions = [];

    static public function getSession(Player $player): ?Session {
        return self::getSessionByName($player->getName()) ?? null;
    }

    static public function getSessionByName(string $username): ?Session {
        return self::$sessions[$username] ?? null;
    }

    static public function createSession(Player $player): void {
        self::$sessions[$player->getName()] = $player;
    }

    static public function removeSession(Player $player): void {
        unset(self::$sessions[$player->getName()]);
    }
}
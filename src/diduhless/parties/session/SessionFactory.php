<?php

declare(strict_types=1);


namespace diduhless\parties\session;


use pocketmine\player\Player;

class SessionFactory {

    /** @var Session[] */
    static private array $sessions = [];

    /**
     * @return Session[]
     */
    static public function getSessions(): array {
        return self::$sessions;
    }

    static public function getSessionByName(string $username): ?Session {
        return self::$sessions[strtolower($username)] ?? null;
    }

    static public function getSession(Player $player): ?Session {
        return self::getSessionByName(strtolower($player->getName())) ?? null;
    }

    static public function hasSessionByName(string $username): bool {
        return array_key_exists(strtolower($username), self::$sessions);
    }

    static public function hasSession(Player $player): bool {
        return self::hasSessionByName($player->getName());
    }

    static public function createSession(Player $player): void {
        if(!self::hasSession($player)) {
            self::$sessions[strtolower($player->getName())] = new Session($player);
        }
    }

    static public function removeSession(Player $player): void {
        if(self::hasSession($player)) {
            unset(self::$sessions[strtolower($player->getName())]);
        }
    }

}
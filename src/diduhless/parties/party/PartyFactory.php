<?php

declare(strict_types=1);


namespace diduhless\parties\party;


class PartyFactory {

    /** @var Party[] */
    static private array $parties = [];

    /**
     * @return Party[]
     */
    public static function getParties(): array {
        return self::$parties;
    }

    static public function getParty(string $id): ?Party {
        return self::$parties[$id];
    }

    static public function existsParty(Party $party): bool {
        return array_key_exists($party->getId(), self::$parties);
    }

    static public function addParty(Party $party): void {
        if(!self::existsParty($party)) {
            self::$parties[$party->getId()] = $party;
        }
    }

    static public function removeParty(Party $party): void {
        if(self::existsParty($party)) {
            unset(self::$parties[$party->getId()]);
        }
    }

}
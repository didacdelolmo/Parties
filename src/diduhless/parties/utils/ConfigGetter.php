<?php


namespace diduhless\parties\utils;


use diduhless\parties\Parties;

class ConfigGetter {

    /**
     * @param string $key
     * @return bool|mixed
     */
    static public function get(string $key) {
        return Parties::getInstance()->getConfig()->get($key);
    }

    static public function getMaximumSlots(): int {
        return self::get("maximum-party-slots");
    }

    static public function isPvpDisabled(): bool {
        return self::get("disable-pvp-with-party-members");
    }

    static public function isWorldTeleportEnabled(): bool {
        return self::get("teleport-members-to-leader-on-change-world");
    }

    static public function isTransferTeleportEnabled(): bool {
        return self::get("teleport-members-to-leader-on-transfer-server");
    }

}
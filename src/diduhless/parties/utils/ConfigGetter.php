<?php


namespace diduhless\parties\utils;


use diduhless\parties\Parties;

class ConfigGetter {

    /**
     * @param string $key
     * @return mixed
     */
    static public function get(string $key): mixed {
        return Parties::getInstance()->getConfig()->get($key);
    }

    static public function getMaximumSlots(): int {
        return self::get("maximum-party-slots");
    }

    static public function isPvpDisabledOption(): bool {
        return self::get("show-disable-pvp-with-party-members-option");
    }

    static public function isPvpDisabled(): bool {
        return self::get("default-disable-pvp-with-party-members");
    }

    static public function isWorldTeleportOption(): bool {
        return self::get("show-teleport-members-to-leader-on-change-world-option");
    }

    static public function isWorldTeleportEnabled(): bool {
        return self::get("default-teleport-members-to-leader-on-change-world");
    }

    static public function isTransferTeleportEnabled(): bool {
        return self::get("teleport-members-to-leader-on-transfer-server");
    }

    static public function areLeaderCommandsEnabled(): bool {
        return self::get("enable-leader-commands");
    }

    static public function getSelectedCommands(): array {
        return self::get("selected-leader-commands");
    }

    static public function isPartyItemEnabled(): bool {
        return self::get("give-party-item");
    }

    static public function isPartyItemFixed(): bool {
        return self::get("fix-party-item");
    }

    static public function getPartyItemIndex(): int {
        return self::get("party-item-index");
    }

    static public function getPartyItemWorldNames(): array {
        return self::get("party-item-worlds");
    }

    static public function getPartyItemValues(): array {
        return self::get("party-item");
    }

}
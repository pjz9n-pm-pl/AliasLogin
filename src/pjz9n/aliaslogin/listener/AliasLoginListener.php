<?php

/**
 * Copyright (c) 2020 PJZ9n.
 *
 * This file is part of AliasLogin.
 *
 * AliasLogin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * AliasLogin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with AliasLogin. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace pjz9n\aliaslogin\listener;

use pjz9n\aliaslogin\flag\AliasLoginFlag;
use pjz9n\aliaslogin\language\LanguageHolder;
use pjz9n\aliaslogin\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\nbt\tag\NamedTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\utils\TextFormat;

class AliasLoginListener implements Listener
{
    /** @var NamedTag[]|null[] */
    private $xuids = [];

    public function changeToAliasOnLogin(DataPacketReceiveEvent $event): void
    {
        $packet = $event->getPacket();
        if ($packet instanceof LoginPacket) {
            if (($alias = AliasLoginFlag::get($packet->clientId)) === null) {
                return;
            }
            $packet->username = $alias;
        }
    }

    public function xuidDoesNotMatchKickCancel(PlayerKickEvent $event): void
    {
        $player = $event->getPlayer();
        if (AliasLoginFlag::get($player->getClientId()) !== null) {
            //エイリアスによるログイン
            if ($event->getReason() === "XUID does not match (possible impersonation attempt)") {
                $event->setCancelled();
                Main::getInstance()->getLogger()->notice(LanguageHolder::get()->translateString("logger.xuid.notmatch"));
            }
        }
    }

    public function sendState(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        if (AliasLoginFlag::get($player->getClientId()) !== null) {
            $player->sendMessage(TextFormat::GREEN . LanguageHolder::get()->translateString("aliaslogin.join"));
        }
    }

    public function saveXuid(PlayerPreLoginEvent $event): void
    {
        $player = $event->getPlayer();
        if ((AliasLoginFlag::get($player->getClientId())) !== null) {
            //エイリアスによるログイン
            $tag = $player->namedtag->getTag("LastKnownXUID");
            $this->xuids[$player->getClientId()] = $tag;
            Main::getInstance()->getLogger()->debug("Save the LastKnownXUID: " . ($tag instanceof StringTag ? $tag->getValue() : "null"));
        }
    }

    public function restoreXuid(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();
        if (isset($this->xuids[$player->getClientId()])) {
            $tag = $this->xuids[$player->getClientId()];
            $player->namedtag->setTag($tag, true);
            Main::getInstance()->getLogger()->debug("Restore the LastKnownXUID: " . $tag->getValue());
        }
    }
}

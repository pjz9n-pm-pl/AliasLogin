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

namespace pjz9n\aliaslogin;

use CortexPE\Commando\exception\HookAlreadyRegistered;
use CortexPE\Commando\PacketHooker;
use pjz9n\aliaslogin\language\LanguageHolder;
use pocketmine\lang\BaseLang;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{
    /**
     * @throws HookAlreadyRegistered
     */
    public function onEnable(): void
    {
        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }

        new Config($this->getDataFolder() . "config.yml", Config::YAML, [
            "language" => "default",
        ]);

        $languageCode = ($configLanguage = $this->getConfig()->get("language", "default")) === "default"
            ? $this->getServer()->getLanguage()->getLang()
            : $configLanguage;
        $localePath = $this->getFile() . "resources/locale/";
        $language = new BaseLang($languageCode, $localePath);
        LanguageHolder::set($language);
        $this->getLogger()->info($language->translateString("language.selected", [
            $language->getName(),
            $language->getLang(),
        ]));
    }
}

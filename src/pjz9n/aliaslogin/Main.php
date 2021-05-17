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
use pjz9n\aliaslogin\command\AliasLoginCommand;
use pjz9n\aliaslogin\flag\AliasLoginFlag;
use pjz9n\aliaslogin\language\LanguageHolder;
use pjz9n\aliaslogin\listener\AliasLoginListener;
use pocketmine\lang\BaseLang;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{
    /** @var self */
    private static $instance;

    public static function getInstance(): self
    {
        return self::$instance;
    }

    /** @var Config */
    private $aliasLoginFlagConfig;

    public function onLoad(): void
    {
        self::$instance = $this;
    }

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
            "only-console" => true,
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
        $this->aliasLoginFlagConfig = new Config($this->getDataFolder() . "alias-login-flag.json");
        AliasLoginFlag::fromArray($this->aliasLoginFlagConfig->getAll());
        $this->getServer()->getPluginManager()->registerEvents(new AliasLoginListener(), $this);
        $this->getServer()->getCommandMap()->register($this->getName(), new AliasLoginCommand($this));
    }

    public function onDisable(): void
    {
        $this->aliasLoginFlagConfig->setAll(AliasLoginFlag::toArray());
        $this->aliasLoginFlagConfig->save();
    }
}

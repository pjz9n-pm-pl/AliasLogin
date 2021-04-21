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

namespace pjz9n\aliaslogin\command\sub;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pjz9n\aliaslogin\flag\AliasLoginFlag;
use pjz9n\aliaslogin\language\LanguageHolder;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class AliasLoginCancelCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct(
            "cancel",
            LanguageHolder::get()->translateString("command.aliaslogin.cancel.description")
        );
    }

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void
    {
        $this->setPermission("aliaslogin.command.aliaslogin.cancel");
        $this->registerArgument(0, new RawStringArgument("target", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (isset($args["target"])) {
            $target = Server::getInstance()->getPlayer($args["target"]);
            if ($target === null) {
                $sender->sendMessage(TextFormat::RED . LanguageHolder::get()->translateString("target.player.notexist"));
                return;
            }
        } else if ($sender instanceof Player) {
            $target = $sender;
        } else {
            $sender->sendMessage(TextFormat::RED . LanguageHolder::get()->translateString("must.input.target"));
            return;
        }
        if (AliasLoginFlag::get($target->getClientId()) === null) {
            $sender->sendMessage(TextFormat::RED . LanguageHolder::get()->translateString("aliaslogin.noset"));
            return;
        }
        AliasLoginFlag::remove($target->getClientId());
        $sender->sendMessage(TextFormat::GREEN . LanguageHolder::get()->translateString("aliaslogin.cancel"));
    }
}

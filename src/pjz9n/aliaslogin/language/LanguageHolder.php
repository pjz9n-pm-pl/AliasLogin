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

namespace pjz9n\aliaslogin\language;

use InvalidStateException;
use pocketmine\lang\BaseLang;

final class LanguageHolder
{
    /** @var BaseLang */
    private static $language = null;

    public static function get(): BaseLang
    {
        if (self::$language === null) {
            throw new InvalidStateException("Language is not set");
        }
        return self::$language;
    }

    public static function set(BaseLang $language): void
    {
        self::$language = $language;
    }

    private function __construct()
    {
        //
    }
}

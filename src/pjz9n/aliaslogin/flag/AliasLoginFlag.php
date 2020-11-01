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

namespace pjz9n\aliaslogin\flag;

final class AliasLoginFlag
{
    /** @var string[] */
    private static $flags = [];

    public static function get(int $clientId): ?string
    {
        return self::$flags[$clientId] ?? null;
    }

    public static function set(int $clientId, string $alias): void
    {
        self::$flags[$clientId] = $alias;
    }

    public static function remove(int $clientId): void
    {
        unset(self::$flags[$clientId]);
    }

    public static function toArray(): array
    {
        return self::$flags;
    }

    public static function fromArray(array $array): void
    {
        self::$flags = $array;
    }

    private function __construct()
    {
        //
    }
}

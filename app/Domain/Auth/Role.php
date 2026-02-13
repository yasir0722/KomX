<?php

namespace App\Domain\Auth;

/**
 * Enum representing user roles.
 */
enum Role: string
{
    case Admin = 'admin';
    case Committee = 'committee';
    case Member = 'member';

    /**
     * Get a human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Committee => 'Committee Member',
            self::Member => 'Member',
        };
    }

    /**
     * Get all role values as an array.
     *
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

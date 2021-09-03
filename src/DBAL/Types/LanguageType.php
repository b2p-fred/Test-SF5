<?php

namespace App\DBAL\Types;

/**
 * The LanguageType class defines the allowed languages:
 * - simple for a normal site's contact
 * - responsible for the site's responsible
 * - emergency for an emergency contact of a site
 * - visitor for a visitor of a site (mobile access).
 */
class LanguageType extends BaseEnumType
{
    public const LANG_ALL = 'all';
    public const LANG_UNKNOWN = '';
    public const LANG_FRENCH = 'fr-FR';
    public const LANG_ENGLISH = 'en-GB';
    public const LANG_SPANISH = 'es-ES';
    public const LANG_ITALIAN = 'it-IT';

    public const LANG_DEFAULT = self::LANG_FRENCH;

    protected string $name = 'language_type';

    protected array $values = [
        self::LANG_UNKNOWN,
        self::LANG_ALL,
        self::LANG_FRENCH,
        self::LANG_ENGLISH,
        self::LANG_SPANISH,
        self::LANG_ITALIAN,
    ];
}

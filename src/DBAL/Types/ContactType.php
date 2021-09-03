<?php

namespace App\DBAL\Types;

/**
 * The ContactType class defines the allowed Contact types:
 * - simple for a normal site's contact
 * - responsible for the site's responsible
 * - emergency for an emergency contact of a site
 * - visitor for a visitor of a site (mobile access).
 */
class ContactType extends BaseEnumType
{
    public const CONTACT = 'simple';
    public const CONTACT_EMERGENCY = 'emergency';
    public const CONTACT_RESPONSIBLE = 'responsible';
    public const CONTACT_VISITOR = 'visitor';

    protected string $name = 'enum_contact_type';

    protected array $values = [
        self::CONTACT,
        self::CONTACT_EMERGENCY,
        self::CONTACT_RESPONSIBLE,
        self::CONTACT_VISITOR,
    ];
}

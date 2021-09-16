<?php

namespace App\DBAL\Types;

/**
 * The RelationType class defines the allowed Relation types:
 * - 'created' for a freshly created relation
 * - 'sent' for a document which just got sent
 * - 'approved' for a document approved by the B-party.
 * - 'refused' for a document refused by the B-party.
 */
class RelationType extends BaseEnumType
{
    public const RELATION_CREATED = 'created';
    public const RELATION_SENT = 'sent';
    public const RELATION_APPROVED = 'approved';
    public const RELATION_REFUSED = 'refused';

    protected string $name = 'enum_relation_type';

    protected array $values = [
        self::RELATION_CREATED,
        self::RELATION_SENT,
        self::RELATION_APPROVED,
        self::RELATION_REFUSED,
    ];
}

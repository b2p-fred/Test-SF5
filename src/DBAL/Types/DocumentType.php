<?php

namespace App\DBAL\Types;

/**
 * The DocumentType class defines the allowed Document types:
 * - 'protocol' for a security protocole
 * - 'annex' for an annex document.
 */
class DocumentType extends BaseEnumType
{
    public const DOCUMENT_PROTOCOL = 'protocol';
    public const DOCUMENT_ANNEX = 'annex';

    protected string $name = 'enum_document_type';

    protected array $values = [
        self::DOCUMENT_PROTOCOL,
        self::DOCUMENT_ANNEX,
    ];
}

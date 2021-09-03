<?php

namespace App\DBAL\Types;

/**
 * The DocumentType class defines the allowed Document types:
 * - 'main' for the main document in a security protocole
 * - 'annex' for an annex document.
 */
class DocumentType extends BaseEnumType
{
    public const DOCUMENT_MAIN = 'main';
    public const DOCUMENT_ANNEX = 'annex';

    protected string $name = 'enum_document_type';

    protected array $values = [
        self::DOCUMENT_MAIN,
        self::DOCUMENT_ANNEX,
    ];
}

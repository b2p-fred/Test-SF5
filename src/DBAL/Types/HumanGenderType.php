<?php

namespace App\DBAL\Types;

class HumanGenderType extends BaseEnumType
{
    public const GENDER_MALE = 'male';
    public const GENDER_FEMALE = 'female';
    public const GENDER_OTHER = 'other';
    public const GENDER_UNKNOWN = 'unknown';

    protected string $name = 'enum_human_gender';

    protected array $values = [
        self::GENDER_MALE,
        self::GENDER_FEMALE,
        self::GENDER_OTHER,
        self::GENDER_UNKNOWN,
    ];
}

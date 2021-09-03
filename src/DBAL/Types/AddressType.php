<?php

namespace App\DBAL\Types;

/**
 * The AddressType class defines the allowed Address types:
 * - 'main' for the main site address
 * - 'vehicle' for the address to use with a vehicle.
 */
class AddressType extends BaseEnumType
{
    public const ADDRESS_MAIN = 'main';
    public const ADDRESS_VEHICLE = 'vehicle';

    protected string $name = 'enum_address_type';

    protected array $values = [
        self::ADDRESS_MAIN,
        self::ADDRESS_VEHICLE,
    ];
}

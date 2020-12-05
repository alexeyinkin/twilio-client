<?php
declare(strict_types=1);

namespace TwilioClient\Entity;

use JsonSerializable;

class Location implements JsonSerializable
{
    public const TABLE = 'location';

    private int $locationId;
    private int $building;
    private string $street;

    /**
     * @param $assoc array<int, string>
     */
    public static function denormalize(array $assoc): self
    {
        $obj = new self();

        $obj->locationId    = (int) $assoc['location_id'];
        $obj->building      = (int) $assoc['building'];
        $obj->street        = $assoc['street'];

        return $obj;
    }

    /**
     * @return array<string, int|string>
     */
    public function jsonSerialize(): array
    {
        return [
            'locationId'    => $this->locationId,
            'building'      => $this->building,
            'street'        => $this->street,
        ];
    }
}

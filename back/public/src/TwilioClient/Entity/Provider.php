<?php
declare(strict_types=1);

namespace TwilioClient\Entity;

use DateTimeImmutable;
use JsonSerializable;
use TwilioClient\Enum\DateFormatEnum;

class Provider implements JsonSerializable
{
    public const TABLE = 'provider';

    private int $providerId;
    private string $name;
    private Location $location;
    private string $status;
    private DateTimeImmutable $notificationDatetime;

    /**
     * @param $assocs array<int, array<int, string>>
     */
    public static function denormalizeArray(array $assocs): array
    {
        $result = [];

        foreach ($assocs as $assoc) {
            $result[] = self::denormalize($assoc);
        }

        return $result;
    }

    /**
     * @param $assoc array<int, string>
     */
    public static function denormalize(array $assoc): self
    {
        $obj = new self();

        $obj->providerId            = (int) $assoc['provider_id'];
        $obj->name                  = $assoc['name'];
        $obj->location              = Location::denormalize($assoc);
        $obj->status                = $assoc['status'];
        $obj->notificationDatetime  = new DateTimeImmutable($assoc['notification_datetime']);

        return $obj;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, int|string>
     */
    public function jsonSerialize(): array
    {
        return [
            'providerId'            => $this->providerId,
            'name'                  => $this->name,
            'location'              => $this->location->jsonSerialize(),
            'status'                => $this->status,
            'notificationDatetime'  => $this->notificationDatetime->format(DateFormatEnum::DB),
        ];
    }
}

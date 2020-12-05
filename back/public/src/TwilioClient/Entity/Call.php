<?php
declare(strict_types=1);

namespace TwilioClient\Entity;

use DateTimeImmutable;
use JsonSerializable;

class Call implements JsonSerializable
{
    public const TABLE = 'call';

    private int $callId = 0;        // Need both IDs in case we get call providers other than Twilio.
    private string $sid;
    private int $providerId;
    private string $status;
    private DateTimeImmutable $startDatetime;
    private ?DateTimeImmutable $endDatetime = null;

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

        $obj->callId        = (int) $assoc['call_id'];
        $obj->sid           = $assoc['sid'];
        $obj->providerId    = (int) $assoc['provider_id'];
        $obj->status        = $assoc['status'];

        return $obj;
    }

    public function isInserted(): bool
    {
        return $this->callId !== 0;
    }

    public function setCallId(int $callId): void
    {
        $this->callId = $callId;
    }

    public function getCallId(): int
    {
        return $this->callId;
    }

    public function setSid(string $sid): void
    {
        $this->sid = $sid;
    }

    public function getSid(): string
    {
        return $this->sid;
    }

    public function setProviderId(int $providerId): void
    {
        $this->providerId = $providerId;
    }

    public function getProviderId(): int
    {
        return $this->providerId;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStartDatetime(DateTimeImmutable $startDatetime): void
    {
        $this->startDatetime = $startDatetime;
    }

    public function getStartDatetime(): DateTimeImmutable
    {
        return $this->startDatetime;
    }

    public function setEndDatetime(?DateTimeImmutable $endDatetime): void
    {
        $this->endDatetime = $endDatetime;
    }

    public function getEndDatetime(): ?DateTimeImmutable
    {
        return $this->endDatetime;
    }

    /**
     * @return array<string, int|string>
     */
    public function jsonSerialize(): array
    {
        return [
            'sid'           => $this->sid,
            'providerId'    => $this->providerId,
            'status'        => $this->status,
        ];
    }
}

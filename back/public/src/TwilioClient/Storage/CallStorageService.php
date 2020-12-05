<?php
declare(strict_types=1);

namespace TwilioClient\Storage;

use PDO;
use TwilioClient\Entity\Call;
use TwilioClient\Enum\DateFormatEnum;

class CallStorageService
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function saveCall(Call $call): void
    {
        if ($call->isInserted()) {
            $this->updateCall($call);
        } else {
            $this->insertCall($call);
        }
    }

    public function updateCall(Call $call): void
    {
        $sql = sprintf(
           'UPDATE `%s` SET
                status = :status,
                end_datetime = :endDatetime
            WHERE call_id = :callId',
            Call::TABLE,
        );

        $statement = $this->connection->prepare($sql);
        $statement->execute(
            $params = [
                'callId'        => $call->getCallId(),
                'status'        => $call->getStatus(),
                'endDatetime'   => $call->getEndDatetime() ? $call->getEndDatetime()->format(DateFormatEnum::DB) : null,
            ]
        );
    }

    public function insertCall(Call $call): void
    {
        $sql = sprintf(
           'INSERT INTO `%s` (call_id, sid, provider_id, start_datetime, status) VALUES
                (:callId, :sid, :providerId, :startDatetime, :status)',
            Call::TABLE,
        );

        $statement = $this->connection->prepare($sql);
        $statement->execute(
            [
                'callId'        => $call->getCallId(),
                'sid'           => $call->getSid(),
                'providerId'    => $call->getProviderId(),
                'startDatetime' => $call->getStartDatetime()->format(DateFormatEnum::DB),
                'status'        => $call->getStatus(),
            ]
        );
        $call->setCallId((int) $this->connection->lastInsertId());
    }

    public function findCallBySid(string $sid): ?Call
    {
        $sql = sprintf(
            'SELECT * FROM `%s` WHERE sid = :sid',
            Call::TABLE,
        );

        $statement = $this->connection->prepare($sql);
        $statement->execute(['sid' => $sid]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return Call::denormalize($row);
    }
}

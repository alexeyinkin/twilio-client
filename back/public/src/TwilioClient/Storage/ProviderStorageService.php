<?php
declare(strict_types=1);

namespace TwilioClient\Storage;

use PDO;
use TwilioClient\Entity\Location;
use TwilioClient\Entity\Provider;

class ProviderStorageService
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array<int, Provider>
     */
    public function findAllProviders(): array
    {
        $sql = sprintf('
            SELECT *
            FROM
                %s provider
                INNER JOIN %s location ON provider.location_id = location.location_id
            ',
            Provider::TABLE,
            Location::TABLE,
        );

        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return Provider::denormalizeArray($rows);
    }

    public function findProviderById(int $providerId): ?Provider
    {
        $sql = sprintf('
            SELECT *
            FROM
                %s provider
                INNER JOIN %s location ON provider.location_id = location.location_id
            WHERE provider_id = :providerId
            ',
            Provider::TABLE,
            Location::TABLE,
        );

        $statement = $this->connection->prepare($sql);
        $statement->execute(['providerId' => $providerId]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return Provider::denormalize($row);
    }

    public function setProviderStatus(int $providerId, string $status): void
    {
        $sql = sprintf(
            'UPDATE %s SET status = :status WHERE provider_id = :providerId',
            Provider::TABLE,
        );

        $statement = $this->connection->prepare($sql);
        $statement->execute(['providerId' => $providerId, 'status' => $status]);
    }
}

<?php declare(strict_types=1);

namespace App\Infrastructure\Presistance;

use App\Domain\Vote\Url;
use App\Domain\Vote\Rate;
use App\Domain\Vote\Vote;
use App\Domain\Vote\Identity;
use App\Domain\Vote\VoteCollection;
use App\Domain\VoteRepositoryInterface;
use App\Infrastructure\Exception\PersistenceException;
use PDO;
use PDOException;

class PDOVoteRepository implements VoteRepositoryInterface
{
    /** @var PDO */
    private $connection;

    /**
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function getByIdentity(Identity $identity): Vote
    {
        try {
            $sth = $this->connection->prepare(
                'SELECT `identity`, `url`, `rate` FROM `vote` WHERE `identity` = :identity'
            );
            $sth->bindValue(':identity', $identity->asString());
            $sth->execute();
            $result = $sth->fetch();
        } catch (PDOException $exception) {
            throw new PersistenceException('Failed to fetch vote by identity.', 0, $exception);
        }

        return new Vote(
            Identity::fromString($result['identity']),
            Url::fromString($result['url']),
            Rate::fromInteger((int)$result['rate'])
        );
    }

    /**
     * @inheritDoc
     */
    public function getByUrl(Url $url): VoteCollection
    {
        try {
            $sth = $this->connection->prepare(
                'SELECT `identity`, `url`, `rate` FROM `vote` WHERE `url` = :url'
            );
            $sth->bindValue(':url', $url->asString());
            $sth->execute();
            $results = $sth->fetchAll();
        } catch (PDOException $exception) {
            throw new PersistenceException('Failed to fetch vote by url.', 0, $exception);
        }

        $votes = array_map(function (array $item) {
            return new Vote(
                Identity::fromString($item['identity']),
                Url::fromString($item['url']),
                Rate::fromInteger((int)$item['rate'])
            );
        }, $results);

        return new VoteCollection($votes);
    }

    /**
     * @inheritDoc
     */
    public function persist(Vote $vote): void
    {
        try {
            $sth = $this->connection->prepare(
                'INSERT INTO `vote` (`identity`, `url`, `rate`) VALUES(:identity, :url, :rate)'
            );
            $sth->bindValue(':identity', $vote->getIdentity()->asString());
            $sth->bindValue(':url', $vote->getUrl()->asString());
            $sth->bindValue(':rate', $vote->getRate()->asInteger());
            $sth->execute();
        } catch (PDOException $exception) {
            throw new PersistenceException('Failed to save vote.', 0, $exception);
        }
    }
}

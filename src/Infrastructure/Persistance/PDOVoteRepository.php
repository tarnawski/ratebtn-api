<?php declare(strict_types=1);

namespace App\Infrastructure\Presistance;

use App\Domain\Vote\Hash;
use App\Domain\Vote\Rate;
use App\Domain\Vote\Vote;
use App\Domain\Vote\Identity;
use App\Domain\Vote\VoteCollection;
use App\Domain\VoteRepositoryInterface;
use PDO;

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
        $sth = $this->connection->prepare('SELECT * FROM `vote` WHERE `identity` = :identity');
        $sth->bindParam(':hash', $identity->asString());
        $sth->execute();
        $item = $sth->fetch();

        return new Vote(
            Identity::fromString($item['identity']),
            Hash::fromString($item['hash']),
            Rate::fromInteger((int)$item['rate'])
        );
    }

    /**
     * @inheritDoc
     */
    public function getByHash(Hash $hash): VoteCollection
    {
        $sth = $this->connection->prepare('SELECT * FROM `vote` WHERE `hash` = :hash');
        $sth->bindParam(':hash', $hash->asString());
        $sth->execute();

        $votes = array_map(function (array $item) {
            return new Vote(
                Identity::fromString($item['identity']),
                Hash::fromString($item['hash']),
                Rate::fromInteger((int)$item['rate'])
            );
        }, $sth->fetchAll());

        return new VoteCollection($votes);
    }

    /**
     * @inheritDoc
     */
    public function persist(Vote $vote): void
    {
        $sth = $this->connection->prepare(
            'INSERT INTO `vote` (`identity`, `hash`, `rate`) VALUES(:identity, :hash, :rate)'
        );
        $sth->bindParam(':identity', $vote->getIdentity()->asString());
        $sth->bindParam(':hash', $vote->getHash()->asString());
        $sth->bindParam(':rate', $vote->getRate()->asInteger());
        $sth->execute();
    }
}
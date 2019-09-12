<?php declare(strict_types=1);

namespace App\Infrastructure\Presistance;

use App\Domain\Vote\Url;
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
        $sth = $this->connection->prepare(
            'SELECT `identity`, `url`, `rate` FROM `vote` WHERE `identity` = :identity'
        );
        $sth->bindValue(':identity', $identity->asString());
        $sth->execute();
        $item = $sth->fetch();

        return new Vote(
            Identity::fromString($item['identity']),
            Url::fromString($item['url']),
            Rate::fromInteger((int)$item['rate'])
        );
    }

    /**
     * @inheritDoc
     */
    public function getByUrl(Url $url): VoteCollection
    {
        $sth = $this->connection->prepare(
            'SELECT `identity`, `url`, `rate` FROM `vote` WHERE `url` = :url'
        );
        $sth->bindValue(':url', $url->asString());
        $sth->execute();

        $votes = array_map(function (array $item) {
            return new Vote(
                Identity::fromString($item['identity']),
                Url::fromString($item['url']),
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
            'INSERT INTO `vote` (`identity`, `url`, `rate`) VALUES(:identity, :url, :rate)'
        );
        $sth->bindValue(':identity', $vote->getIdentity()->asString());
        $sth->bindValue(':url', $vote->getUrl()->asString());
        $sth->bindValue(':rate', $vote->getRate()->asInteger());
        $sth->execute();
    }
}

<?php

/**
 * src/Entity/Result.php
 *
 * @category Entities
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

namespace MiW\Results\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Stringable;

/**
 * Class Result
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
#[ORM\Entity, ORM\Table(
    name: 'results',
    indexes: [ 'name' => 'FK_USER_ID_idx', 'columns' => [ 'user_id' ] ]
)]
class Result implements JsonSerializable, Stringable
{
    #[ORM\Column(
        name: 'id',
        type: 'integer',
        nullable: false
    )]
    #[ORM\Id, ORM\GeneratedValue(strategy: "IDENTITY")]
    protected int $id;

    #[ORM\Column(
        name: 'result',
        type: 'integer',
        nullable: false
    )]
    protected int $result;

    #[ORM\ManyToOne(targetEntity: 'User')]
    #[ORM\JoinColumn(
        name: 'user_id',
        referencedColumnName: 'id',
        onDelete: 'CASCADE'
    )]
    protected User $user;

    #[ORM\Column(
        name: 'time',
        type: 'datetime',
        nullable: false
    )]
    protected DateTime $time;

    /**
     * Result constructor.
     *
     * @param int $result result
     * @param User|null $user user
     * @param DateTime|null $time time
     */
    public function __construct(
        int $result = 0,
        ?User $user = null,
        ?DateTime $time = null
    ) {
        $this->id     = 0;
        $this->result = $result;
        $this->user   = $user;
        $this->time   = $time;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Implements __toString()
     *
     * @return string
     * @link   http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString(): string
    {
        return sprintf(
            '%3d - %3d - %22s - %s',
            $this->id,
            $this->result,
            $this->user->getUsername(),
            $this->time->format('Y-m-d H:i:s')
        );
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link   http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since  5.4.0
     */
    public function jsonSerialize(): array
    {
        return [
            'id'     => $this->id,
            'result' => $this->result,
            'user'   => $this->user,
            'time'   => $this->time->format('Y-m-d H:i:s')
        ];
    }
}

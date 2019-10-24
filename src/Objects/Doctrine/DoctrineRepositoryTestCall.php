<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\Doctrine;

use Doctrine\ORM\EntityRepository;

/**
 * Class DoctrineRepositoryTestCall
 * @package Tests\Neznajka\Codeception\Engine\Objects\Doctrine
 */
class DoctrineRepositoryTestCall extends AbstractDoctrineTestCall
{

    public function getTargetClassName(): string
    {
        return EntityRepository::class;
    }
}

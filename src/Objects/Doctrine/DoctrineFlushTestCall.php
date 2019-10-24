<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\Doctrine;

use Doctrine\ORM\EntityManager;

/**
 * Class DoctrineFlushTestCall
 * @package Tests\Neznajka\Codeception\Engine\Objects\Doctrine
 */
class DoctrineFlushTestCall extends AbstractDoctrineTestCall
{
    /** @var callable */
    protected $validator;

    public function __construct(callable $validator = null)
    {
        parent::__construct('flush', [], null);
        $this->validator = $validator;
    }

    public function isArgumentsValid(array $arguments, $class): bool
    {
        if ($this->validator) {
            call_user_func_array($this->validator, $arguments);
        }

        return true;
    }

    public function getTargetClassName(): string
    {
        return EntityManager::class;
    }
}

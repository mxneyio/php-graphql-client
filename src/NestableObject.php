<?php

namespace GraphQL;

/**
 * Class NestableObject
 *
 * @codeCoverageIgnore
 *
 * @package GraphQL
 */
abstract class NestableObject
{
    // TODO: Remove this method and class entirely, it's purely tech debt
    /**
     * @return mixed
     */
    abstract protected function setAsNested();

    abstract protected function aliasOrName(): string;
}
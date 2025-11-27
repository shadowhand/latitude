<?php

namespace Latitude\QueryBuilder\Query\Capability;

trait HasOnConstraint
{
    protected bool $onConstraint = false;
    protected null|string|array $constraint = null;
    protected ?bool $ignore = null;
    protected array $updatesMap = [];

    public function ignoreOnConstraint(null|string|array $constraint): self
    {
        $this->onConstraint = true;
        $this->constraint = $constraint;
        $this->ignore = true;

        return $this;
    }

    public function updateOnConstraint(null|string|array $constraint, array $map): self
    {
        $this->onConstraint = true;
        $this->constraint = $constraint;
        $this->updatesMap = $map;
        $this->ignore = false;

        return $this;
    }
}

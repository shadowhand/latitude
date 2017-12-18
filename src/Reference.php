<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

/**
 * Class Reference
 * @package Latitude\QueryBuilder
 */
class Reference implements Statement
{
    use Traits\CanUseDefaultIdentifier;
    use Traits\HasNoParameters;

    /**
     * Create a new table or column reference.
     *
     * @param string $reference
     * @return static
     */
    public static function make(string $reference)
    {
        return new static($reference);
    }

    // Statement
    /**
     * @param Identifier|null $identifier
     * @return string
     * @throws \TypeError
     */
    public function sql(Identifier $identifier = null): string
    {
        $identifier = $this->getDefaultIdentifier($identifier);

        return $identifier->escapeQualified($this->reference);
    }

    /**
     * @var string
     */
    protected $reference;

    /**
     * @see Reference::make()
     */
    protected function __construct(string $reference)
    {
        $this->reference = $reference;
    }
}

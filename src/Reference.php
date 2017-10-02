<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

class Reference implements Statement
{
    use Traits\CanUseDefaultIdentifier;
    use Traits\HasNoParameters;

    /**
     * Create a new table or column reference.
     */
    public static function make(string $reference)
    {
        return new static($reference);
    }

    // Statement
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

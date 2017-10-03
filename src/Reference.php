<?php

namespace Latitude\QueryBuilder;

class Reference implements Statement
{
    use Traits\CanUseDefaultIdentifier;
    use Traits\HasNoParameters;
    /**
     * Create a new table or column reference.
     */
    public static function make($reference)
    {
        return new static($reference);
    }
    // Statement
    public function sql(Identifier $identifier = null)
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
    protected function __construct($reference)
    {
        $this->reference = $reference;
    }
}
<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Ruler;

use Hoa\Ruler\Model as Ast;
use Hoa\Ruler\Visitor\Disassembly;
use Hoa\Visitor\Element as ElementInterface;
use Hoa\Visitor\Visit as VisitorInterface;

use function Latitude\QueryBuilder\criteria;
use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\listing;
use function Latitude\QueryBuilder\literal;
use function Latitude\QueryBuilder\param;

class Visitor implements VisitorInterface
{
    const SUPPORTED_ACCESS_TYPE = [
        Ast\Bag\Context::ARRAY_ACCESS,
        Ast\Bag\Context::ATTRIBUTE_ACCESS,
    ];

    /** @var Disassembly */
    protected $disassembly;

    public function __construct()
    {
        $this->disassembly = new Disassembly();
    }

    public function visit(ElementInterface $element, &$handle = null, $eldnah = null)
    {
        if ($element instanceof Ast\Model) {
            return $this->visitModel($element, $handle, $eldnah);
        }

        if ($element instanceof Ast\Operator) {
            return $this->visitOperator($element, $handle, $eldnah);
        }

        if ($element instanceof Ast\Bag\Scalar) {
            return $this->visitScalar($element, $handle, $eldnah);
        }

        if ($element instanceof Ast\Bag\RulerArray) {
            return $this->visitArray($element, $handle, $eldnah);
        }

        if ($element instanceof Ast\Bag\Context) {
            return $this->visitAccess($element, $handle, $eldnah);
        }

        // @codeCoverageIgnoreStart
        throw new \LogicException(sprintf('Element of type "%s" not handled', get_class($element)));
        // @codeCoverageIgnoreEnd
    }

    protected function visitAccess(Ast\Bag\Context $element, &$handle = null, $eldnah = null)
    {
        if ($element->getId() === '?') {
            return literal($element->getId());
        }

        $identifier = [$element->getId()];
        foreach ($element->getDimensions() as $dimension) {
            $this->assertValidAccess($element, $dimension[Ast\Bag\Context::ACCESS_TYPE]);
            $identifier[] = $dimension[Ast\Bag\Context::ACCESS_VALUE];
        }

        return identify(implode('.', $identifier));
    }

    protected function assertValidAccess(Ast\Bag\Context $element, int $type): void
    {
        if (in_array($type, self::SUPPORTED_ACCESS_TYPE) === false) {
            throw new \LogicException(sprintf(
                'Invalid access type in expression: %s',
                $this->disassembly->visit($element)
            ));
        }
    }

    protected function visitScalar(Ast\Bag\Scalar $element, &$handle = null, $eldnah = null)
    {
        return param($element->getValue());
    }

    protected function visitArray(Ast\Bag\RulerArray $element, &$handle = null, $eldnah = null)
    {
        return express('(%s)', listing(array_map($this->remapper($handle, $eldnah), $element->getArray())));
    }

    protected function visitModel(Ast\Model $element, &$handle = null, $eldnah = null)
    {
        return $element->getExpression()->accept($this, $handle, $eldnah);
    }

    protected function visitOperator(Ast\Operator $element, &$handle = null, $eldnah = null)
    {
        $values = array_map($this->remapper($handle, $eldnah), $element->getArguments());

        if ($element->isFunction()) {
            return criteria(sprintf('%s(%%s)', strtoupper($element->getName())), listing($values));
        }

        if (count($values) === 2) {
            return criteria(sprintf('%%s %s %%s', $element->getName()), $values[0], $values[1]);
        }

        return criteria(sprintf('%s (%%s)', $element->getName()), listing($values, ' '));
    }

    protected function remapper(&$handle, $eldnah): callable
    {
        return function ($element) use (&$handle, $eldnah) {
            return $element->accept($this, $handle, $eldnah);
        };
    }
}

<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use PHPUnit_Framework_TestCase as TestCase;

class ConditionsTest extends TestCase
{
    public function testBasicAndOr()
    {
        $conditions = Conditions::make()
            ->with('id = ?', 1)
            ->andWith('last_login > ?', 'today')
            ->orWith('last_login IS NULL');

        $this->assertSql($conditions, 'id = ? AND last_login > ? OR last_login IS NULL');
        $this->assertParams($conditions, [1, 'today']);
    }

    public function testLogicalIn()
    {
        $conditions = Conditions::make()
            ->with('role_id IN ?', InValue::make([1, 2, 3]))
            ->orWith('user_id IN ?', InValue::make([100]));

        $this->assertSql($conditions, 'role_id IN (?, ?, ?) OR user_id IN (?)');
        $this->assertParams($conditions, [1, 2, 3, 100]);

        $conditions = Conditions::make()
            ->with('role_id IN ?', InValue::make([4, 5, 6]));

        $this->assertSql($conditions, 'role_id IN (?, ?, ?)');
        $this->assertParams($conditions, [4, 5, 6]);
    }

    public function testGroupingWithAnd()
    {
        $conditions = Conditions::make()
            ->with('id = ?', 1);

        $group = $conditions->group()
            ->with('last_login > ?', 'today')
            ->orWith('last_login IS NULL');

        $this->assertSame($conditions, $group->end());

        $this->assertSql($group, 'last_login > ? OR last_login IS NULL');
        $this->assertSql($conditions, 'id = ? AND (' . $group->sql() . ')');

        $this->assertParams($conditions, [1, 'today']);
    }

    public function testGroupingWithOr()
    {
        $conditions = Conditions::make()
            ->group()
                ->with('failed_logins > ?', 5)
                ->andWith('last_login IS NULL')
            ->end()
            ->orGroup()
                ->with('role = ?', 'banned')
            ->end();

        $this->assertSql($conditions, '(failed_logins > ? AND last_login IS NULL) OR (role = ?)');
        $this->assertParams($conditions, [5, 'banned']);
    }

    public function testGroupParent()
    {
        $conditions = Conditions::make();

        $this->assertSame($conditions, $conditions->end());

        $subconditions = $conditions->group();

        $this->assertSame($conditions, $subconditions->end());
    }

    public function testSubConditionIdentifier()
    {
        $identifier = Common\Identifier::make();

        $conditions = Conditions::make()
            ->with('u.id = ?')
            ->orGroup()
                ->with('u.username = ?')
            ->end();

        $this->assertSame(
            '"u"."id" = ? OR ("u"."username" = ?)',
            $conditions->sql($identifier)
        );
    }

    private function assertSql(Conditions $conditions, string $expected)
    {
        $this->assertSame($expected, $conditions->sql());
    }

    private function assertParams(Conditions $conditions, array $params)
    {
        $this->assertSame($params, $conditions->params());
    }
}

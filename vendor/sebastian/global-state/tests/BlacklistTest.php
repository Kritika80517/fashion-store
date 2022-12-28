<?php declare(strict_types=1);
/*
 * This file is part of sebastian/global-state.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\GlobalState;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\GlobalState\TestFixture\blacklistedChildClass;
use SebastianBergmann\GlobalState\TestFixture\blacklistedClass;
use SebastianBergmann\GlobalState\TestFixture\blacklistedImplementor;
use SebastianBergmann\GlobalState\TestFixture\blacklistedInterface;

/**
 * @covers \SebastianBergmann\GlobalState\blacklist
 */
final class blacklistTest extends TestCase
{
    /**
     * @var \SebastianBergmann\GlobalState\blacklist
     */
    private $blacklist;

    protected function setUp(): void
    {
        $this->blacklist = new blacklist;
    }

    public function testGlobalVariableThatIsNotblacklistedIsNotTreatedAsblacklisted(): void
    {
        $this->assertFalse($this->blacklist->isGlobalVariableblacklisted('variable'));
    }

    public function testGlobalVariableCanBeblacklisted(): void
    {
        $this->blacklist->addGlobalVariable('variable');

        $this->assertTrue($this->blacklist->isGlobalVariableblacklisted('variable'));
    }

    public function testStaticAttributeThatIsNotblacklistedIsNotTreatedAsblacklisted(): void
    {
        $this->assertFalse(
            $this->blacklist->isStaticAttributeblacklisted(
                blacklistedClass::class,
                'attribute'
            )
        );
    }

    public function testClassCanBeblacklisted(): void
    {
        $this->blacklist->addClass(blacklistedClass::class);

        $this->assertTrue(
            $this->blacklist->isStaticAttributeblacklisted(
                blacklistedClass::class,
                'attribute'
            )
        );
    }

    public function testSubclassesCanBeblacklisted(): void
    {
        $this->blacklist->addSubclassesOf(blacklistedClass::class);

        $this->assertTrue(
            $this->blacklist->isStaticAttributeblacklisted(
                blacklistedChildClass::class,
                'attribute'
            )
        );
    }

    public function testImplementorsCanBeblacklisted(): void
    {
        $this->blacklist->addImplementorsOf(blacklistedInterface::class);

        $this->assertTrue(
            $this->blacklist->isStaticAttributeblacklisted(
                blacklistedImplementor::class,
                'attribute'
            )
        );
    }

    public function testClassNamePrefixesCanBeblacklisted(): void
    {
        $this->blacklist->addClassNamePrefix('SebastianBergmann\GlobalState');

        $this->assertTrue(
            $this->blacklist->isStaticAttributeblacklisted(
                blacklistedClass::class,
                'attribute'
            )
        );
    }

    public function testStaticAttributeCanBeblacklisted(): void
    {
        $this->blacklist->addStaticAttribute(
            blacklistedClass::class,
            'attribute'
        );

        $this->assertTrue(
            $this->blacklist->isStaticAttributeblacklisted(
                blacklistedClass::class,
                'attribute'
            )
        );
    }
}

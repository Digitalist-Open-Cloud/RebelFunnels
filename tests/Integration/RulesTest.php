<?php

namespace Piwik\Plugins\RebelFunnels\tests\Integration;

use Piwik\Plugins\RebelFunnels\Matching\Rules;
use Piwik\Tests\Framework\TestCase\IntegrationTestCase;

/**
 * @group RebelFunnels
 * @group Plugins
 */
class RulesTest extends IntegrationTestCase
{
    /**
     * @dataProvider getUrlRules
     */
    public function testUrlRule(string $operator, string $value, string $url, bool $expected)
    {
        $steps = [['name' => 'Step', 'rules' => [['type' => 'url', 'operator' => $operator, 'value' => $value]]]];

        $this->assertSame([$expected], Rules::testUrl($steps, $url));
    }

    public function getUrlRules(): array
    {
        return [
            'equals' => ['equals', 'example.org/cart', 'https://example.org/cart', true],
            'equals is the whole URL' => ['equals', 'example.org/cart', 'https://example.org/cart/items', false],
            'protocol and www. are ignored' => ['equals', 'http://www.example.org/cart', 'https://example.org/cart', true],
            'case is ignored' => ['equals', 'example.org/Cart', 'https://EXAMPLE.org/cart', true],
            'contains' => ['contains', '/cart', 'https://example.org/shop/cart?step=1', true],
            'contains not' => ['contains', '/basket', 'https://example.org/shop/cart', false],
            'starts with' => ['starts_with', 'https://example.org/shop', 'http://example.org/shop/cart', true],
            'starts with not' => ['starts_with', 'example.org/cart', 'http://example.org/shop/cart', false],
            'ends with' => ['ends_with', '/cart', 'http://example.org/shop/cart', true],
            'ends with not' => ['ends_with', '/shop', 'http://example.org/shop/cart', false],
            'percent is no wildcard' => ['contains', '100%', 'http://example.org/1000', false],
            'underscore is no wildcard' => ['contains', 'a_c', 'http://example.org/abc', false],
            'regex' => ['regex', '/order/[0-9]+$', 'http://example.org/order/42', true],
            'regex not' => ['regex', '/order/[0-9]+$', 'http://example.org/order/new', false],
            'regex sees no protocol' => ['regex', '^example\.org/$', 'https://www.example.org/', true],
        ];
    }

    public function testAUrlIsOnlyComparedWithPageUrlRules()
    {
        $steps = [
            ['name' => 'Title', 'rules' => [['type' => 'title', 'operator' => 'contains', 'value' => 'cart']]],
            ['name' => 'Either', 'rules' => [
                ['type' => 'event_name', 'operator' => 'contains', 'value' => 'cart'],
                ['type' => 'url', 'operator' => 'contains', 'value' => 'cart'],
            ]],
        ];

        $this->assertSame([false, true], Rules::testUrl($steps, 'http://example.org/cart'));
    }

    public function testAnInvalidRegexIsRejected()
    {
        Rules::assertValidRegex('^/cart/(a|b)$');

        $this->expectException(\Exception::class);
        Rules::assertValidRegex('(unclosed');
    }
}

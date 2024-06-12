<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Collection;

use Orangesoft\Throttler\Collection\Node;
use PHPUnit\Framework\TestCase;

final class NodeTest extends TestCase
{
    public function testNode(): void
    {
        $node = new Node(
            name: '192.168.0.1',
            weight: 5,
            payload: [
                'callback_url' => 'http://127.0.0.1/',
            ],
        );

        $this->assertSame('192.168.0.1', $node->getName());
        $this->assertSame(5, $node->getWeight());
        $this->assertSame(['callback_url' => 'http://127.0.0.1/'], $node->getPayload());
    }
}

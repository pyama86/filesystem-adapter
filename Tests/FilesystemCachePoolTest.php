<?php

/*
 * This file is part of php-cache organization.
 *
 * (c) 2015 Aaron Scherer <aequasi@gmail.com>, Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cache\Adapter\Filesystem\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FilesystemCachePoolTest extends TestCase
{
    use CreatePoolTrait;

    /**
     * @expectedException \Psr\Cache\InvalidArgumentException
     */
    public function testInvalidKey()
    {
        $pool = $this->createCachePool();

        $pool->getItem('test%string')->get();
    }

    public function testCleanupOnExpire()
    {
    }

    public function testChangeFolder()
    {
        $pool = $this->createCachePool();
        $pool->setFolder('foobar');

        $pool->save($pool->getItem('test_path'));
        $this->assertTrue($this->getFilesystem()->has('foobar/test_path'));
    }

    public function testCorruptedCacheFileHandledNicely()
    {
        $pool = $this->createCachePool();

        $this->getFilesystem()->write('cache/corrupt', 'corrupt data');

        $item = $pool->getItem('corrupt');
        $this->assertFalse($item->isHit());

        $this->getFilesystem()->delete('cache/corrupt');
    }
}

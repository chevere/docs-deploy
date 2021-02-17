<?php

/*
 * This file is part of Chevere.
 *
 * (c) Rodolfo Berrios <rodolfo@chevere.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Chevere\Tests;

use function Chevere\Components\Filesystem\dirForPath;
use function Chevere\Components\Writer\streamTemp;
use Chevere\Components\Writer\StreamWriter;
use DocsDeploy\Iterator;
use PHPUnit\Framework\TestCase;

final class IteratorTest extends TestCase
{
    public function testConstruct(): void
    {
        $dir = dirForPath(__DIR__ . '/_resources/docs/');
        $writer = new StreamWriter(streamTemp(''));
        $iterator = new Iterator($dir, $writer);
        $this->assertSame($dir, $iterator->dir());
    }

    public function testDocs(): void
    {
        $iterator = $this->getIterator('');
        $this->assertSame(
            [
                'files-readme-sub-folders/',
                'files/',
                'files-readme/',
                'README.md',
                'sub-folders/',
            ],
            $iterator->contents()['/']
        );
        $flags = $iterator->flags()['/'];
        $this->assertTrue($flags->hasNested());
        $this->assertTrue($flags->hasReadme());
    }

    public function testFiles(): void
    {
        $iterator = $this->getIterator('files/');
        $this->assertSame(
            [
                'file-2.md',
                'file-1.md',
            ],
            $iterator->contents()['/']
        );
        $flags = $iterator->flags()['/'];
        $this->assertFalse($flags->hasNested());
        $this->assertFalse($flags->hasReadme());
    }

    public function testFilesReadme(): void
    {
        $iterator = $this->getIterator('files-readme/');
        $this->assertSame(
            [
                'README.md',
                'file-2.md',
                'file-1.md',
            ],
            $iterator->contents()['/']
        );
        $flags = $iterator->flags()['/'];
        $this->assertFalse($flags->hasNested());
        $this->assertTrue($flags->hasReadme());
    }

    public function testFilesReadmeSubFolders(): void
    {
        $iterator = $this->getIterator('files-readme-sub-folders/');
        $this->assertSame(
            [
                'folder-1/',
                'folder-2/',
                'README.md',
                'file-1.md',
            ],
            $iterator->contents()['/']
        );
        $flags = $iterator->flags()['/'];
        $this->assertTrue($flags->hasNested());
        $this->assertTrue($flags->hasReadme());
    }

    public function testSubFolders(): void
    {
        $iterator = $this->getIterator('sub-folders/');
        $this->assertSame(
            [
                'folder-1/',
                'folder-2/',
            ],
            $iterator->contents()['/']
        );
        $flags = $iterator->flags()['/'];
        $this->assertTrue($flags->hasNested());
        $this->assertFalse($flags->hasReadme());
    }

    private function getIterator(string $path): Iterator
    {
        return new Iterator(
            dirForPath(__DIR__ . '/_resources/docs/' . $path),
            new StreamWriter(streamTemp(''))
        );
    }
}
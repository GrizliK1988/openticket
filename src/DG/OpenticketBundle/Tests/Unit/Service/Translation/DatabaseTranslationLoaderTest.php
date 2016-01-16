<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\Tests\Unit\Service\Translation;
use DG\OpenticketBundle\Entity\TranslationFilter;
use DG\OpenticketBundle\Model\Translation;
use DG\OpenticketBundle\Services\Translation\DatabaseTranslationLoader;


/**
 * Class DatabaseTranslationLoaderTest
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class DatabaseTranslationLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \DG\OpenticketBundle\Services\Repository\TranslationRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $translationRepositoryMock;

    /**
     * @var DatabaseTranslationLoader
     */
    private $loader;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->translationRepositoryMock = $this->getMockForAbstractClass('DG\OpenticketBundle\Services\Repository\TranslationRepositoryInterface');
        $this->loader = new DatabaseTranslationLoader($this->translationRepositoryMock);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->translationRepositoryMock = null;
        $this->loader = null;
    }

    public function testLoad()
    {
        $this->translationRepositoryMock->expects($this->once())->method('loadByFilter')
            ->with((new TranslationFilter())->setLocale('locale')->setDomain('domain'))->willReturn([
                Translation::create()->setLocale('locale')->setDomain('domain')->setKey('key')->setTranslation('value')
            ]);

        $catalogue = $this->loader->load('', 'locale', 'domain');
        $this->assertEquals(['domain' => ['key' => 'value']], $catalogue->all());
        $this->assertEquals(['key' => 'value'], $catalogue->all('domain'));
        $this->assertEquals([], $catalogue->all('not_existed_domain'));
    }

    /**
     * @expectedException \Symfony\Component\Translation\Exception\InvalidResourceException
     */
    public function testLoadWithError()
    {
        $this->translationRepositoryMock->expects($this->once())->method('loadByFilter')->willThrowException(new \Exception());
        $this->loader->load('', 'locale', 'domain');
    }
}

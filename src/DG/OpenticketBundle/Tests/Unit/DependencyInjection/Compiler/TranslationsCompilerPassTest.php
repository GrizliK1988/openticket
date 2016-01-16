<?php

namespace DG\OpenticketBundle\Tests\Unit\DependencyInjection\Compiler;


use DG\OpenticketBundle\DependencyInjection\Compiler\TranslationsCompilerPass;
use DG\OpenticketBundle\Services\Translation\DatabaseTranslationLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class TranslationsCompilerPassTest
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TranslationsCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        /** @var ContainerBuilder|\PHPUnit_Framework_MockObject_MockObject $containerMock */
        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $containerMock->expects($this->once())->method('getParameter')->with('dg_openticket.locales')->willReturn(['en']);

        /** @var Definition|\PHPUnit_Framework_MockObject_MockObject $definition */
        $definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $definition->expects($this->once())->method('getArgument')->with(3)->willReturn([
            'dummy_key' => 'dummy_data',
            'resource_files' => ['en' => ['existed_translation']]
        ]);
        $definition->expects($this->once())->method('replaceArgument')->with(3, [
            'dummy_key' => 'dummy_data',
            'resource_files' => [
                'en' => [
                    'existed_translation',
                    sprintf('%s.%s.db', DatabaseTranslationLoader::DOMAIN_TICKET_STATUS, 'en'),
                    sprintf('%s.%s.db', DatabaseTranslationLoader::DOMAIN_TICKET_CATEGORY, 'en'),
                ]
            ]
        ]);

        $containerMock->expects($this->once())->method('getDefinition')->with('translator.default')->willReturn($definition);

        $compiler = new TranslationsCompilerPass();
        $compiler->process($containerMock);
    }
}

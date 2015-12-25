<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\DependencyInjection\Compiler;


use DG\OpenticketBundle\Services\Translation\DatabaseTranslationLoader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class TranslationsCompilerPass
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TranslationsCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $translatorDefinition = $container->getDefinition('translator.default');
        $locales = $container->getParameter('dg_openticket.locales');

        $options = $translatorDefinition->getArgument(3);
        if (!isset($options['resource_files'])) {
            $options['resource_files'] = [];
        }

        foreach ($locales as $locale) {
            if (!isset($options['resource_files'][$locale])) {
                $options['resource_files'][$locale] = [];
            }

            foreach (DatabaseTranslationLoader::DOMAINS as $domain) {
                $options['resource_files'][$locale][] = sprintf('%s.%s.db', $domain, $locale);
            }
        }

        $translatorDefinition->replaceArgument(3, $options);
    }
}
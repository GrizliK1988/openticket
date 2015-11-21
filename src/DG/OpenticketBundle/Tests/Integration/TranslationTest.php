<?php

namespace DG\OpenticketBundle\Tests\Integration;


use DG\OpenticketBundle\Model\Translation;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TranslationTest extends AbstractORMTest
{
    public function testUserCRUD()
    {
        $translation = Translation::create()
            ->setLocale('ru')
            ->setDomain('status')
            ->setKey('opened')
            ->setTranslation('Открыт');

        $this->persist($translation);
        $this->manager->flush();
        $this->manager->clear();

        /** @var Translation[] $translations */
        $repo = $this->manager->getRepository('DGOpenticketBundle:Translation');
        $translations = $repo->findBy(['domain' => 'status']);
        $this->assertNotEmpty($translations);
        $this->assertEquals('ru', $translations[0]->getLocale());
        $this->assertEquals('opened', $translations[0]->getKey());
        $this->assertEquals('Открыт', $translations[0]->getTranslation());

        $translations[0]
            ->setTranslation('Opened')
            ->setLocale('en');

        $this->persist($translations[0]);
        $this->manager->flush();
        $this->manager->clear();

        /** @var Translation $foundTranslation */
        $foundTranslation = $repo->find($translations[0]->getId());
        $this->assertNotEmpty($foundTranslation);

        $this->assertEquals('en', $foundTranslation->getLocale());
        $this->assertEquals('Opened', $foundTranslation->getTranslation());
    }
}
 
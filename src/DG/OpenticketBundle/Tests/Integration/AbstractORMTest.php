<?php

namespace DG\OpenticketBundle\Tests\Integration;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
abstract class AbstractORMTest extends WebTestCase
{
    protected $persistedEntities = [];

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var QueriesCountLogger
     */
    protected $queriesCountLogger;

    protected function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $this->manager = $container->get('doctrine.orm.entity_manager');
        $this->queriesCountLogger = new QueriesCountLogger();
        $this->manager->getConnection()->getConfiguration()->setSQLLogger($this->queriesCountLogger);
    }

    protected function tearDown()
    {
        $this->manager = $this->manager->create($this->manager->getConnection(), $this->manager->getConfiguration());
        foreach ($this->persistedEntities as $entity) {
            $repo = $this->manager->getRepository('DGOpenticketBundle:' . str_replace('DG\OpenticketBundle\Model\\', '', get_class($entity)));
            $foundEntities = $repo->findBy(['id' => $entity->getId()]);
            foreach ($foundEntities as $foundEntity) {
                $this->manager->remove($foundEntity);
            }
        }
        $this->manager->flush();

        $this->manager = null;
    }

    protected function persist($entity)
    {
        $this->manager->persist($entity);
        $this->persistedEntities[] = $entity;
    }
}

<?php

namespace DG\OpenticketBundle\EventSubscriber;


use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Adds table prefix configured in %database_tables_prefix% parameter
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TablePrefixEventSubscriber implements EventSubscriber
{
    private $tablesScheme = null;

    /**
     * @param $tablesScheme
     */
    public function __construct($tablesScheme)
    {
        $this->tablesScheme = $tablesScheme;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     */
    public function getSubscribedEvents()
    {
        return [
            'loadClassMetadata' => 'loadClassMetadata'
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {
        /** @var ClassMetaData $classMetaData */
        $classMetaData = $event->getClassMetadata();

        $tableName = $classMetaData->getTableName();
        $classMetaData->setPrimaryTable([
            'name' => $this->tablesScheme ? $this->tablesScheme . '.' . $tableName : $tableName
        ]);
    }
}
<?php

namespace DG\OpenticketBundle\DataFixtures;
use DG\OpenticketBundle\Exception\DuplicateException;


/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
interface FixtureLoaderInterface
{
    /**
     * Performs fixture loading: persists and flushes fixture
     *
     * @throws DuplicateException
     */
    public function load();
}
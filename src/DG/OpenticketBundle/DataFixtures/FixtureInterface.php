<?php

namespace DG\OpenticketBundle\DataFixtures;


/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
interface FixtureInterface
{
    /**
     * Performs fixture loading: persists and flushes fixture
     *
     * @return void
     */
    public function load();
}
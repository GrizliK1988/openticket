<?php

namespace DG\OpenticketBundle\DataFixtures;


/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
interface FixtureLoadCheckerInterface
{
    /**
     * Says whether fixture has been already loaded or not
     *
     * @return bool
     */
    public function hasBeenLoaded();
}
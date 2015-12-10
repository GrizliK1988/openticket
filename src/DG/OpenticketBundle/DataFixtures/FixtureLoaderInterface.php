<?php

declare(strict_types=1);

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

    /**
     * Says has been fixture already loaded
     *
     * @return bool
     */
    public function hasBeenLoaded(): \bool;

    /**
     * Returns name of loader
     *
     * @return string
     */
    public function getName(): \string;
}
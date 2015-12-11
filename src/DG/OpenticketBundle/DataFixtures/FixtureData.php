<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\DataFixtures;


/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class FixtureData implements FixtureDataInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * FixtureData constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Returns fixture data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
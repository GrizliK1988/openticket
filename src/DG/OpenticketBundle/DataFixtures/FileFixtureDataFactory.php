<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\DataFixtures;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Yaml\Yaml;


/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class FileFixtureDataFactory implements FileFixtureDataFactoryInterface
{
    /**
     * @var FileLocatorInterface
     */
    private $fileLocator;

    /**
     * FileFixtureDataFactory constructor.
     * @param FileLocatorInterface $fileLocator
     */
    public function __construct(FileLocatorInterface $fileLocator)
    {
        $this->fileLocator = $fileLocator;
    }

    /**
     * @param string $fixtureFilePath
     * @return FixtureDataInterface
     * @throws \InvalidArgumentException
     */
    public function createFixtureData(\string $fixtureFilePath): FixtureDataInterface
    {
        $filePath = $this->fileLocator->locate($fixtureFilePath);
        $fixtureContents = file_get_contents($filePath);

        $data = Yaml::parse($fixtureContents);

        return new FixtureData($data);
    }
}
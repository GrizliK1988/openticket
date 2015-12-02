<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\Command;


use DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface;
use DG\OpenticketBundle\DataFixtures\FixtureManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class LoadDatabaseFixturesCommand extends Command
{
    /**
     * @var FixtureManagerInterface
     */
    private $fixtureManager;

    /**
     * @var FixtureLoaderInterface[]
     */
    private $loadingFixtures = [];

    /**
     * LoadDatabaseFixturesCommand constructor.
     * @param FixtureManagerInterface $fixtureManager
     */
    public function __construct(FixtureManagerInterface $fixtureManager)
    {
        parent::__construct(null);
        $this->fixtureManager = $fixtureManager;
    }

    protected function configure()
    {
        $this->setName('load:db:fixtures');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $fixtures = $this->fixtureManager->getFixtureLoaders();
        $fixtureNames = array_map('get_class', $fixtures);

        $defaultAnswer = implode(',', range(0, count($fixtureNames)-1));
        $question = new ChoiceQuestion('What fixtures would you like to load? (By default all of them selected)', $fixtureNames, $defaultAnswer);
        $question->setMultiselect(true);
        $this->loadingFixtures = $questionHelper->ask($input, $output, $question);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fixtures = $this->fixtureManager->getFixtureLoaders();
        foreach ($fixtures as $fixture) {
            if (in_array(get_class($fixture), $this->loadingFixtures) || !$input->isInteractive()) {
                $fixture->load();
            }
        }
    }
}
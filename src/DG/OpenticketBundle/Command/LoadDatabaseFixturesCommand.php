<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\Command;


use DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface;
use DG\OpenticketBundle\DataFixtures\FixtureManagerInterface;
use DG\OpenticketBundle\Event\Fixture\BeforeLoadEvent;
use DG\OpenticketBundle\Event\Fixture\RecordLoadEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var ProgressBar
     */
    private $progressBar;

    /**
     * LoadDatabaseFixturesCommand constructor.
     * @param FixtureManagerInterface $fixtureManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(FixtureManagerInterface $fixtureManager, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct(null);
        $this->fixtureManager = $fixtureManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configure()
    {
        $this->setName('load:db:fixtures');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->eventDispatcher->addListener(BeforeLoadEvent::NAME, function (BeforeLoadEvent $event) use ($output) {
            $this->progressBar = new ProgressBar($output, $event->getLoadLength());
            $this->progressBar->start();
        });

        $this->eventDispatcher->addListener(RecordLoadEvent::NAME, function () {
            $this->progressBar->advance();
        });
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $fixtures = $this->fixtureManager->getFixtureLoaders();
        $fixtureNames = array_map(function (FixtureLoaderInterface $fixtureLoader): \string {
            if ($fixtureLoader->hasBeenLoaded()) {
                return sprintf('<error>[loaded already] %s</error>', $fixtureLoader->getName());
            } else {
                return $fixtureLoader->getName();
            }
        }, $fixtures);

        $defaultAnswer = implode(',', range(0, count($fixtureNames)-1));
        $question = new ChoiceQuestion('What fixtures would you like to load? (By default all of them selected)', $fixtureNames, $defaultAnswer);
        $question->setMultiselect(true);
        $this->loadingFixtures = $questionHelper->ask($input, $output, $question);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loadStat = [];
        $fixtures = $this->fixtureManager->getFixtureLoaders();
        foreach ($fixtures as $fixture) {
            if (in_array($fixture->getName(), $this->loadingFixtures) || !$input->isInteractive()) {
                $output->writeln('Loading of ' . $fixture->getName());

                $fixture->load();
                if ($this->progressBar) {
                    $loadStat[] = [$fixture->getName(), $this->progressBar->getMaxSteps()];
                    $this->progressBar->finish();
                    $this->progressBar = null;
                    $output->writeln('');
                }
            }
        }

        $table = new Table($output);
        $table->setHeaders(['Fixture', 'Loaded records']);
        foreach ($loadStat as $fixtureStat) {
            $table->addRow($fixtureStat);
        }
        $table->render();
    }
}
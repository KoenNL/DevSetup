<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\ProjectSettings;
use App\Service\Installer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class CreateProject extends Command
{

    protected static $defaultName = 'create-project';

    private Installer $installer;

    public function __construct(Installer $installer)
    {
        parent::__construct();
        $this->installer = $installer;
    }

    protected function configure()
    {
        $this->setDescription('Create a new project');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $questionHelper = $this->getHelper('question');

        $projectNameQuestion = new Question('What is the name of your new exciting project? ');
        $projectName = $questionHelper->ask($input, $output, $projectNameQuestion);

        $defaultHostname = $this->formatHostname($projectName . '.localhost');
        $hostnameQuestion = new Question('Nice! What hostname would you like to use? ', $defaultHostname);
        $hostname = $questionHelper->ask($input, $output, $hostnameQuestion);
        $hostname = $this->formatHostname($hostname);

        $databaseQuestion = new ConfirmationQuestion('Would you like a database with that? ');
        $needsDatabase = $questionHelper->ask($input, $output, $databaseQuestion);

        $vueQuestion = new ConfirmationQuestion('And a side of VueJS? ');
        $needsVue = $questionHelper->ask($input, $output, $vueQuestion);

        $output->writeln('Your awesome new project settings:');
        $output->writeln('- Name:     ' . $projectName);
        $output->writeln('- Hostname: ' . $hostname);
        $output->writeln('- Database: ' . ($needsDatabase ? 'Yes' : 'No'));
        $output->writeln('- VueJS:    ' . ($needsVue ? 'Yes' : 'No'));

        $confirmCreateQuestion = new ConfirmationQuestion('Is that all? Would you like to create this project now? ');
        $createConfirmation = $questionHelper->ask($input, $output, $confirmCreateQuestion);

        if (!$createConfirmation) {
            $output->writeln('No worries. Come back later when you change your mind!');
            return Command::SUCCESS;
        }

        $projectSettings = new ProjectSettings($projectName, $hostname, $needsDatabase, $needsVue);
        $this->installer->init($projectSettings);

        try {
            foreach ($this->installer->getConfigCreators() as $configCreator) {
                $output->writeln('Creating ' . $configCreator->getName() . '...');
                $configCreator->create($projectSettings);
                $output->writeln($configCreator->getName() . ' created!');
            }

            $output->writeln('Writing to output...');
            $this->installer->finalize($projectSettings);
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
            return Command::FAILURE;
        }

        $output->writeln('Success! Enjoy your new project! You can find it in: ' . $projectSettings->getOutputPath());

        return Command::SUCCESS;
    }

    private function formatHostname(string $hostname): string
    {
        $hostname = strtolower($hostname);
        if (!str_ends_with($hostname, '.localhost')) {
            $hostname .= '.localhost';
        }

        return $hostname;
    }
}

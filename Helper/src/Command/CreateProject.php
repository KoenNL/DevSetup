<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\ProjectSettings;
use App\Recipe\GetAvailableRecipes;
use App\Service\Installer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class CreateProject extends Command
{

    protected static $defaultName = 'create-project';

    private Installer $installer;
    private GetAvailableRecipes $getAvailableRecipes;

    public function __construct(Installer $installer, GetAvailableRecipes $getAvailableRecipes)
    {
        parent::__construct();
        $this->installer = $installer;
        $this->getAvailableRecipes = $getAvailableRecipes;
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

        $availableWebserverRecipes = $this->getAvailableRecipes->getNames($this->getAvailableRecipes->getAvailableWebserverRecipes());
        $webserverQuestion = new ChoiceQuestion('Select your webserver recipe', $availableWebserverRecipes);
        $webserverRecipeClassName = $questionHelper->ask($input, $output, $webserverQuestion);
        $webserverRecipeName = $availableWebserverRecipes[$webserverRecipeClassName];

        $availableDatabaseRecipes = $this->getAvailableRecipes->getNames($this->getAvailableRecipes->getAvailableDatabaseRecipes());
        $availableDatabaseRecipes[0] = 'None';
        $databaseQuestion = new ChoiceQuestion('Select your database recipe', $availableDatabaseRecipes);
        $databaseRecipeClassName = $questionHelper->ask($input, $output, $databaseQuestion);
        $databaseRecipeName = $availableDatabaseRecipes[$databaseRecipeClassName];

        $availableFrontendRecipes = $this->getAvailableRecipes->getNames($this->getAvailableRecipes->getAvailableFrontendRecipes());
        $availableFrontendRecipes[0] = 'None';
        $frontendQuestion = new ChoiceQuestion('Select your frontend recipe', $availableFrontendRecipes);
        $frontendRecipeClassName = $questionHelper->ask($input, $output, $frontendQuestion);
        $frontendRecipeName = $availableFrontendRecipes[$frontendRecipeClassName];

        $output->writeln('Your awesome new project settings:');
        $output->writeln('- Name:      ' . $projectName);
        $output->writeln('- Hostname:  ' . $hostname);
        $output->writeln('- Webserver: ' . $webserverRecipeName);
        $output->writeln('- Database:  ' . $databaseRecipeName);
        $output->writeln('- VueJS:     ' . $frontendRecipeName);

        $confirmCreateQuestion = new ConfirmationQuestion('Is that all? Would you like to create this project now? ');
        $createConfirmation = $questionHelper->ask($input, $output, $confirmCreateQuestion);

        if (!$createConfirmation) {
            $output->writeln('No worries. Come back later when you change your mind!');
            return Command::SUCCESS;
        }

        $projectSettings = new ProjectSettings($projectName, $hostname);

        try {
            $output->writeln('Installing...');
            $this->installer->installFromRecipes(
                $projectSettings,
                [
                    $this->getAvailableRecipes->getByClassName($webserverRecipeClassName),
                    $this->getAvailableRecipes->getByClassName($databaseRecipeClassName),
                    $this->getAvailableRecipes->getByClassName($frontendRecipeClassName),
                ]
            );
            $output->writeln('Finalizing...');
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

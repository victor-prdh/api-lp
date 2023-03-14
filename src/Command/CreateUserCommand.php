<?php

namespace App\Command;

use App\Helper\UserHelper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-user',
    description: 'Create user with command',
)]
class CreateUserCommand extends Command
{

    private UserHelper $userHelper;

    public function __construct(UserHelper $userHelper) {
        $this->userHelper = $userHelper;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var Symfony\Component\Console\Helper\QuestionHelper */
        $helper = $this->getHelper('question');

        $question = new Question('Nom de l\'utilisateur: ', '');
        $name = $helper->ask($input, $output, $question);

        $question = new Question('Prénom de l\'utilisateur: ', '');
        $firstname = $helper->ask($input, $output, $question);

        $question = new Question('Email de l\'utilisateur: ', '');
        $email = $helper->ask($input, $output, $question);

        $question = new Question('Mot de passe de l\'utilisateur: ', '');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $question);

        $user = $this->userHelper->createUser($firstname, $name, $email, $password);


        $io->success(sprintf('user "%s" created !', $user->getEmail()));

        return Command::SUCCESS;
    }
}

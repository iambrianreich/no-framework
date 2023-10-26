<?php

declare(strict_types=1);

namespace NoFramework\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'no-framework:create-entity')]
class NewEntityCommand extends Command {
    protected function execute(InputInterface $input, OutputInterface $output): int {
	$output->writeln('Lets define a new data type');



        $entityName = $this->getEntityName($input, $output);
	$descriptionQuestion = new Question(sprintf('How would you describe the purpose of %s?', $entityName));
	$entityDescription = $questionHelper->ask($input, $output, $descriptionQuestion);
	return Command::SUCCESS;
    }

    protected function getEntityName(InputInterface $input, OutputInterface $output): string {
	$questionHelper = $this->getHelper('question');
	return $this
	    ->getHelper('question')
	    ->ask(
		$input,
		$output,
                new Question(
		    'Name your entity. (A singular noun like "User" or "Document"):'
		)
	    );
    }
}


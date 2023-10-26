<?php

declare(strict_types=1);

namespace NoFramework\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Printer;
use Nette\PhpGenerator\PhpFile;

/**
 * A command for creating a new Entity
 *
 * The NewEntityCommand asks the user interactive questions to get required
 * properties of the entity. The entity name is the common name of the entity
 * that can be used in documentation, and speaking about the entity as $
 * concept. The name should be a singular noun such as "User," "Training
 * Course," or "Administrator." The description provides a description of the
 * entity concept. The entity class name is what the entity will be called in
 * code.
 */
#[AsCommand(name: 'no-framework:create-entity')]
class NewEntityCommand extends Command {
    /**
     * Executes the command.
     *
     * @param InputInterface $input The input interface.
     * @param OutputInterface $output The output interface.
     * @return int Returns success or failure.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
	$output->writeln('Lets define a new data type');

        $entityName = $this->getEntityName($input, $output);
	$entityDescription = $this->getEntityDescription($input, $output, $entityName);
	$entityClassName = $this->getClassNameForEntity($input, $output, $entityName);

	$classType = $this->getClass($entityName, $entityDescription, $entityClassName);

	$printer = new Printer;
	$output->write($printer->printClass($classType));
	return Command::SUCCESS;
    }

    /**
     * Gets the description of the entity from the user.
     *
     * @param InputInterface $input The input interface.
     * @param OutputInterface $output The output interface.
     * @param string $entityName The name of the entity.
     * @return string Returns the entity description.
     */
    protected function getEntityDescription(
	InputInterface $input,
	OutputInterface $output,
        string $entityName
    ): string {
	return $this
	    ->getHelper('question')
	    ->ask(
		$input,
		$output,
		new Question(sprintf('How would you describe the purpose of %s?', $entityName))
	    );
    }

    /**
     * Gets the name of the entity from the user.
     *
     * @param InputInterface $input The input interface.
     * @param OutputInterface $output The output interface.
     * @return string Returns the entity name.
     */
    protected function getEntityName(InputInterface $input, OutputInterface $output): string {
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

    /**
     * Gets the class name for the entity.
     *
     * @param InputInterface $input The input interface.
     * @param OutputInterface $output The output interface.
     * @param string $entityName The name of the entity.
     * @return string Returns the class name for the entity.
     */
    public function getClassNameForEntity(
	InputInterface $input,
	OutputInterface $output,
	string $entityName
    ): string {
	$defaultClassName = $this->getDefaultClassNameForEntity($entityName);

	return $this
	    ->getHelper('question')
	    ->ask(
		$input,
		$output,
		new Question(
		    sprintf(
			'Name the class associated with your entity (%s): ',
			$defaultClassName
		    ),
		    $defaultClassName
		)
	    );
    }

    public function getClass(string $entityName, string $entityDescription, string $entityClassName): ClassType {
	$classType = new ClassType($entityClassName);

	// TODO comments
	// TODO properties
	//
	return $classType;
    }

    public function getDefaultClassNameForEntity(string $entityName): string {
	return str_replace(
	    ' ',
	    '',
	    ucwords(
		preg_replace('/[^A-Za-z]+/', "", $entityName)
	    )
	);
    }

    public function getFile(
	string $entityName,
	string $entityDescription,
	string $entityClassName,
	ClassType $classType
    ): PhpFile {
	$file = new PhpFile();
	$file->addClass($classType);
	return $this->decorateFile($file);
    }

    public function decorateFile(PhpFile $file): PhpFile {
	return $file;
    }
}


<?php


namespace Avolutions\Command;


use Avolutions\Core\Application;

class CreateValidatorCommand extends Command
{
    protected static string $name = 'create-validator';
    protected static string $description = 'Creates a new Validator.';

    public function initialize(): void
    {
        $this->addArgumentDefinition(new Argument('name', 'TODO'));
        $this->addOptionDefinition(new Option('force', 'f', 'TODO'));
    }

    public function execute(): int
    {
        $validatorName = ucfirst($this->getArgument('name'));
        $validatorFullname = $validatorName . 'Validator';
        $validatorFile = Application::getValidatorPath() . $validatorFullname . '.php';

        if (file_exists($validatorFile) && !$this->getOption('force')) {
            $this->Console->writeLine($validatorFullname . ' already exists. If you want to override, please use force mode (-f).', 'error');
            return 0;
        }

        $Template = new Template('validator');
        $Template->assign('namespace', rtrim(Application::getValidatorNamespace(), '\\'));
        $Template->assign('validator', $validatorName);

        if($Template->save($validatorFile)) {
            $this->Console->writeLine('Validator created successfully.', 'success');
            return 1;
        } else {
            $this->Console->writeLine('Error when creating Validator.', 'error');
            return 0;
        }
    }
}
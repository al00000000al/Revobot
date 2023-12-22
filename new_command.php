<?php

$cmd = readline('Enter command name: ');
$cmd_class = ucfirst($cmd) . 'Cmd';
$cmd_class_filename = $cmd_class . '.php';
$path = __DIR__ . '/src/Commands/';

if (file_exists($path . $cmd_class_filename)) {
    die('Commands already exists in ' . $path . $cmd_class_filename);
}

$description = readline('Enter command description: ');
$help_description = readline('Enter description in help cmd: ');
$class_src = cmdTpl($cmd_class, $cmd, $description, $help_description);
file_put_contents($path . $cmd_class_filename, $class_src);
die("\nCreated new cmd {$cmd}!\n");

function cmdTpl($cmd_class, $cmd, $description = '', $help_description = '')
{
    return <<<PHP
<?php

    namespace Revobot\Commands;

    class {$cmd_class} extends BaseCmd
    {
        const KEYS = ['{$cmd}'];
        const IS_ENABLED = true;
        const HELP_DESCRIPTION = '{$help_description}';

        public function __construct(string \$input)
        {
            parent::__construct(\$input);
            \$this->setDescription('/{$cmd} {$description}');
        }

        public function exec(): string
        {
            if (empty(\$this->input)){
                return \$this->description;
            }
            return \$this->input;
        }
    }
PHP;
}

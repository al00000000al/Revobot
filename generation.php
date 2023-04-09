<?php

require 'vendor/autoload.php';

define('COMMANDS_PATH', __DIR__ . '/src/Commands');

processFiles();
function processFiles(){
    $help_str = "";
    $switch = '';
    $commands = [];
    $f = get_dir_files(COMMANDS_PATH);
    foreach($f as $file){
        if(substr(basename($file), -7) !== 'Cmd.php'){
            continue;
        }
        $class =  str_replace('.php', '', basename($file));
        $namespace = str_replace(__DIR__, '', $file);
        $namespace = str_replace(basename($file), '', $namespace);
        $namespace = str_replace('/', '\\', $namespace);
        $namespace = str_replace('\src', 'Revobot', $namespace);
        //echo $namespace;

      $reflector = new ReflectionClass($namespace.$class);
       // echo $namespace . $class . "\n";
        $constants = $reflector->getConstants();
        if(!isset($constants['IS_ENABLED'])){
        continue;
    }
    if(!$constants['IS_ENABLED']){
        continue;
    }
        if(!isset($constants['IS_ADMIN_ONLY'])){
            $help_str .= '/'.$constants['KEYS'][0].' - '.$constants['HELP_DESCRIPTION']."\n";
        }
        $commands = array_merge($commands, $constants['KEYS']);

        try {
            $start_params = array_column($reflector->getConstructor()->getParameters(), 'name');
            $switch .= generateSwitch($namespace.$class, $constants['KEYS'], $start_params);
        }catch(Exception $e){
            continue;
        }
    }

    $helpCmd = generateHelpCmd($help_str);
    $commands_arr = '\''.implode('\',\'', $commands)."'";
    $commandsManager = generateCommandsManager($commands_arr, $switch);
    file_put_contents(COMMANDS_PATH.'/HelpCmd.php', $helpCmd);
    file_put_contents(COMMANDS_PATH.'/../CommandsManager.php', $commandsManager);
    echo "generated: HelpCmd.php, CommandsManager.php\n";
}


function get_dir_files( $dir, $recursive = true, $include_folders = false ){
	if( ! is_dir($dir) )
		return [];

	$files = [];

	$dir = rtrim( $dir, '/\\' ); // удалим слэш на конце

	foreach( glob( "$dir/{,.}[!.,!..]*", GLOB_BRACE ) as $file ){

		if( is_dir( $file ) ){
			if( $include_folders )
				$files[] = $file;
			if( $recursive )
				$files = array_merge( $files, call_user_func( __FUNCTION__, $file, $recursive, $include_folders ) );
		}
		else
			$files[] = $file;
	}

	return $files;
}

function generateHelpCmd($commands){
    return <<<PHP
<?php
/*
  Autogenerated code
*/
namespace Revobot\Commands;

class HelpCmd extends BaseCmd
{
    const KEYS = ['help','хэлп','хлеп', 'помощь'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Помощь';
    /**
     * @return string
     */
    public function exec(): string
    {
        return "Список команд бота:\n{$commands}";
    }
}
PHP;
}

function generateCommandsManager($commands, $switch){
    return <<<TEXT
<?php
/*
  Autogenerated code
*/
namespace Revobot;

class CommandsManager extends CommandsManagerBase
{
    public const COMMANDS = [
        {$commands}
    ];

    /**
     * @param Revobot \$bot
     * @param string \$command
     * @param string \$input
     * @return string
     */
    public static function run(Revobot \$bot, string \$command, string \$input): string
    {
        switch (\$command) {
            {$switch}
            default:
            \$response = '';
        }
        dbg_echo('cmd:' . \$command . ',inp:' . \$input . ',response: ' . \$response . "\\n");
        return \$response;
    }
}

TEXT;
}

function generateSwitch($class, $commands, $start_params){
    $result = '';
    foreach($commands as $cmd){
        $result .= "case '{$cmd}':\n";
    }
    $params = '$'. implode(', $', $start_params);
    $result .= "\t\$response = (new \\{$class}($params))->exec();\n\tbreak;\n";
    return $result;
}

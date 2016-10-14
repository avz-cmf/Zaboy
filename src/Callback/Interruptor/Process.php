<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace zaboy\Callback\Interruptor;

use zaboy\Callback\CallbackException;
use zaboy\Callback\Callback;

/**
 * AnotherProcess
 *
 * @category   callback
 * @package    zaboy
 */
class Process extends Callback
{

    const CALLBACK_KEY = 'callback';
    const VALUE_KEY = 'value';
    //
    const STDOUT_KEY = 'stdout';
    const STDERR_KEY = 'stderr';
    const PID_KEY = 'pid';
    //
    const PATH_SCRIPT_SRC = 'src/Callback/Interruptor/Script/';
    const PATH_SCRIPT_WWW = 'www/Callback/Interruptor/Script/';
    const FILE_NAME = 'process.php';

    public function __invoke($value)
    {
        if (!is_file($this->getScriptName())) {
            throw new CallbackException('Sript "' . $this->getScriptName() . '" does not exist in the folder "Script"');
        }
        $cmd = 'php ' . $this->getScriptName();

        $arrayParams = [
            self::VALUE_KEY => $value,
            self::CALLBACK_KEY => $this->getCallback()
        ];
        $serializedParams = serialize($arrayParams);
        $params64 = base64_encode($serializedParams);
        $cmd .= ' ' . $params64;

        // Files names for stdout and stderr
        $result[self::STDOUT_KEY] = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('stdout_', 1);
        $result[self::STDERR_KEY] = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('stderr_', 1);
        $cmd .= "  1>{$result[self::STDOUT_KEY]} 2>{$result[self::STDERR_KEY]}";

        if (substr(php_uname(), 0, 7) !== "Windows") {
            $cmd .= " & echo $!";
        }
        var_dump($cmd);
        //from apache
        //$command = 'nohup '.$this->command.' > /dev/null 2>&1 & echo $!';
        $result[self::PID_KEY] = trim(shell_exec($cmd));
        return $result;

//        $errors = $this->parser->parseFile($stdErrFilename);
//        $output = $this->parser->parseFile($stdOutFilename);
//
//        if ($errors['fatalStatus']) {
//            throw new CallbackException($errors['message']);
//        }
//        return $output['message'];
    }

    protected function getScriptName()
    {
        if (!file_exists(self::PATH_SCRIPT_WWW . self::FILE_NAME)) {
            throw new \RuntimeException(
            'File ' . self::FILE_NAME, ' is not exist in ' . self::PATH_SCRIPT_WWW
            );
        }
        return self::PATH_SCRIPT_WWW . self::FILE_NAME;
    }

    /**
     * Checks an environment where this script was run
     *
     * It's not allowed to run in Windows
     *
     * @throws CallbackException
     */
    protected function checkEnvironment()
    {
        if ('Windows' == substr(php_uname(), 0, 7)) {
            throw new CallbackException("This callback type will not work in Windows");
        }
        if (!function_exists('shell_exec')) {
            throw new CallbackException("The function \"shell_exec\" does not exist or it is not allowed.");
        }
        if (!function_exists('posix_kill')) {
            throw new CallbackException("The function \"posix_kill\" does not exist or it is not allowed.");
        }
    }

}
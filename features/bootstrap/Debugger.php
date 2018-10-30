<?php

declare(strict_types = 1);

final class Debugger
{
    private const COMMAND_PREFIX = '$';
    private const RETURN_CODE_PREFIX = 'code:';
    private const STDOUT_PREFIX = '>';
    private const STDERR_PREFIX = 'err:';

    /**
     * @var string
     */
    private $output = '';

    public function writeCommand(string $command): void
    {
        $this->writeLines($command, self::COMMAND_PREFIX);
    }

    public function writeResult(Result $result): void
    {
        $this->writeLines($result->getOutput(), self::STDOUT_PREFIX);
        $this->writeLines($result->getErrorOutput(), self::STDERR_PREFIX);
        $this->writeLines((string)$result->getReturnCode(), self::RETURN_CODE_PREFIX);
    }

    public function getOutput(): string
    {
        return $this->output;
    }

    private function writeLines(string $lines, string $prefix): void
    {
        foreach (explode(PHP_EOL, $lines) as $line) {
            if (!empty($line)) {
                $this->output .= sprintf(
                    '%s %s%s',
                    $prefix,
                    $line,
                    PHP_EOL
                );
            }
        }
    }
}

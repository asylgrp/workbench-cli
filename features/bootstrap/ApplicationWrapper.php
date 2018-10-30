<?php

declare(strict_types = 1);

final class ApplicationWrapper
{
    /**
     * @var Debugger
     */
    private $debugger;

    /**
     * @var string
     */
    private $executable;

    /**
     * @var string
     */
    private $flags;

    /**
     * @var string
     */
    private $cwd;

    public function __construct(string $executable, string $flags, string $cwd)
    {
        $this->debugger = new Debugger;
        $this->executable = $executable;
        $this->flags = $flags;
        $this->cwd = $cwd;
    }

    public function __call(string $command , array $arguments): Result
    {
        return $this->execute("$command " . implode(' ', $arguments));
    }

    public function execute(string $command): Result
    {
        $this->debugger->writeCommand($command);

        $process = proc_open(
            "{$this->executable} $command {$this->flags}",
            [
                1 => ["pipe", "w"],
                2 => ["pipe", "w"]
            ],
            $pipes,
            $this->cwd
        );

        $output = stream_get_contents($pipes[1]);
        $errorOutput = stream_get_contents($pipes[2]);

        $result = new Result(
            proc_close($process),
            $output,
            $errorOutput
        );

        $this->debugger->writeResult($result);

        return $result;
    }

    public function getDebugOutput(): string
    {
        return $this->debugger->getOutput();
    }

    public function createFile(string $content): string
    {
        $filename = uniqid();
        file_put_contents("{$this->cwd}/$filename", $content);

        return $filename;
    }
}

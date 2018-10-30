<?php

declare(strict_types = 1);

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Hook\Scope\AfterScenarioScope;

final class FeatureContext implements Context
{
    /**
     * @var ApplicationWrapper
     */
    private $app;

    /**
     * @var string
     */
    private $cwd;

    /**
     * @var string
     */
    private $executable;

    /**
     * @var string
     */
    private $flags;

    /**
     * @var Result
     */
    private $result;

    public function __construct(string $executable, string $flags = '--no-interaction --no-ansi -vvv')
    {
        $this->executable = realpath(getcwd() . '/' . $executable);
        $this->flags = $flags;
    }

    /**
     * @BeforeScenario
     */
    public function setupApplication()
    {
        $this->cwd = sys_get_temp_dir() . '/workbench_cli_tests_' . time();
        mkdir($this->cwd);
        mkdir($this->cwd . '/.workbench');

        $this->app = new ApplicationWrapper(
            $this->executable,
            $this->flags . " --config='.workbench'",
            $this->cwd
        );
    }

    /**
     * @AfterScenario
     */
    public function tearDownApplication(AfterScenarioScope $scope)
    {
        if (!$scope->getTestResult()->isPassed()) {
            echo $this->app->getDebugOutput();
        }

        if (is_dir($this->cwd)) {
            exec("rm -rf {$this->cwd}", $output, $return);
        }
    }

    /**
     * @Given an organization named :org
     */
    public function anOrganizationNamed($org)
    {
        $this->result = $this->app->init("--org-name='$org'");
    }

    /**
     * @When I import a nonexistent file
     */
    public function iImportANonexistentFile()
    {
        $this->result = $this->app->import('this-file-does-not-exist');
    }

    /**
     * @When I import:
     */
    public function iImport(PyStringNode $content)
    {
        $this->result = $this->app->import(
            $this->app->createFile((string)$content)
        );
    }

    /**
     * @When I generate a ledger
     */
    public function iGenerateALedger()
    {
        $this->result = $this->app->book();
    }

    /**
     * @When I inspect the storage
     */
    public function iInspectTheStorage()
    {
        $this->result = $this->app->store();
    }

    /**
     * @When I remove :key from the storage
     */
    public function iRemoveFromTheStorage($key)
    {
        $this->result = $this->app->store("--rm='$key'");
    }

    /**
     * @When I clear the storage
     */
    public function iClearTheStorage()
    {
        $this->result = $this->app->store("--clear");
    }

    /**
     * @Then the storage contains :string
     */
    public function theStorageContains($string)
    {
        $this->iInspectTheStorage();
        $this->theOutputContains($string);
    }

    /**
     * @Then the storage does not contain :string
     */
    public function theStorageDoesNotContain($string)
    {
        $this->iInspectTheStorage();
        $this->theOutputDoesNotContain($string);
    }

    /**
     * @Then I get an error
     */
    public function iGetAnError()
    {
        if (!$this->result->isError()) {
            throw new \Exception('Invocation should result in an error');
        }
    }

    /**
     * @Then the output contains :string
     */
    public function theOutputContains($string)
    {
        if (!preg_match("/$string/i", $this->result->getOutput())) {
            throw new \Exception("Unable to find '$string' in output");
        }
    }

    /**
     * @Then the output does not contain :string
     */
    public function theOutputDoesNotContain($string)
    {
        if (preg_match("/$string/i", $this->result->getOutput())) {
            throw new \Exception("'$string' should not be in output");
        }
    }
}

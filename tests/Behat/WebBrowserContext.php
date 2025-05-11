<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Kernel;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Step\Then;
use Behat\Step\When;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Webmozart\Assert\Assert;

class WebBrowserContext implements Context
{
    private KernelBrowser $client;

    public function __construct()
    {
        $kernel = new Kernel('test', true);
        $kernel->boot();

        $this->client = new KernelBrowser($kernel);
        $this->client->setServerParameter('HTTP_ACCEPT', 'application/json');
    }

    #[When('I send a :method request to :path')]
    public function iSendAGetRequestTo(string $method, string $path): void
    {
        $this->client->request(
            $method,
            $path,
        );
    }

    #[When('I send a :method request to :path with payload:')]
    public function iSendAPostRequestTo(
        string $method,
        string $path,
        TableNode $table
    ): void {
        $this->client->request(
            $method,
            $path,
            content: json_encode($table->getRowsHash()),
        );
    }

    #[Then('the response status code should be :expected')]
    public function theResponseStatusCodeShouldBe(int $expected): void
    {
        $actual = $this->client->getResponse()->getStatusCode();
        Assert::same($actual, $expected);
    }

    #[Then('the response content should be collection of :length elements')]
    public function theResponseContentShouldBeCollectionOfElements(int $length): void
    {
        $response = $this->client->getResponse()->getContent();
        $value = json_decode($response, true, flags: JSON_THROW_ON_ERROR);
        Assert::isArray($value);
        Assert::count($value, $length);
    }

    #[Then('the response content should be an object with payload:')]
    public function theResponseContentShouldBeAnObjectWithPayload(TableNode $tableNode): void
    {
        $response = $this->client->getResponse()->getContent();
        $value = json_decode($response, true, flags: JSON_THROW_ON_ERROR);
        Assert::isArray($value);
        foreach ($tableNode->getRowsHash() as $expectedKey => $expectedValue) {
            Assert::keyExists($value, $expectedKey);
            Assert::same((string)$value[$expectedKey], (string)$expectedValue);
        }
    }
}

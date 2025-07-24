<?php

namespace App\SharedKernel\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

readonly class GeminiAIService
{
    private const URL = 'https://generativelanguage.googleapis.com/v1beta/models';
    private const MODEL = 'gemini-2.5-flash-lite';
    private const GENERATE_CONTENT_SUFFIX = 'generateContent';

    private Client $client;

    public function __construct(
        private string $apiKey,
        private LoggerInterface $logger,
    ) {
        $this->client = new Client(
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]
        );
    }

    public function generate(
        array $parts,
        array $schema,
        bool $associative = true,
    ): ?array {
        try {
            $response = $this->client->post(
                $this->getUri(),
                [
                    'json' => [
                        'contents' => [
                            'parts' => $parts,
                        ],
                        'generationConfig' => [
                            'responseMimeType' => 'application/json',
                            'responseSchema' => $schema,
                            'candidateCount' => 1,
                        ],
                    ]
                ],
            );
        } catch (RequestException $e) {
            $this->logger->error(
                'There was an error while generating the response with API.',
                [
                    'message' => $e->getMessage(),
                    'response' => $e->getResponse()->getBody()->getContents(),
                    'trace' => $e->getTraceAsString(),
                ]
            );
            return null;
        }

        try {
            $jsonData = json_decode(
                $response->getBody()->getContents(),
                true,
                flags: JSON_THROW_ON_ERROR,
            );

            return $this->parseJson($jsonData, $associative);
        } catch (\JsonException $e) {
            $this->logger->error(
                'There was an error while decoding the response.',
                [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );
            return null;
        }
    }

    private function getUri(): string
    {
        return sprintf(
            '%s/%s:%s?key=%s',
            self::URL,
            self::MODEL,
            self::GENERATE_CONTENT_SUFFIX,
            $this->apiKey,
        );
    }

    private function parseJson(array $jsonData, bool $associative): mixed
    {
        $nestedData = $jsonData['candidates'][0]['content']['parts'][0]['text'] ?? null;
        if (!$nestedData) {
            return null;
        }

        return json_decode(
            $nestedData,
            $associative,
            flags: JSON_THROW_ON_ERROR
        );
    }
}
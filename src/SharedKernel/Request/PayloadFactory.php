<?php

namespace App\SharedKernel\Request;


use App\SharedKernel\Exception\InvalidPayload;
use Symfony\Component\HttpFoundation\Request;

readonly class PayloadFactory
{
    /**
     * @throws InvalidPayload
     */
    public static function fromRequest(Request $request): array
    {
        try {
            return json_decode(
                $request->getContent(),
                true,
                flags: JSON_THROW_ON_ERROR,
            );
        } catch (\JsonException) {
            throw new InvalidPayload();
        }
    }
}
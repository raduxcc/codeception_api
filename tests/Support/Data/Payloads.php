<?php

namespace Tests\Support\Data;

use Faker\Factory;

class Payloads
{
    public const FULL_PAYLOAD = [
        'mbId' => '9001',
        'initials' => 'TM',
        'name' => 'Test Media Buyer',
        'email' => 'test.media.buyer@example.com',
        'slackUserId' => 'U05AZ3DQBBKK',
        'active' => 1,
    ];
    public const VALID_NO_INITIALS = [
        'mbId' => '9001',
        'name' => 'Test Media Buyer',
        'email' => 'test.media.buyer@example.com',
        'slackUserId' => 'U05AZ3DQBBKK',
        'active' => 1,
    ];

    public const VALID_NO_SLACKUSERID = [
        'mbId' => '9001',
        'initials' => 'TM',
        'name' => 'Test Media Buyer',
        'email' => 'test.media.buyer@example.com',
        'active' => 1,
    ];

    public const VALID_JUST_REQUIRED = [
        'mbId' => '9001',
        'name' => 'Test Media Buyer',
        'email' => 'test.media.buyer@example.com',
        'active' => 1,
    ];

    /**
     * Dynamically generates a fresh, within constraints payload on every call
     */
    public static function fullFakerPayload(): array
    {
        $faker = Factory::create();
        $microtime = microtime(true);
        // remove the decimal point and keep 3 decimal places for milliseconds to eliminate chance of unique-constraint collisions
        $msTimestamp = sprintf('%0.0f', $microtime * 1000);

        return [
            'mbId'        => $msTimestamp,
            'initials'    => strtoupper($faker->lexify('??')),  // Generates 2 random letters (e.g., 'TM')
            'name'        => $faker->name(),
            'email'       => $msTimestamp . '@testemail.com',
            'slackUserId' => strtoupper($faker->bothify('U##########')), // Generates standard 11-char Slack ID format
            'active'      => random_int(0, 1),
        ];
    }
}
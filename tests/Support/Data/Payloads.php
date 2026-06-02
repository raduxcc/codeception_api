<?php

namespace Tests\Support\Data;

use Faker\Factory;
use Faker\Generator;

class Payloads
{
    private static ?Generator $faker = null;
    private array $attributes;

    public function __construct()
    {
        // keeps Faker performance optimized inside the thread process
        if (self::$faker === null) {
            self::$faker = Factory::create();
        }

        $microtime = microtime(true);
        $msTimestamp = sprintf('%0.0f', $microtime * 1000);

        $this->attributes = [
            'mbId'        => $msTimestamp,
            'initials'    => strtoupper(self::$faker->lexify('??')),
            'name'        => self::$faker->name(),
            'email'       => "test.buyer.{$msTimestamp}@example.com",
            'slackUserId' => 'U' . substr($msTimestamp, -10),
            'active'      => random_int(0, 1),
        ];
    }

    public function with(string $key, mixed $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function without(string ...$keys): self
    {
        foreach ($keys as $key) {
            unset($this->attributes[$key]);
        }
        return $this;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    //    // get independent values
//    public function getMbId(): string       { return $this->attributes['mbId']; }
//    public function getEmail(): string      { return $this->attributes['email']; }
//    public function getInitials(): string   { return $this->attributes['initials']; }
//    public function getName(): string       { return $this->attributes['name']; }
//    public function getSlackUserId(): string { return $this->attributes['slackUserId']; }
//    public function getActive(): int        { return $this->attributes['active']; }



}
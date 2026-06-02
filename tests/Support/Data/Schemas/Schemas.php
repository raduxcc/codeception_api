<?php

namespace Support\Data\Schemas;

class Schemas
{
    public const POST_INVARIABLE_400_RESULT = [
        'errors' => [
            ['detail' => 'This field is missing: [name]'],
            ['detail' => 'The email not-an-email is not a valid email.']
        ]
    ];

    public static function postValidationErrorPattern(string $errorMessage): array
    {
        return [
            'errors' => [
                [
                    'detail' => $errorMessage
                ]
            ]
        ];
    }

}
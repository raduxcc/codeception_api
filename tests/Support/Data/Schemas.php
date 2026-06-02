<?php

namespace Tests\Support\Data;

class Schemas
{
    public const GET_RESPONSE_LIGHT = [
        'id'          => 'string',
        'title'       => 'string',
        'price'       => 'integer|float',
        'description' => 'string',
        'category'    => 'string',
        'image'       => 'string',
        'email'       => 'string:regex(~^[^@\s]+@[^@\s]+\.[^@\s]{3}+$~)',
    ];

    public const GET_RESPONSE = [
        'type' => 'object',
        'properties' => [
            'data' => [
                'type' => 'array',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'id'  => ['type' => 'integer'],
                        'mbId' => ['type' => 'string'],
                        'initials' => [
                            'type' => 'string',
                            'minLength' => '2',
                            'maxLength' => '2'
                            ],
                        'name' => [
                            'type' => 'string',
                            'minLength' => 2,
                            'maxLength' => 30
                        ],
                        'email' => [
                            'type' => 'string',
                            'format' => 'email'
//                            'pattern' => '^[^@\s]+@[^@\s]+\.[^@\s]+$'
                             # "practical RFC-compliant pattern that doesn't include obsolete edge cases" -- not working as expected
//                            'pattern' => '^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$'
                        ],
                        'slackUserId' => [
                            'type' => 'string',
                        ],
                        'active' => [
                            'type' => 'integer',
                            'enum' => [0, 1]
                        ]
                    ],
                    // mandatory keys
                    'required' => ['id', 'mbId', 'initials', 'name', 'email', 'slackUserId', 'active']
                ]
            ]
        ],
        // mandatory key at root level
        'required' => ['data']
    ];


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
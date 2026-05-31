<?php

declare(strict_types=1);

namespace Tests\Api;

use Codeception\Attribute\Group;
use Tests\Support\ApiTester;
use Tests\Support\Data\Schemas;

class getMediabuyersCest
{
    private const lightDataEndpoint = '/api/mediabuyers';
    private const extendedDataEndpoint = '/api/mediabuyers/extended';

    // Code here will be executed before each test function.
    public function _before(ApiTester $I): void
    {
        $I->sendGet(self::extendedDataEndpoint);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    #[Group('local')]
    public function getFullListOfMediaBuyers(ApiTester $I): void
    {
        $I->seeResponseIsValidOnJsonSchemaString(json_encode(Schemas::GET_RESPONSE));
    }

    #[Group('local')]
    public function dataFieldIsAlwaysAnArray(ApiTester $I): void
    {
        $response = json_decode($I->grabResponse(), true);
//        fwrite(STDOUT, "\n>>> response: " . json_encode($response) . "\n");
//        fwrite(STDOUT, "\n>>> data: " . json_encode($response['data']) . "\n");
        $I->assertIsArray(
            $response['data'],
//            'Failed validation: \'data\' should be an array'
        );
    }

    public function validateEmailField(ApiTester $I): void
    {
        $I->seeResponseIsValidOnJsonSchemaString(json_encode(Schemas::GET_RESPONSE));
    }

    #[Group('local')]
    public function validateActiveField(ApiTester $I): void
    {
        // codeception methods are magical
        $ids = $I->grabDataFromResponseByJsonPath('$.data[*].id');
//        fwrite(STDOUT, "\n>>> \$ids: " . json_encode($ids) . "\n");

        $I->assertEquals(
            count($ids),
            count(array_unique($ids)),
            'Failed validation: current response contains duplicate ids'
        );
    }


}

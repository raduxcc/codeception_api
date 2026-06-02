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

    public function _before(ApiTester $I): void
    {
        $I->sendGet(self::extendedDataEndpoint);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeHttpHeader('Content-Type', 'application/json');

        $rawResponse = $I->grabResponse();
        $prettyJson = json_encode(json_decode($rawResponse), JSON_PRETTY_PRINT);
        // inject it into the HTML report steps
        $I->comment("--- API RESPONSE BODY ---\n\n" . $prettyJson);
    }

    #[Group('get', 'positive', 'sanity')]
    public function getRequestHappyPathForListOfMediaBuyers(ApiTester $I): void
    {
        $I->seeResponseIsValidOnJsonSchemaString(json_encode(Schemas::GET_RESPONSE));
    }

    #[Group('get', 'sanity')]
    public function validateDataFieldIsAlwaysAnArray(ApiTester $I): void
    {
        $response = json_decode($I->grabResponse(), true);
        $I->assertIsArray(
            $response['data'],
            'Failed validation: \'data\' should be an array'
        );
    }

    /**
     * @throws \Exception
     */
    #[Group('get', 'sanity')]
    public function validateIdIsUniqueWithinCurrentApiResponse(ApiTester $I): void
    {
        $ids = $I->grabDataFromResponseByJsonPath('$.data[*].id');

        $I->assertEquals(
            count($ids),
            count(array_unique($ids)),
            'Failed validation: current response contains duplicate ids'
        );
    }


}

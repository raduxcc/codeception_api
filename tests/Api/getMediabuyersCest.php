<?php

declare(strict_types=1);

namespace Tests\Api;

use Codeception\Attribute\Group;
use Tests\Support\ApiTester;

class getMediabuyersCest
{
    private const lightDataEndpoint = '/api/mediabuyers'; #response contains 1 obj
    private const extendedDataEndpoint = '/api/mediabuyers/extended'; #response contains 4 obj

    private const getSchema = 'Schemas/get-media-buyers-schema.json';

    # mockfly is weirdly vague about response headers, could not overwrite
//    private const responseHeaderValue = 'application/json';
    private const responseHeaderValue = 'application/json; charset=utf-8';

    public function _before(ApiTester $I): void
    {
        $I->sendGet(self::extendedDataEndpoint);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeHttpHeader('Content-Type', self::responseHeaderValue);
    }

    #[Group('get', 'positive', 'sanity', 'yyy')]
    public function getRequestHappyPathForListOfMediaBuyers(ApiTester $I): void
    {
        $I->seeResponseIsValidOnJsonSchema(codecept_data_dir(self::getSchema));
//        $rawResponse = $I->grabResponse();
//        $prettyJson = json_encode(json_decode($rawResponse), JSON_PRETTY_PRINT);
//        // inject it into the HTML report steps
//        $I->comment("--- API RESPONSE BODY ---\n\n" . $prettyJson);
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

<?php

declare(strict_types=1);

namespace Tests\Api;

use Codeception\Attribute\Examples;
use Codeception\Attribute\Group;
use Codeception\Example;
use Support\Data\Schemas\Schemas;
use Tests\Support\ApiTester;
use Tests\Support\Data\Payloads;

class postMediabuyersCest
{
    private const apiEndpoint = '/api/mediabuyers';
    private const extendedDataEndpoint = '/api/mediabuyers/extended';
    private const postPositiveSchema = 'Schemas/post-media-buyer-schema.json';

//    private const expectedHeaders = 'application/json';
    private const expectedHeaders = 'application/json; charset=utf-8';

    #[Group('post', 'positive')]
    #[Examples(removeKeys: [], type: 'Full valid payload')]
    #[Examples(removeKeys: ['initials'], type: 'All fields, missing "initials"')]
    #[Examples(removeKeys: ['slackUserId'], type: 'All fields, missing "slackUserId"')]
    #[Examples(removeKeys: ['initials', 'slackUserId'], type: 'Only mandatory fields')]
    public function testMediaBuyerCreationWithValidPayloads(ApiTester $I, Example $data): void
    {
        // custom header for api to respond with expected schema
        $I->haveHttpHeader('expecting', '200');

        // dynamically build payload, excluding optional keys
        $payload = (new Payloads())->without(...$data['removeKeys'])->toArray();

        $I->sendPost(self::apiEndpoint, $payload);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeHttpHeader('Content-Type', self::expectedHeaders); #
        $I->seeResponseIsValidOnJsonSchema(codecept_root_dir(self::postPositiveSchema));
    }

    #[Group('post', 'negative')]
    #[Examples(delta: ['mbId' => null], errorMessage: 'This field is missing: mbID')] #required
    #[Examples(delta: ['mbId' => ''], errorMessage: 'Invalid field value: mbID')]
    #[Examples(delta: ['mbId' => ' '], errorMessage: 'Invalid field value: mbID')]
    #[Examples(delta: ['mbId' => 'abc'], errorMessage: 'Invalid field value: mbID')]

    #[Examples(delta: ['initials' => ''], errorMessage: 'Invalid field value: initials')]
    #[Examples(delta: ['initials' => ' '], errorMessage: 'Invalid field value: initials')]
    #[Examples(delta: ['initials' => '99'], errorMessage: 'Invalid field value: initials')]
    #[Examples(delta: ['initials' => 'A'], errorMessage: 'The initials must be exactly 2 characters long.')]
    #[Examples(delta: ['initials' => 'BBB'], errorMessage: 'The initials must be exactly 2 characters long.')]
    #[Examples(delta: ['initials' => 'TOO LONG'], errorMessage: 'The initials must be exactly 2 characters long.')]

    #[Examples(delta: ['name' => null], errorMessage: 'This field is missing: name')] #required
    #[Examples(delta: ['name' => ''], errorMessage: 'Invalid field value: name')]
    #[Examples(delta: ['name' => '  '], errorMessage: 'Invalid field value: name')]
    #[Examples(delta: ['name' => '11'], errorMessage: 'Invalid field value: name')]
    #[Examples(delta: ['name' => 'A'], errorMessage: '"name" should be between 2 and 30 characters (inclusive).')]
    #[Examples(delta: ['name' => 'thirtyonecharactersaaaaaaaaaaaa'], errorMessage: '"name" should be between 2 and 30 characters (inclusive).')]

    #[Examples(delta: ['email' => null], errorMessage: 'This field is missing: email')] #required
    #[Examples(delta: ['email' => ''], errorMessage: 'Invalid field value: email')]
    #[Examples(delta: ['email' => ' '], errorMessage: 'Invalid field value: email')]
    #[Examples(delta: ['email' => 'justusername'], errorMessage: 'Invalid field value: email')]
    #[Examples(delta: ['email' => 'username_symbol@'], errorMessage: 'Invalid field value: email')]
    #[Examples(delta: ['email' => 'username_symbol@gmail'], errorMessage: 'Invalid field value: email')]
    #[Examples(delta: ['email' => 'username_symbol@.com'], errorMessage: 'Invalid field value: email')]
    #[Examples(delta: ['email' => '@gmail'], errorMessage: 'Invalid field value: email')]
    #[Examples(delta: ['email' => '@gmail.com'], errorMessage: 'Invalid field value: email')]
    #[Examples(delta: ['email' => 'test name@example.com'], detail: 'Invalid field value: email')]
    #[Examples(delta: ['email' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa@example.com'], detail: 'The email should not be greater than 254 characters.')]
    #[Examples(delta: ['email' => 'not-an-email'], errorMessage: 'The email not-an-email is not a valid email.')]

    #[Examples(delta: ['slackUserId' => ''], errorMessage: '"slackUserId" should be between 1 and 32 characters (inclusive).')]
    #[Examples(delta: ['slackUserId' => ' '], errorMessage: 'Invalid field value: slackUserId')]
    #[Examples(delta: ['slackUserId' => '33characterslongggggggggggggggggg'], errorMessage: '"slackUserId" should be between 1 and 32 characters (inclusive).')]

    #[Examples(delta: ['active' => null], errorMessage: 'This field is missing: active')] #required
    #[Examples(delta: ['active' => ''], errorMessage: 'Invalid field value: active')]
    #[Examples(delta: ['active' => ' '], errorMessage: 'Invalid field value: active')]
    #[Examples(delta: ['active' => 0 ], errorMessage: 'Invalid field value: active')]
    #[Examples(delta: ['active' => 1 ], errorMessage: 'Invalid field value: active')]
    #[Examples(delta: ['active' => 'A' ], errorMessage: 'Invalid field value: active')]
    public function testMediaBuyerCreationBoundaries(ApiTester $I, Example $data): void
    {
        // custom header for api to respond with expected schema
        $I->haveHttpHeader('expecting', '400');

        // dynamically generate payload, overwrite keys according to delta
        $payload = array_merge((new Payloads())->toArray(), $data['delta']);

        $I->sendPost(self::apiEndpoint, $payload);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeHttpHeader('Content-Type', self::expectedHeaders);

        $I->seeResponseContainsJson(Schemas::postValidationErrorPattern($data['errorMessage']));
    }

    #[Group('post', 'negative')]
    #[Examples(removeKeys: ['mbId'], type: 'mbId not part of payload')]
    #[Examples(removeKeys: ['name'], type: 'name not part of payload')]
    #[Examples(removeKeys: ['email'], type: 'email not part of payload')]
    #[Examples(removeKeys: ['active'], type: 'active not part of payload')]
    #[Examples(removeKeys: ['mbId', 'name'], type: 'mbId, name not part of payload')]
    #[Examples(removeKeys: ['mbId', 'name', 'email'], type: 'mbId, name, email not part of payload')]
    public function testMediaBuyerCreationBoundariesByNotSendingRequiredFields(ApiTester $I, Example $data): void
    {
        // custom header for api to respond with expected schema
        $I->haveHttpHeader('expecting', '400');

        // dynamically build payload, excluding optional keys
        $payload = (new Payloads())->without(...$data['removeKeys'])->toArray();

        $I->sendPost(self::apiEndpoint, $payload);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeHttpHeader('Content-Type', self::expectedHeaders);
        $I->seeResponseIsValidOnJsonSchema(codecept_data_dir(self::postPositiveSchema));
    }

    #[Group('post', 'sanity')]
    public function testIdIsPositiveInteger(ApiTester $I): void
    {
        $payload = (new Payloads())->toArray();

        $I->sendPost(self::apiEndpoint, $payload);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $returnedId = $I->grabDataFromResponseByJsonPath('$.data.id')[0];

        $I->assertTrue(
            is_int($returnedId),
            'Expected data.id to be an integer');
        $I->assertGreaterThan(0,
            $returnedId,
            'Expected data.id to be a positive integer greater than 0');
    }

    #[Group('post', 'sanity', 'collision')]
    public function testUniquenessConstraintsAreEnforcedOnMBID(ApiTester $I): void
    {
        $I->sendGet(self::extendedDataEndpoint);
        $I->seeResponseCodeIs(200);

        # validate response body > 0
        $response = json_decode($I->grabResponse(), true);
        $I->assertGreaterThan(0, strlen(implode($response)), 'API response body is empty!');

        $mbId = $I->grabDataFromResponseByJsonPath('$.data[0].mbId');
        $mbIdString = $mbId[0];

        $payload = array_merge((new Payloads())->toArray(), ['mbId' => $mbIdString]);
        $I->sendPost(self::apiEndpoint, $payload);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeHttpHeader('Content-Type', self::expectedHeaders);
        $I->seeResponseContainsJson(
            Schemas::postValidationErrorPattern('Record with mbId ' . $mbIdString . ' already exists.'));
    }

    #[Group('post', 'sanity', 'collision')]
    public function testUniquenessConstraintsAreEnforcedOnEmail(ApiTester $I): void
    {
        $I->sendGet(self::extendedDataEndpoint);
        $I->seeResponseCodeIs(200);

        $response = json_decode($I->grabResponse(), true);
        $I->assertGreaterThan(0, strlen(implode($response)), 'API response body is empty!');

        $email = $I->grabDataFromResponseByJsonPath('$.data[0].email');
        $emailString = $email[0];

        $payload = array_merge((new Payloads())->toArray(), ['emailString' => $emailString]);
        $I->sendPost(self::apiEndpoint, $payload);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeHttpHeader('Content-Type', self::expectedHeaders);

        $I->seeResponseContainsJson(
            Schemas::postValidationErrorPattern('Record with email ' . $emailString . ' already exists.'));
    }


}

<?php

declare(strict_types=1);

namespace Api;

use Codeception\Attribute\Group;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use Tests\Support\ApiTester;
use Tests\Support\Data\Payloads;
use Tests\Support\Data\Schemas;
use Tests\Support\DataProviders\MediaBuyerContractProvider;
use Codeception\Attribute\Examples;

class postMediabuyersCest
{
    private const apiEndpoint = '/api/mediabuyers';
    private const extendedDataEndpoint = '/api/mediabuyers/extended';

    #[Group('spec')]
    #[Examples(delta: ['mbId' => null], errorMessage: 'This field is missing: mbID')] #required
    #[Examples(delta: ['mbId' => ''], errorMessage: 'Invalid field value: mbID')]
    #[Examples(delta: ['mbId' => ' '], errorMessage: 'Invalid field value: mbID')]
    #[Examples(delta: ['mbId' => '42'], errorMessage: 'Record already exists')] # collision test
    #[Examples(delta: ['mbId' => 'AAAAA'], errorMessage: 'Invalid field value: mbID')]

    #[Examples(delta: ['initials' => ''], errorMessage: 'Invalid field value: initials')]
    #[Examples(delta: ['initials' => ' '], errorMessage: 'Invalid field value: initials')]
    #[Examples(delta: ['initials' => '99'], errorMessage: 'Invalid field value: initials')]
    #[Examples(delta: ['initials' => 'A'], errorMessage: '"initials" should be 2 characters long.')]
    #[Examples(delta: ['initials' => 'BBB'], errorMessage: '"initials" should be 2 characters long.')]

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
        $payload = [...Payloads::fullFakerPayload(), ...$data['delta']];

        $I->sendPost(self::apiEndpoint, $payload);
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson(Schemas::postValidationErrorPattern($data['errorMessage']));
    }

    #[Group('xxx')]
    public function testUniquenessConstraintsAreEnforcedOnMBID(ApiTester $I): void
    {
        $I->sendGet(self::extendedDataEndpoint);
        $I->seeResponseCodeIs(200);

        $mbId = $I->grabDataFromResponseByJsonPath('$.data[0].mbId');
        $mbIdString = $mbId[0];
        fwrite(STDOUT, "\n>>> ouuut: " . $mbIdString . "\n");

        $payload = [...Payloads::fullFakerPayload(), ...['mbId' => $mbIdString]];
        $I->sendPost(self::apiEndpoint, $payload);
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson(
            Schemas::postValidationErrorPattern('Record with mbId ' . $mbIdString . ' already exists.'));
    }

    #[Group('xxx')]
    public function testUniquenessConstraintsAreEnforcedOnEmail(ApiTester $I): void
    {
        $I->sendGet(self::extendedDataEndpoint);
        $I->seeResponseCodeIs(200);
        # assuming expected response based on HTTP200
        $email = $I->grabDataFromResponseByJsonPath('$.data[0].email');
        $emailString = $email[0];
        fwrite(STDOUT, "\n>>> ouuut: " . $emailString . "\n");

        $payload = [...Payloads::fullFakerPayload(), ...['mbId' => $emailString]];
        $I->sendPost(self::apiEndpoint, $payload);
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson(
            Schemas::postValidationErrorPattern('Record with email ' . $emailString . ' already exists.'));
    }

//    #[Group('smoke', 'someGroup')]
//    public function postPositiveFullPayload(ApiTester $I): void
//    {
//        $I->sendPost(self::apiEndpoint, Payloads::FULL_PAYLOAD);
//        $I->seeResponseCodeIs(200);
//        $I->seeResponseIsJson();
//        $I->seeResponseIsValidOnJsonSchemaString(json_encode(Data::POST_RESPONSE));
//    }

//    public function postPositiveNoInitials(ApiTester $I): void
//    {
//        $I->sendPost(self::apiEndpoint, Payloads::VALID_NO_INITIALS);
//        $I->seeResponseCodeIs(200);
//        $I->seeResponseIsJson();
//        $I->seeResponseIsValidOnJsonSchemaString(json_encode(Data::POST_RESPONSE));
//    }
//
//    public function postPositiveNoSlackUserId(ApiTester $I): void
//    {
//        $I->sendPost(self::apiEndpoint, Payloads::VALID_NO_SLACKUSERID);
//        $I->seeResponseCodeIs(200);
//        $I->seeResponseIsJson();
//        $I->seeResponseIsValidOnJsonSchemaString(json_encode(Data::POST_RESPONSE));
//    }
//
//    #[Group('smoke', 'sanity')]
//    public function postPositiveWithOnlyRequiredFields(ApiTester $I): void
//    {
//        $I->sendPost(self::apiEndpoint, Payloads::VALID_JUST_REQUIRED);
//        $I->seeResponseCodeIs(200);
//        $I->seeResponseIsJson();
//        $I->seeResponseIsValidOnJsonSchemaString(json_encode(Data::POST_RESPONSE));
//    }

//    public function POST_NEGATIVE_SomethingHere1(ApiTester $I): void
//    {
//        $I->sendPost(self::apiEndpoint, Payloads::FULL_PAYLOAD);
//        $I->seeResponseCodeIs(200);
//        $I->seeResponseIsJson();
//        $I->seeResponseIsValidOnJsonSchemaString(json_encode(Data::POST_RESPONSE));
//    }

}

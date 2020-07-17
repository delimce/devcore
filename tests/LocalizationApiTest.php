<?php

class LocalizationApiTest extends TestCase
{
    const API_URI = "api/local/";

    /** @test 
     * manager index page
     */
    public function testLocalizationStates()
    {
        $response = $this->call('GET', static::API_URI . 'states');
        $this->assertEquals(200, $response->status());
        $madrid = [
            "id" => 13,
            "country_id" => 204,
            "name" => "Madrid, Comunidad de",
            "status" => 1
        ];
        $content = $this->getArrayByResponse($response);
        $this->assertContains($madrid, $content);
    }

    public function testLocalizationProvinces()
    {
        $response = $this->call('GET', static::API_URI . 'provinces/13');
        $this->assertEquals(200, $response->status());
        $madrid = [
            "id" => 28,
            "state_id" => 13,
            "name" => "Madrid",
            "status" => 1
        ];
        $content = $this->getArrayByResponse($response);
        $this->assertContains($madrid, $content);
    }
}

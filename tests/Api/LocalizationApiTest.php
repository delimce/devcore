<?php

class LocalizationApiTest extends TestCase
{
    const API_URI = "api/local/";

    /**
     * @test
     * testLocalizationStates
     *
     * @return void
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
    
    /**
     * @test
     * testLocalizationProvinces
     *
     * @return void
     */
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
    
    /**
     * @test
     * testLocalizationCitiesByCountry
     *
     * @return void
     */
    public function testLocalizationCitiesByCountry()
    {
        $countryId = 204; # spain
        $response = $this->call('GET', static::API_URI . 'cities/country/'.$countryId);
        $content = $this->getArrayByResponse($response);
        $this->assertTrue(count($content)>20);

    }
}

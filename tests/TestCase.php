<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }


    /**
     * getCountOfResponseList
     *
     * @param  \Illuminate\Http\Response $response
     * @return int
     */
    protected function getCountOfResponseList($response)
    {
        $content = $this->getArrayByResponse($response);
        return count($content["list"]);
    }

    
    /**
     * getListByResponse
     *
     * @param  @param  \Illuminate\Http\Response $response
     * @return array
     */
    protected function getArrayByResponse($response)
    {
        $response = json_decode($response->getContent(), true);
        return $response["info"];
    }
}

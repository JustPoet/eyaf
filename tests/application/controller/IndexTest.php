<?php
require_once APPLICATION_PATH . '/tests/library/ControllerTestCase.php';


class IndexTest extends ControllerTestCase
{
    /**
     * @test
     */
    public function testIndex()
    {
        $request = new Yaf\Request\Simple("CLI", "Index", "Index", 'hello');
        $response = $this->application->getDispatcher()
            ->returnResponse(true)
            ->dispatch($request);
        $content = $response->getBody();
        $this->assertEquals('hello', $content);
    }
}
<?php

namespace Yish\Imgur\Test;

use GuzzleHttp\Exception\ClientException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Imgur;
use Yish\Imgur\Upload;

class ImgurTest extends TestCase
{
    private static $test_image = 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Apple_logo_black.svg/1200px-Apple_logo_black.svg.png';

    private $object;

    public function setUp()
    {
        parent::setUp();

        $this->object = Imgur::upload(static::$test_image);
    }

    /**
     * @test
     * @group imgur
     */
    public function it_should_see_version()
    {
        $expected = 'v3';

        $result = Imgur::version();

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     * @group imgur
     */
    public function it_should_upload_image()
    {
        $this->assertInstanceOf(Upload::class, $this->object);
        $this->assertEquals(200, $this->object->response->status);
    }

    /**
     * @test
     * @group imgur
     */
    public function it_should_upload_image_and_validate_link()
    {
        $validator = validator([
            'url' => $this->object->response->data->link
        ], [
            'url' => 'url'
        ]);

        $this->assertFalse($validator->fails());
    }

    /**
     * @test
     * @group imgur
     */
    public function it_should_get_link()
    {
        $this->assertEquals($this->object->response->data->link, $this->object->link());
    }

    /**
     * @test
     * @group imgur
     */
    public function it_should_get_size()
    {
        $this->assertEquals($this->object->response->data->size, $this->object->filesize());
    }

    /**
     * @test
     * @group imgur
     */
    public function it_should_get_type()
    {
        $this->assertEquals($this->object->response->data->type, $this->object->type());
    }

    /**
     * @test
     * @group imgur
     */
    public function it_should_get_width()
    {
        $this->assertEquals($this->object->response->data->width, $this->object->width());
    }

    /**
     * @test
     * @group imgur
     */
    public function it_should_get_height()
    {
        $this->assertEquals($this->object->response->data->height, $this->object->height());
    }

    /**
     * @test
     * @group imgur
     */
    public function it_should_get_usual()
    {
        $this->assertEquals([
            'link' => $this->object->response->data->link,
            'filesize' => $this->object->response->data->size,
            'type' => $this->object->response->data->type,
            'width' => $this->object->response->data->width,
            'height' => $this->object->response->data->height,
        ], $this->object->usual());
    }

    /**
     * @test
     * @group imgur
     */
    public function it_should_get_exception_with_header()
    {
        $this->expectException(ClientException::class);

        Imgur::setHeaders(['abc' => 'def'])->upload(self::$test_image);
    }

    /**
     * @test
     * @group imgur
     */
    public function it_should_get_exception_with_params()
    {
        $this->expectException(ClientException::class);

        Imgur::setFormParams(['abc' => 'def'])->upload(self::$test_image);
    }

    /**
     * @test
     * @group imgur
     */
    public function it_should_set_all_and_work()
    {
        $result = Imgur::setHeaders([
            'headers' => [
                'authorization' => 'Client-ID ' . env('IMGUR_CLIENT_ID'),
                'content-type' => 'application/x-www-form-urlencoded',
            ]
        ])->setFormParams([
            'form_params' => [
                'image' => self::$test_image,
            ]
        ])->upload(self::$test_image);

        $this->assertEquals(200, $result->response->status);
    }

    /**
     * @test
     * @group imgur
     */
    public function it_should_get_specific_size()
    {
        $origin = "https://i.imgur.com/BO49tuZ.jpg";

        $result = Imgur::size($origin, 's');

        $this->assertEquals("https://i.imgur.com/BO49tuZs.jpg", $result);
    }
}

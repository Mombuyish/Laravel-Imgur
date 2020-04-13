<?php

namespace Yish\Imgur;

use GuzzleHttp\Client;
use InvalidArgumentException;
use Illuminate\Http\UploadedFile;

class Upload implements Contract
{
    protected $url;

    protected $headers = [];

    protected $params = [];

    protected $size = ['s', 'b', 't', 'm', 'l', 'h'];

    public $response;

    const VERSION = 'v3';

    private $client_id;

    private $client_secret;

    private $image;

    public function __construct($client_id, $client_secret, $endpoint)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->url = $endpoint;
    }

    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Check API version.
     *
     * @return string
     */
    public static function version()
    {
        return self::VERSION;
    }

    /**
     * If concrete instance UploadedFile, it should transform base64, either return url.
     *
     * @param $image
     * @return string
     */
    private function fileType($image)
    {
        if ($image instanceof UploadedFile) {
            return base64_encode(file_get_contents($image->path()));
        }

        return $image;
    }

    /**
     * Set headers.
     *
     * @param $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * If does not set headers, using default header, either return headers.
     *
     * @return array
     */
    private function getHeaders()
    {
        if (empty($this->headers)) {
            return [
                'headers' => [
                    'authorization' => 'Client-ID ' . $this->client_id,
                    'content-type' => 'application/x-www-form-urlencoded',
                    ]
            ];
        }

        return $this->headers;
    }


    /**
     * Set form params.
     *
     * @param $params
     * @return $this
     */
    public function setFormParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * If does not set form, using default form, either return form.
     *
     * @return array
     */
    private function getFormParams()
    {
        if (empty($this->params)) {
            return [
                'form_params' => [
                    'image' => $this->image
                ]
            ];
        }

        return $this->params;
    }

    private function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Main entrance point.
     *
     * @param $image
     * @return $this
     */
    public function upload($image)
    {
        $client = new Client();

        $this->setImage($this->fileType($image));

        $response = $client->request('POST', $this->url, array_merge($this->getHeaders(), $this->getFormParams()));

        $this->setResponse(json_decode($response->getBody()->getContents()));

        return $this;
    }

    /**
     * get uploaded image link.
     *
     * @return mixed
     */
    public function link()
    {
        return $this->response->data->link;
    }

    /**
     * get uploaded image size.
     *
     * @return mixed
     */
    public function filesize()
    {
        return $this->response->data->size;
    }

    /**
     * get uploaded image type.
     *
     * @return mixed
     */
    public function type()
    {
        return $this->response->data->type;
    }

    /**
     * get uploaded image width.
     *
     * @return mixed
     */
    public function width()
    {
        return $this->response->data->width;
    }

    /**
     * get uploaded image height.
     *
     * @return mixed
     */
    public function height()
    {
        return $this->response->data->height;
    }

    /**
     * get uploaded image usual parameters.
     *
     * @return mixed
     */
    public function usual()
    {
        return [
            'link' => $this->link(),
            'filesize' => $this->filesize(),
            'type' => $this->type(),
            'width' => $this->width(),
            'height' => $this->height(),
        ];
    }

    private function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Imgur image size.
     * @param $url
     * @param $size
     * @return string
     */
    public function size($url, $size)
    {
        if (! in_array($size, $this->size)) {
            throw new InvalidArgumentException("Imgur does not support ' $size ' type." );
        }

        $delimiter = 'https://i.imgur.com/';

        $image = explode('.', explode($delimiter, $url)[1]);

        return $delimiter . $image[0] . $size . '.' . $image[1];
    }
}

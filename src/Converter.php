<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 4/16/19
 * Time: 3:06 PM
 */

namespace DedeGunawan\PdfConverterClient;


use DedeGunawan\PdfConverterClient\Exception\Base64DecodeException;
use DedeGunawan\PdfConverterClient\Exception\JsonDecodeException;

class Converter
{
    protected $curl;
    protected static $api_url;
    protected static $api_key;
    protected static $secret_key;
    protected $file;
    protected $response;
    protected $pdf_data;

    /**
     * @return mixed
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * @param mixed $curl
     */
    public function setCurl($curl)
    {
        $this->curl = $curl;
    }

    /**
     * @return mixed
     */
    public static function getApiUrl()
    {
        return self::$api_url;
    }

    /**
     * @param mixed $api_url
     */
    public static function setApiUrl($api_url)
    {
        self::$api_url = $api_url;
    }

    /**
     * @return mixed
     */
    public static function getApiKey()
    {
        return self::$api_key;
    }

    /**
     * @param mixed $api_key
     */
    public static function setApiKey($api_key)
    {
        self::$api_key = $api_key;
    }

    /**
     * @return mixed
     */
    public static function getSecretKey()
    {
        return self::$secret_key;
    }

    /**
     * @param mixed $secret_key
     */
    public static function setSecretKey($secret_key)
    {
        self::$secret_key = $secret_key;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getPdfData()
    {
        return $this->pdf_data;
    }

    /**
     * @param mixed $pdf_data
     */
    public function setPdfData($pdf_data)
    {
        $this->pdf_data = $pdf_data;
    }


    public function __construct()
    {
        $this->init();
    }

    public function init() {
        $curl = curl_init();
        $this->setCurl($curl);
    }
    public function convert() {
        $this->_curl();
        return $this->real_pdf_file();
    }

    public function _curl() {

        $this->init();
        $curl = $this->getCurl();
        $api_url = self::getApiUrl();
        if (!$api_url) throw new \Exception("API URL not setted");

        $api_key = self::getApiKey();
        if (!$api_key) throw new \Exception("API KEY not setted");
        $secret_key = self::getSecretKey();
        if (!$secret_key) throw new \Exception("SECRET KEY not setted");

        $file = $this->getFile();
        if (!$file) throw new \Exception("FILE not setted");
        $file = "@{$file}";

        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$api_url}/api/convert",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => compact('api_key', 'secret_key', 'file'),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
                "Postman-Token: 4a9c1edc-7b38-4299-b9cf-9f29c32186ef",
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new CurlException($err);
        } else {
            $this->setResponse($response);
        }
    }

    public function real_pdf_file() {
        $response = @json_decode($this->getResponse(), 1);
        if (!$response) throw new JsonDecodeException();

        $pdf_base64 = $response['items']['pdf'];
        $pdf = base64_decode($pdf_base64);
        if (!$pdf) throw new Base64DecodeException();

        $this->setPdfData($pdf);
    }


}
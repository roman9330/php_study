<?php
namespace MyStudy\Url;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Monolog\Level;

class Validator
{
    protected ClientInterface $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }


    /**
     * @throws GuzzleException
     */
    public function validateUrl(string $url): bool
    {
        $result = true;
        if(empty($url) || !filter_var($url, FILTER_VALIDATE_URL) || !$this->checkUrl($url)){
            WriterLog::getInstance()->write(Level::Alert, "Неверный Url " . $url);
            $result = false;
        }
        return $result;
    }

    /**
     * @throws GuzzleException
     */
    protected function checkUrl(string $url): bool
    {
        $validCodeArray = [
            200, 201, 301, 302
        ];
        try {
            //$client = new Client();
            $response = $this->client->request('GET', $url);
            $result = (!empty($response) && in_array($response->getStatusCode(), $validCodeArray));
        }catch (ConnectException $e){
            WriterLog::getInstance()->write(Level::Error, $e->getMessage());
            $result = false;
        }
        return $result;
        //        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//         curl_setopt($ch, CURLOPT_NOBODY, true);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_exec($ch);
//        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        curl_close($ch);
//        return (!empty($response) && $response != 404);
    }
}
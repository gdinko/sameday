<?php

namespace Mchervenkov\Sameday;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Mchervenkov\Sameday\Exceptions\SamedayException;

trait MakesHttpRequests
{
    /**
     * Send Get Request
     *
     * @param string $url
     * @param array $data
     * @return mixed
     * @throws SamedayException
     */
    public function get(string $url, array $data = []): mixed
    {
        return $this->request('get', $url, $data);
    }

    /**
     * Send Post Request
     *
     * @param string $url
     * @param array $data
     * @return mixed
     * @throws SamedayException
     */
    public function post(string $url, array $data = []): mixed
    {
        return $this->request('post', $url, $data);
    }

    /**
     * Send Put Request
     *
     * @param string $url
     * @param array $data
     * @return mixed
     * @throws SamedayException
     */
    public function put(string $url, array $data = []): mixed
    {
        return $this->request('put', $url, $data);
    }

    /**
     * Send Api Request
     *
     * @param string $verb
     * @param string $url
     * @param array $data
     * @return mixed
     * @throws SamedayException
     */
    public function request(string $verb, string $url, array $data = []): mixed
    {
        $token = Cache::get('sameday_token');

        $response = Http::withHeaders(['X-AUTH-TOKEN' => $token])
            ->timeout($this->timeout)
            ->retry(2, 0, function (Exception $exception, PendingRequest $request) {
                if (! $exception instanceof RequestException || $exception->response->status() !== 401) {
                    return false;
                }

                $request->withHeaders(['X-AUTH-TOKEN' => $this->getToken()]);

                return true;

            })->{$verb}("$this->baseUrl/$url", $data)
            ->throw(function ($response, $e) {
                throw new SamedayException(
                    $e->getMessage(),
                    $e->getCode(),
                    $response->json()
                );
            });

        return $response->json();
    }

    /**
     * Return Api Token and store it into Cache
     *
     * @return string
     */
    public function getToken(): string
    {
        $response = Http::withHeaders([
            'X-AUTH-USERNAME' => $this->xAuthUsername,
            'X-AUTH-PASSWORD' => $this->xAuthPassword,
        ])->post("$this->baseUrl/api/authenticate");

        $data = $response->json();

        $token = data_get($response->json(), 'token');
        Cache::put('sameday_token', data_get($data, 'token'));

        return $token;
    }
}

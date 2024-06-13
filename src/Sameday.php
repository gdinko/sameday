<?php

namespace Mchervenkov\Sameday;

class Sameday
{
    use MakesHttpRequests;
    use Actions\ManageClient;
    use Actions\ManageGeolocation;
    use Actions\ManageLockers;

    public const SIGNATURE = 'CARRIER_SAMEDAY';

    /**
     * Sameday Authentication Username
     */
    private string $xAuthUsername;

    /**
     * Sameday Authentication Password
     */
    private string $xAuthPassword;

    /**
     * Sameday Api Base Url
     */
    private string $baseUrl;

    /**
     * Sameday Api Timeout
     */
    private int $timeout;

    public function __construct()
    {
        $this->xAuthUsername = config('sameday.x_auth_username');
        $this->xAuthPassword = config('sameday.x_auth_password');
        $this->timeout = config('sameday.timeout');

        $this->configBaseUrl();
    }

    /**
     * configBaseUrl
     *
     * @return void
     */
    public function configBaseUrl()
    {
        if (config('sameday.env') == 'production') {
            $this->baseUrl = config('sameday.production_base_url');
        } else {
            $this->baseUrl = config('sameday.test_base_url');
        }
    }

    /**
     * setAccount
     *
     * @param  string $xAuthUsername
     * @param  string $xAuthPassword
     * @return void
     */
    public function setAccount(string $xAuthUsername, string $xAuthPassword)
    {
        $this->xAuthUsername = $xAuthUsername;
        $this->xAuthPassword = $xAuthPassword;
    }

    /**
     * getAccount
     *
     * @return array
     */
    public function getAccount(): array
    {
        return [
            'x_auth_username' => $this->xAuthUsername,
            'x_auth_password' => $this->xAuthPassword,
        ];
    }

    /**
     * getUserName
     *
     * @return string
     */
    public function getUserName(): string
    {
        return $this->xAuthUsername;
    }

    /**
     * getPassword
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->xAuthPassword;
    }

    /**
     * setBaseUrl
     *
     * @param  string $baseUrl
     * @return void
     */
    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * getBaseUrl
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * setTimeout
     *
     * @param  int $timeout
     * @return void
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * getTimeout
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * getSignature
     *
     * @return string
     */
    public function getSignature(): string
    {
        return self::SIGNATURE;
    }
}

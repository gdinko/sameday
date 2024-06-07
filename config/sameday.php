<?php

/*
 * You can place your custom package configuration in here.
 */

return [

    /**
     * Configure Sameday endpoint (test|production)
     */
    'env' => env('SAMEDAY_ENV', 'test'),

    /**
     * Set Sameday API username
     */
    'x_auth_username' => env('SAMEDAY_AUTH_USERNAME', 'test-sameday-username'),

    /**
     * Set Sameday API password
     */
    'x_auth_password' => env('SAMEDAY_AUTH_PASSWORD', 'test-sameday-password'),

    /**
     * Default Sameday test base url
     */
    'test_base_url' => rtrim(env('SAMEDAY_API_TEST_BASE_URI', 'https://sameday-api-bg.demo.zitec.com'), '/'),

    /**
     * Default Sameday production base url
     */
    'production_base_api_url' => rtrim(env('SAMEDAY_API_PRODUCTION_BASE_URI', 'https://sameday-api.demo.zitec.com/'), '/'),

    /**
     * Set Request timeout
     */
    'timeout' => env('SAMEDAY_API_TIMEOUT', 5),
];

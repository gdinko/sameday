<?php

use Mchervenkov\Sameday\Sameday;


test('setup default sameday object', function () {

    $sameday = new Sameday();

    expect($sameday)->toBeInstanceOf(Sameday::class);

    expect($sameday->getAccount())->toMatchArray([
        'x_auth_username' => config('sameday.x_auth_username'),
        'x_auth_password' => config('sameday.x_auth_password'),
    ]);

    $defaultBaseUrl = config('sameday.production_base_api_url');

    if (config('sameday.env') == 'test') {
        $defaultBaseUrl = config('sameday.test_base_url');
    }

    expect($sameday->getBaseUrl())->toEqual($defaultBaseUrl);

    expect($sameday->getTimeout())->toEqual(config('sameday.timeout'));
});

test('set props of sameday object', function () {
    $sameday = new Sameday();

    expect($sameday)->toBeInstanceOf(Sameday::class);

    $sameday->setAccount('user', 'pass');

    expect($sameday->getAccount())->toMatchArray([
        'user' => 'user',
        'pass' => 'pass',
    ]);

    $sameday->setBaseUrl('endpoint');

    expect($sameday->getBaseUrl())->toEqual('endpoint');

    $sameday->setTimeout(99);

    expect($sameday->getTimeout())->toEqual(99);
});

test('set test endpoint of sameday object', function () {
    config(['sameday.env' => 'test']);

    $sameday = new Sameday();

    expect($sameday->getBaseUrl())->toEqual(config('sameday.test_base_url'));
});

test('set production endpoint of sameday object', function () {
    config(['sameday.env' => 'production']);

    $sameday = new Sameday();

    expect($sameday->getBaseUrl())->toEqual(config('econt.production_base_api_url'));
});

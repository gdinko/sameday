<?php

it('Can Get Counties', function () {

    $sameday = new Mchervenkov\Sameday\Sameday();

    $response = $sameday->getCounties();

    expect($response['data'])
        ->toBeArray();

    foreach ($response['data'] as $county) {
        expect($county)
            ->toHaveKeys([
                'id',
            ]);
    }
});

it('Can Get Cities', function () {

    $sameday = new Mchervenkov\Sameday\Sameday();

    $response = $sameday->getCities();

    expect($response['data'])
        ->toBeArray();

    foreach ($response['data'] as $city) {
        expect($city)
            ->toHaveKeys([
                'id',
            ]);
    }
});

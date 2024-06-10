<?php

it('Can Get Services', function () {

    $sameday = new Mchervenkov\Sameday\Sameday();

    $response = $sameday->getServices();

    expect($response['data'])
        ->toBeArray();

    foreach ($response['data'] as $city) {
        expect($city)
            ->toHaveKeys([
                'id',
            ]);
    }
});

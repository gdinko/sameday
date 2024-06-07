<?php

it('Can Get Services', function () {

    $sameday = new Mchervenkov\Sameday\Sameday();

    $repoonse = $sameday->getServices();

    expect($repoonse)
        ->toBeArray()
        ->toHaveKeys([
            'data.id',
        ]);

    $this->assertIsNumeric($repoonse['data']['id']);
});

it('Can Get Pickup Points', function () {

    $sameday = new Mchervenkov\Sameday\Sameday();

    $repoonse = $sameday->getPickupPoints();

    expect($repoonse)
        ->toBeArray()
        ->toHaveKeys([
            'data.id',
        ]);

    $this->assertIsNumeric($repoonse['data']['id']);
});

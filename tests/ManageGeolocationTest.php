<?php

it('Can Get Counties', function () {

    $sameday = new Mchervenkov\Sameday\Sameday();

    $repoonse = $sameday->getCounties();

    expect($repoonse)
        ->toBeArray()
        ->toHaveKeys([
            'data.id',
        ]);

    $this->assertIsNumeric($repoonse['data']['id']);
});

it('Can Get Cities', function () {

    $sameday = new Mchervenkov\Sameday\Sameday();

    $repoonse = $sameday->getCities();

    expect($repoonse)
        ->toBeArray()
        ->toHaveKeys([
            'data.id',
        ]);

    $this->assertIsNumeric($repoonse['data']['id']);
});

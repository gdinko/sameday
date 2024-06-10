<?php

it('Can Get Lockers', function () {

    $sameday = new Mchervenkov\Sameday\Sameday();

    $response = $sameday->getLockers();

    expect($response)
        ->toBeArray();

    foreach ($response as $locker) {
        expect($locker)
        ->toHaveKeys([
            'lockerId',
        ]);
    }
});

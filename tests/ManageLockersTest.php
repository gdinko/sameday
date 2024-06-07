<?php

it('Can Get Lockers', function () {

    $sameday = new Mchervenkov\Sameday\Sameday();

    $repoonse = $sameday->getLockers();

    expect($repoonse)
        ->toBeArray()
        ->toHaveKeys([
            'lockerId',
        ]);

    $this->assertIsNumeric($repoonse['lockerId']);
});

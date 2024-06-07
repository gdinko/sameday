<?php

it('Can Get Cities', function () {
    $this->artisan('sameday:get-cities')->assertExitCode(0);
});

it('Can Get Counties', function () {
    $this->artisan('sameday:get-counties')->assertExitCode(0);
});

it('Can Get Lockers', function () {
    $this->artisan('sameday:get-lockers')->assertExitCode(0);
});

it('Can Check API Status', function () {
    $this->artisan('sameday:api-status')->assertExitCode(0);
});

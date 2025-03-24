<?php

pest()->group('commands');

it('can certifaction roles list', function () {
    $this->artisan('certifaction:roles:list')->assertSuccessful();
});

it('can certifaction roles list with with force from remote option', function () {
    $this->artisan('certifaction:roles:list', ['--remote' => true])->assertSuccessful();
});

<?php

pest()->group('commands');

it('can certifaction roles list', function () {
    $this->artisan('certifaction:list-roles')->assertSuccessful();
});

it('can certifaction roles list with with force from remote option', function () {
    $this->artisan('certifaction:list-roles', ['--force-remote' => true])->assertSuccessful();
});

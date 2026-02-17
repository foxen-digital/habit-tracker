<?php

/**
 * Smoke tests - Basic sanity checks to ensure the application is working
 */
describe('application health', function () {
    it('can boot the application', function () {
        expect(app())->toBeInstanceOf(Illuminate\Foundation\Application::class);
    });

    it('has a valid environment configured', function () {
        expect(app()->environment())->toBeString();
    });

    it('can resolve the config service', function () {
        expect(config('app.name'))->toBeString();
    });
});

describe('database connectivity', function () {
    it('can connect to the database', function () {
        $pdo = DB::connection()->getPdo();
        expect($pdo)->toBeInstanceOf(PDO::class);
    });
});

<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    private ?string $postgresSchema = null;

    protected function setUp(): void
    {
        parent::setUp();
        // Never use .env credentials in automated tests.
        $connection = [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
        ];
        $testDatabase = getenv('SDM_TEST_PGSQL_DATABASE');
        if ($testDatabase !== false && $testDatabase !== '') {
            if (! preg_match('/\A[a-z0-9_]+_test\z/', $testDatabase)) {
                throw new \RuntimeException('PostgreSQL tests require a dedicated database whose name ends in _test.');
            }
            $this->postgresSchema = 'sdm_test_'.bin2hex(random_bytes(8));
            $connection = [
                'driver' => 'pgsql',
                'host' => getenv('SDM_TEST_PGSQL_HOST') ?: '127.0.0.1',
                'port' => getenv('SDM_TEST_PGSQL_PORT') ?: '5432',
                'database' => $testDatabase,
                'username' => getenv('SDM_TEST_PGSQL_USERNAME') ?: 'postgres',
                'password' => getenv('SDM_TEST_PGSQL_PASSWORD') ?: '',
                'charset' => 'utf8', 'prefix' => '',
                'search_path' => $this->postgresSchema,
                'sslmode' => getenv('SDM_TEST_PGSQL_SSLMODE') ?: 'prefer',
            ];
        }
        config(['database.connections.sdm' => $connection]);
        DB::purge('sdm');
        if ($this->postgresSchema !== null) {
            DB::connection('sdm')->statement('CREATE SCHEMA "'.$this->postgresSchema.'"');
        }
        $migration = require __DIR__.'/../packages/sugihart-device-manager/database/migrations/2026_11_06_000000_create_device_manager_tables.php';
        $migration->up();
    }

    protected function tearDown(): void
    {
        try {
            if ($this->postgresSchema !== null) {
                // Only drop the random schema created by this test, never application tables.
                DB::connection('sdm')->statement('DROP SCHEMA "'.$this->postgresSchema.'" CASCADE');
            }
        } finally {
            parent::tearDown();
        }
    }

    protected function path(string $path = ''): string
    {
        return '/sugihart_device_manager/public/'.ltrim($path, '/');
    }
}

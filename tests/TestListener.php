<?php
namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class TestListener extends \PHPUnit_Framework_BaseTestListener
{
    use CreatesApplication;

    protected $app;

    public static $called = false;

    public function __construct()
    {
        $this->app = $this->createApplication();
    }

    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        if (! self::$called) {
            Storage::disk('testing')->put('phpunit.sqlite', '');
            Artisan::call('migrate');
            self::$called = true;
        }
    }
}

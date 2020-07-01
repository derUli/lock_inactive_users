<?php

use Spatie\Snapshots\MatchesSnapshots;

class LockInactiveUsersControllerTest extends \PHPUnit\Framework\TestCase
{
    use MatchesSnapshots;

    protected function setUp(): void
    {
        Translation::loadAllModuleLanguageFiles("en");
        Settings::set("lock_inactive_users/enable", "1");
        Settings::set("lock_inactive_users/days", 2);
        
        $controller = new LockInactiveUsersController();
        $controller->cron();
    }

    protected function tearDown(): void
    {
        $_GET = [];
        Settings::delete("lock_inactive_users/enable");
        Settings::delete("lock_inactive_users/days");
    }

    public function testGetSettingsHeadline()
    {
        $controller = new LockInactiveUsersController();
        $this->assertMatchesTextSnapshot($controller->getSettingsHeadline());
    }

    public function testGetSettings()
    {
        $controller = new LockInactiveUsersController();
        $html = $controller->settings();
        $this->assertStringContainsString(
            '<input class="form-check-input" type="checkbox" value="1"',
            $html
        );

        $this->assertStringNotContainsString(
            'class="alert alert-success lert-dismissable fade in"',
            $html
        );
    }

    public function testGetSettingsSave()
    {
        $_GET["save"] = "1";
        $controller = new LockInactiveUsersController();
        $html = $controller->settings();
        $this->assertStringContainsString(
            'class="alert alert-success lert-dismissable fade in"',
            $html
        );
    }
}

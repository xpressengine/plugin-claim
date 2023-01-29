<?php

namespace Xpressengine\Plugins\Claim\Migrations;

use Illuminate\Support\Collection;

/**
 * Class MigrationResource
 * @package Xpressengine\Plugins\Claim\Migrations
 */
class MigrationResource
{
    /**
     * install migrations
     * @return void
     */
    public function install()
    {
        $this->getSupportMigrations()->each(
            function ($migration) {
                $migration->install();
            }
        );
    }

    /**
     * check installed
     * @return bool
     */
    public function checkInstalled()
    {
        $isInstalled = true;

        $this->getSupportMigrations()->each(
            function ($migration) use (&$isInstalled) {
                if ($migration->checkInstalled() === false) {
                    $isInstalled = false;
                    return false;
                }

                return true;
            }
        );

        return $isInstalled;
    }

    /**
     * update migrations
     * @param string|null $installedVersion
     * @return void
     */
    public function update(string $installedVersion = null)
    {
        $this->getSupportMigrations()->each(
            function ($migration) use ($installedVersion) {
                $migration->update($installedVersion);
            }
        );
    }

    /**
     * check updated
     * @param string|null $installedVersion
     * @return bool
     */
    public function checkUpdated(string $installedVersion = null)
    {
        $isUpdated = true;

        $this->getSupportMigrations()->each(
            function ($migration) use (&$isUpdated, $installedVersion) {
                if ($migration->checkUpdated() === false) {
                    $isUpdated = false;
                    return false;
                }

                return true;
            }
        );

        return $isUpdated;
    }

    /**
     * @return Collection
     */
    protected function getSupportMigrations()
    {
        return collect([
            app(Table\ClaimLogTable::class)
        ]);
    }
}

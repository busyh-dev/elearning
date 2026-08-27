<?php

use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

class AddPermissionsToRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $routes = [

            ['name' => _trans('ai-content.AI Content'), 'route' => 'ai-content', 'type' => 1, 'parent_route' => null, 'module' => 'AIContent'],
            ['name' => _trans('ai-content.Setup'), 'route' => 'ai-content.settings', 'type' => 2, 'parent_route' => 'ai-content', 'module' => 'AIContent'],
            ['name' => _trans('ai-content.Content'), 'route' => 'ai-content.content', 'type' => 2, 'parent_route' => 'ai-content', 'module' => 'AIContent'],

        ];


        if (function_exists('permissionUpdateOrCreate')) {
            permissionUpdateOrCreate($routes);
        }
    }

    public function down()
    {
        //
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class LaratrustSeeder extends Seeder
{
    public function run()
    {
        $this->truncateLaratrustTables();

        $config = Config::get('laratrust_seeder.roles_structure');
        // $config= [
        //        'super_admin' => [
        //            'users' => 'c,r,u,d',
        //        ],
        //    ],

        if ($config === null) {
            $this->command->error("The configuration has not been published. Did you run `php artisan vendor:publish --tag=\"laratrust-seeder\"`");
            $this->command->line('');
            return false;
        }

        $mapPermission = collect(config('laratrust_seeder.permissions_map'));
//        $mapPermission= [
//        'c' => 'create',
//        'r' => 'read',
//        'u' => 'update',
//        'd' => 'delete',
//    ],

        // $config= [
        //        'super_admin' => [
        //            'users' => 'c,r,u,d',
        //        ],
        //    ],
        foreach ($config as $key => $modules) {

            // Create a new role
            $role = \App\Models\Role::firstOrCreate([
                'name' => $key,//'super_admin'
                'display_name' => ucwords(str_replace('_', ' ', $key)),
                'description' => ucwords(str_replace('_', ' ', $key))
            ]);
            $permissions = [];

            $this->command->info('Creating Role ' . strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {
//                $modules = ['users' => 'c,r,u,d'],
                foreach (explode(',', $value) as $perm) {

                //$mapPermission= [
                   //   'c' => 'create',
                   //   'r' => 'read',
                   //   'u' => 'update',
                   //   'd' => 'delete',
                   //  ],
                    $permissionValue = $mapPermission->get($perm);
//                  $permissionValue =['create', 'read', 'update','delete']

                    $permissions[] = \App\Models\Permission::firstOrCreate([
                        'name' => $module . '_' . $permissionValue,
                        'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                    ])->id;

                    $this->command->info('Creating Permission to ' . $permissionValue . ' for ' . $module);
                }
            }

            // Add all permissions to the role
            $role->permissions()->sync($permissions);

            if (Config::get('laratrust_seeder.create_users')) {
                $this->command->info("Creating '{$key}' user");
                // Create default user for each role
//                $user = \App\Models\User::create([
//                    'name' => ucwords(str_replace('_', ' ', $key)),
//                    'email' => $key . '@app.com',
//                    'password' => bcrypt('password')
//                ]);
//                $user->addRole($role);
            }

        }
    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return  void
     */
    public function truncateLaratrustTables()
    {
        $this->command->info('Truncating User, Role and Permission tables');
        Schema::disableForeignKeyConstraints();

        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('role_user')->truncate();

        if (Config::get('laratrust_seeder.truncate_tables')) {
            DB::table('roles')->truncate();
            DB::table('permissions')->truncate();

            if (Config::get('laratrust_seeder.create_users')) {
                $usersTable = (new \App\Models\User)->getTable();
                DB::table($usersTable)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}

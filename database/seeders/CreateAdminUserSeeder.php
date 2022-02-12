<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $user = User::create([
            // Name Admin
        'name' => 'Walid Reda',
            // E-mail Admin
        'email' => 'redaw600@gmail.com',
            // Password Admin
        'password' => bcrypt('60006000'),
            // Roles (Job) == Admin (Owner)
        'roles_name' => ["owner"],
            // Status User (Admin) == ON
        'Status' => 'مفعل',
        ]);

        $role = Role::create(['name' => 'owner']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
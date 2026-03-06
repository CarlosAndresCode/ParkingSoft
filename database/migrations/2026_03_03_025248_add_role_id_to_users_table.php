<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('role_id')->nullable()->after('role')->constrained()->nullOnDelete();
            });
        }

        // Migrar datos de 'role' (string) a 'role_id'
        $adminRole = \Illuminate\Support\Facades\DB::table('roles')->where('name', 'admin')->first();
        if (! $adminRole) {
            $adminRoleId = \Illuminate\Support\Facades\DB::table('roles')->insertGetId(['name' => 'admin', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]);
        } else {
            $adminRoleId = $adminRole->id;
        }

        $cashierRole = \Illuminate\Support\Facades\DB::table('roles')->where('name', 'cashier')->first();
        if (! $cashierRole) {
            $cashierRoleId = \Illuminate\Support\Facades\DB::table('roles')->insertGetId(['name' => 'cashier', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]);
        } else {
            $cashierRoleId = $cashierRole->id;
        }

        $roles = [
            'admin' => $adminRoleId,
            'cashier' => $cashierRoleId,
        ];

        \Illuminate\Support\Facades\DB::table('users')->where('role', 'admin')->update(['role_id' => $roles['admin']]);
        \Illuminate\Support\Facades\DB::table('users')->where('role', 'cashier')->update(['role_id' => $roles['cashier']]);
        // Por si acaso hay otros o nulos, asignar cashier como default si corresponde
        \Illuminate\Support\Facades\DB::table('users')->whereNull('role_id')->update(['role_id' => $roles['cashier']]);

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                // In SQLite, we need to drop the index before dropping the column if it was manually created
                try {
                    $table->dropIndex(['role']);
                } catch (\Exception $e) {
                    // Index might not exist or already dropped
                }
                $table->dropColumn('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('cashier')->after('email');
        });

        // Revertir datos (opcional, pero buena práctica)
        \Illuminate\Support\Facades\DB::table('users')->join('roles', 'users.role_id', '=', 'roles.id')
            ->update(['users.role' => \Illuminate\Support\Facades\DB::raw('roles.name')]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });
    }
};

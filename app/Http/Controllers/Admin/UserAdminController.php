<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserAdminController extends Controller
{
    private function remoteConnectionName(): string
    {
        // Soporta ambos nombres por si en tu proyecto quedó como 'li' o 'li_users'
        if (config('database.connections.li')) return 'li';
        if (config('database.connections.li_users')) return 'li_users';

        // Si no existe ninguna, truena con mensaje claro
        abort(500, "No existe conexión remota configurada (li o li_users) en config/database.php");
    }

    public function index(Request $request)
    {
        $q = trim((string)$request->query('q', ''));

        $remoteConn = $this->remoteConnectionName();

        // Solo empleados que pueden iniciar sesión: con email y activos
        $remoteUsersQuery = DB::connection($remoteConn)
            ->table('users')
            ->select(['id', 'name', 'email'])
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->where('status', 1)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('email', 'like', "%{$q}%")
                      ->orWhere('name', 'like', "%{$q}%");
                });
            })
            ->orderBy('email', 'asc');

        $remoteUsers = $remoteUsersQuery->paginate(50)->withQueryString();

        // Asegura roles base en local
        Role::findOrCreate('admin');
        Role::findOrCreate('docs_admin');
        Role::findOrCreate('user');

        // “Sincroniza” a local por email (sin tocar passwords)
        $localByEmail = User::query()
            ->whereIn('email', collect($remoteUsers->items())->pluck('email')->filter()->all())
            ->get()
            ->keyBy('email');

        $rows = collect($remoteUsers->items())->map(function ($ru) use ($localByEmail) {
            $email = $ru->email ?? null;

            $local = $email ? ($localByEmail[$email] ?? null) : null;

            // Si no existe local, lo creamos “ligero” (sin password)
            // OJO: el login que hiciste contra la BD central probablemente no depende del password local.
            if ($email && !$local) {
                $local = User::create([
                    'name' => $ru->name ?: $email,
                    'email' => $email,
                    // NO guardamos password aquí; tu login central valida contra la remota
                    'password' => bcrypt(str()->random(40)), // placeholder para evitar null si tu tabla lo exige
                ]);

                // rol por defecto
                if (! $local->hasRole('user') && ! $local->hasRole('admin')) {
                    $local->assignRole('user');
                }
            }

            $role = 'user';
            if ($local) {
                if ($local->hasRole('admin')) {
                    $role = 'admin';
                } elseif ($local->hasRole('docs_admin')) {
                    $role = 'docs_admin';
                }
            }

            return [
                'remote' => $ru,
                'local' => $local,
                'role' => $role,
            ];
        });

        return view('admin.users.index', [
            'rows' => $rows,
            'remoteUsers' => $remoteUsers,
            'q' => $q,
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', 'in:admin,docs_admin,user'],
        ]);

        // Asegura roles base
        Role::findOrCreate('admin');
        Role::findOrCreate('docs_admin');
        Role::findOrCreate('user');

        $user->syncRoles([$data['role']]);

        return back()->with('status', "Rol actualizado: {$user->email} → {$data['role']}");
    }
}
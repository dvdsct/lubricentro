<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class EmpleadosRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'mecánico',
            'lavadero',
            'administrativo',
            'maestranza',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $empleados = [
            ['name' => 'Julio Mendez', 'role' => 'mecánico'],
            ['name' => 'Ruben Castro', 'role' => 'lavadero'],
            ['name' => 'Daniel Galvan', 'role' => 'lavadero'],
            ['name' => 'Ezequiel Pérez', 'role' => 'administrativo'],
            ['name' => 'Carolina Jimenez', 'role' => 'maestranza'],
        ];

        foreach ($empleados as $empleado) {
            $email = $this->buildEmail($empleado['name']);
            $password = $this->buildPassword($empleado['name']);

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $empleado['name'],
                    'password' => bcrypt($password),
                ]
            );

            $user->syncRoles([$empleado['role']]);
        }
    }

    protected function buildEmail(string $name): string
    {
        $parts = $this->normalizeNameParts($name);
        $firstName = $parts[0] ?? '';
        $lastName = end($parts) ?: '';

        return strtolower($firstName . '.' . $lastName . '@test.com');
    }

    protected function buildPassword(string $name): string
    {
        $parts = $this->normalizeNameParts($name);
        $lastName = end($parts) ?: '';

        return strtolower($lastName) . '@159';
    }

    protected function normalizeNameParts(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name));

        $replacements = [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'ñ' => 'n',
        ];

        return array_map(function ($part) use ($replacements) {
            return strtr(strtolower($part), $replacements);
        }, array_filter($parts, fn($part) => $part !== ''));
    }
}

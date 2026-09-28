<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = now();

        DB::table('roles')->upsert([
            ['name' => 'Estudiante', 'code' => 'student', 'description' => 'Registra y consulta sus solicitudes.', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Administrativo', 'code' => 'staff', 'description' => 'Gestiona solicitudes, recursos y reportes.', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Administrador', 'code' => 'admin', 'description' => 'Administra usuarios y catálogos.', 'created_at' => $now, 'updated_at' => $now],
        ], ['code'], ['name', 'description', 'updated_at']);

        DB::table('request_types')->upsert([
            ['name' => 'Infraestructura', 'code' => 'infrastructure', 'description' => 'Problemas en aulas, espacios y servicios físicos.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Soporte tecnológico', 'code' => 'technology_support', 'description' => 'Incidencias de software, red o equipos tecnológicos.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Equipamiento', 'code' => 'equipment', 'description' => 'Problemas o requerimientos de equipamiento institucional.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Otros', 'code' => 'other', 'description' => 'Otros servicios institucionales.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ], ['code'], ['name', 'description', 'is_active', 'updated_at']);

        DB::table('priorities')->upsert([
            ['name' => 'Baja', 'code' => 'low', 'level' => 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Media', 'code' => 'medium', 'level' => 2, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Alta', 'code' => 'high', 'level' => 3, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Crítica', 'code' => 'critical', 'level' => 4, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ], ['code'], ['name', 'level', 'is_active', 'updated_at']);

        DB::table('request_statuses')->upsert([
            ['name' => 'Borrador', 'code' => 'draft', 'is_final' => false, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Registrada', 'code' => 'registered', 'is_final' => false, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Asignada', 'code' => 'assigned', 'is_final' => false, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'En proceso', 'code' => 'in_progress', 'is_final' => false, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Resuelta', 'code' => 'resolved', 'is_final' => false, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cerrada', 'code' => 'closed', 'is_final' => true, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cancelada', 'code' => 'cancelled', 'is_final' => true, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ], ['code'], ['name', 'is_final', 'is_active', 'updated_at']);

        $roles = DB::table('roles')->pluck('id', 'code');

        DB::table('users')->upsert([
            ['name' => 'Administrador Campus', 'email' => 'admin@campus.test', 'password' => Hash::make('admin123'), 'role_id' => $roles['admin'], 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'María Administrativa', 'email' => 'maria.admin@campus.test', 'password' => Hash::make('admin123'), 'role_id' => $roles['staff'], 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Carlos Administrativo', 'email' => 'carlos.admin@campus.test', 'password' => Hash::make('admin123'), 'role_id' => $roles['staff'], 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ana Estudiante', 'email' => 'ana.estudiante@campus.test', 'password' => Hash::make('admin123'), 'role_id' => $roles['student'], 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Luis Estudiante', 'email' => 'luis.estudiante@campus.test', 'password' => Hash::make('admin123'), 'role_id' => $roles['student'], 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ], ['email'], ['name', 'password', 'role_id', 'is_active', 'updated_at']);

        DB::table('resources')->upsert([
            ['code' => 'AULA-A101', 'name' => 'Aula A-101', 'resource_type' => 'Aula', 'location' => 'Bloque A, primer piso', 'status' => 'available', 'description' => 'Aula académica.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'PROY-001', 'name' => 'Proyector Epson 001', 'resource_type' => 'Proyector', 'location' => 'Laboratorio de informática', 'status' => 'available', 'description' => 'Proyector multimedia.', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ], ['code'], ['name', 'resource_type', 'location', 'status', 'description', 'is_active', 'updated_at']);

        $users = DB::table('users')->pluck('id', 'email');
        $types = DB::table('request_types')->pluck('id', 'code');
        $priorities = DB::table('priorities')->pluck('id', 'code');
        $statuses = DB::table('request_statuses')->pluck('id', 'code');

        DB::table('service_requests')->upsert([
            [
                'code' => 'SOL-2026-000001', 'requester_id' => $users['ana.estudiante@campus.test'],
                'assigned_to_user_id' => $users['maria.admin@campus.test'], 'request_type_id' => $types['infrastructure'],
                'priority_id' => $priorities['high'], 'status_id' => $statuses['assigned'],
                'title' => 'Iluminación deficiente en el aula A-101', 'description' => 'Dos luminarias no encienden durante las clases de la tarde.',
                'location' => 'Bloque A, aula A-101', 'submitted_at' => $now->copy()->subDays(2), 'assigned_at' => $now->copy()->subDay(),
                'created_at' => $now->copy()->subDays(2), 'updated_at' => $now,
            ],
            [
                'code' => 'SOL-2026-000002', 'requester_id' => $users['luis.estudiante@campus.test'],
                'assigned_to_user_id' => null, 'request_type_id' => $types['technology_support'],
                'priority_id' => $priorities['medium'], 'status_id' => $statuses['registered'],
                'title' => 'Sin conexión a internet en laboratorio', 'description' => 'Los equipos del laboratorio 2 no tienen acceso a la red institucional.',
                'location' => 'Laboratorio de informática 2', 'submitted_at' => $now->copy()->subHours(10),
                'assigned_at' => null,
                'created_at' => $now->copy()->subHours(10), 'updated_at' => $now,
            ],
        ], ['code'], ['requester_id', 'assigned_to_user_id', 'request_type_id', 'priority_id', 'status_id', 'title', 'description', 'location', 'submitted_at', 'assigned_at', 'updated_at']);
    }
}

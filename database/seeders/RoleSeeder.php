<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
class RoleSeeder extends Seeder
{
    public function run(): void
    {
         $roles = [
            ['name' => 'super_admin',       'label' => 'Super Administrador'],
            ['name' => 'school_admin',      'label' => 'Administrador de Escuela'],
            ['name' => 'teacher',           'label' => 'Docente'],
            ['name' => 'homeroom_teacher',  'label' => 'Tutor de Grupo'],
            ['name' => 'student',           'label' => 'Alumno'],
            ['name' => 'parent',            'label' => 'Padre/Tutor'],
            ['name' => 'registrar',         'label' => 'Control Escolar'],
            ['name' => 'academic_coordinator','label'=>'Coordinador Académico'],
            ['name' => 'finance_manager',   'label' => 'Finanzas/Contabilidad'],
            ['name' => 'nurse',             'label' => 'Enfermería'],
            ['name' => 'librarian',         'label' => 'Bibliotecario'],
            ['name' => 'auditor',           'label' => 'Auditor'],
            ['name' => 'support_admin',     'label' => 'Soporte/DevOps'],
            ['name' => 'admissions_officer','label' => 'Admisiones'],
            ['name' => 'hr_manager',        'label' => 'Recursos Humanos'],
            ['name' => 'attendance_clerk',  'label' => 'Asistencias/Prefectura'],
            ['name' => 'discipline_officer','label' => 'Disciplina/Prefecto'],
            ['name' => 'content_creator',   'label' => 'Creador de Contenido'],
            ['name' => 'examiner',          'label' => 'Evaluador'],
            ['name' => 'substitute_teacher','label' => 'Docente Suplente'],
            ['name' => 'it_support',        'label' => 'Soporte TI'],
            ['name' => 'external_user',     'label' => 'Usuario Externo'],
        ];

        foreach ($roles as $r) {
            $role = Role::firstOrCreate(
                ['name' => $r['name'], 'guard_name' => 'web']
            );
        }
    }
}

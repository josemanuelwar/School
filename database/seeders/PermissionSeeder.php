<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catalog = [
            'schools'           => ['view_any','view','create','update','delete'],
            'academic_years'    => ['view_any','view','create','update','delete'],
            'periods'           => ['view_any','view','create','update','delete'],
            'grades'            => ['view_any','view','create','update','delete'],
            'groups'            => ['view_any','view','create','update','delete'],
            'subjects'          => ['view_any','view','create','update','delete'],
            'grade_subject'     => ['assign','unassign'],

            'users'             => ['view_any','view','create','update','delete','reset_password'],
            'teachers'          => ['view_any','view','create','update','delete'],
            'teacher_school'    => ['assign','unassign'],
            'teacher_group'     => ['view_any','view','assign','update','unassign'],
            'students'          => ['view_any','view','create','update','delete'],
            'student_profiles'  => ['view_any','view','update','view_medical','update_medical'],
            'parents'           => ['view_any','view','create','update','delete'],
            'parent_student'    => ['link','unlink'],

            'syllabus_units'    => ['view_any','view','create','update','delete'],
            'syllabus_topics'   => ['view_any','view','create','update','delete'],
            'activities'        => ['view_any','view','create','update','delete','publish','unpublish'],
            'activity_resources'=> ['create','delete'],

            'assignments'       => ['configure','view_submissions','grade'],
            'assignment_submissions' => ['view_any','view_own','submit','resubmit','grade'],

            'quizzes'           => ['configure','view'],
            'quiz_questions'    => ['create','update','delete'],
            'quiz_options'      => ['create','update','delete'],
            'quiz_attempts'     => ['start','answer','finish','view_any','view_own'],
            'quiz_answers'      => ['view_any','view_own','grade'],

            'attendance'        => ['take','update','view_reports'],
            'discipline'        => ['create_report','update_report','view_reports'],
            'library.books'     => ['view_any','view','create','update','delete'],
            'library.loans'     => ['create','update','close','view_any'],
            'finance.invoices'  => ['view_any','view','create','update','delete'],
            'finance.payments'  => ['view_any','view','record','refund'],
            'reports'           => ['view_school','view_academic','view_attendance','view_finance'],
            'notifications'     => ['send_group','send_student','send_school'],

            'roles'             => ['view','assign'],
            'permissions'       => ['view','assign'],
            'settings.school'   => ['update'],
        ];

        // 2) Crear permisos
        $all = [];
        foreach ($catalog as $group => $actions) {
            foreach ($actions as $a) {
                $name = "{$group}.{$a}";
                Permission::firstOrCreate(['name' => $name]);
                $all[] = $name;
            }
        }
        $expand = function(array $patterns) use ($all, $catalog): array {
            $out = [];
            foreach ($patterns as $p) {
                if ($p === '*') { $out = $all; break; }
                if (str_ends_with($p, '.*')) {
                    $prefix = substr($p, 0, -2);
                    foreach ($all as $perm) if (str_starts_with($perm, $prefix.'.')) $out[] = $perm;
                } else {
                    $out[] = $p; // permiso exacto
                }
            }
            return array_values(array_unique($out));
        };
        $rolesMap = [
            'super_admin' => ['*'],

            'support_admin' => [
                'users.view_any','schools.view_any','reports.view_school','reports.view_academic',
                'permissions.view','roles.view','settings.school.update',
            ],
            'auditor' => array_filter($all, fn($p)=>str_contains($p,'.view_any') || str_starts_with($p,'reports.')),

            'school_admin' => [
                'schools.update',
                'academic_years.*','periods.*','grades.*','groups.*','subjects.*','grade_subject.*',
                'teachers.*','teacher_school.*','teacher_group.*',
                'students.*','student_profiles.view_any','student_profiles.view','student_profiles.update',
                'parents.*','parent_student.*',
                'activities.*','activity_resources.*',
                'assignments.*','assignment_submissions.view_any','assignment_submissions.grade',
                'quizzes.*','quiz_questions.*','quiz_options.*','quiz_attempts.view_any','quiz_answers.view_any','quiz_answers.grade',
                'attendance.*','discipline.*','library.books.*','library.loans.*','finance.invoices.*','finance.payments.*',
                'reports.*','notifications.*','users.*','roles.view','roles.assign','permissions.view','settings.school.update',
            ],
            'registrar' => [
                'students.*','parents.*','parent_student.*',
                'grades.view_any','grades.view','groups.view_any','groups.view',
                'academic_years.view_any','academic_years.view','periods.view_any','periods.view',
                'reports.view_school',
            ],
            'academic_coordinator' => [
                'subjects.*','grade_subject.*','syllabus_units.*','syllabus_topics.*',
                'teacher_group.view_any','teacher_group.assign','teacher_group.update','teacher_group.unassign',
                'activities.view_any','activities.create','activities.update','activities.publish','activities.unpublish',
                'reports.view_academic',
            ],
            'admissions_officer' => [
                'students.create','students.view_any','students.view','students.update',
                'parents.create','parents.view_any','parents.view','parents.update',
                'reports.view_school',
            ],
            'finance_manager' => [
                'finance.invoices.*','finance.payments.*','reports.view_finance',
                'students.view_any','students.view','parents.view_any','parents.view',
            ],
            'hr_manager' => [
                'teachers.*','teacher_school.*','users.view_any','users.view','users.update','reports.view_school',
            ],
            'attendance_clerk' => [
                'attendance.take','attendance.update','attendance.view_reports',
                'students.view_any','students.view','groups.view_any','groups.view','grades.view_any','grades.view',
            ],
            'discipline_officer' => [
                'discipline.create_report','discipline.update_report','discipline.view_reports',
                'students.view_any','students.view','groups.view_any','groups.view',
            ],
            'counselor' => [
                'student_profiles.view_any','student_profiles.view','student_profiles.update','reports.view_academic',
            ],
            'nurse' => [
                'student_profiles.view_any','student_profiles.view','student_profiles.view_medical','student_profiles.update_medical','reports.view_school',
            ],
            'librarian' => [
                'library.books.*','library.loans.*',
            ],
            'it_support' => [
                'users.view_any','users.reset_password','permissions.view','roles.view',
            ],

            'teacher' => [
                'activities.view_any','activities.view','activities.create','activities.update','activities.delete','activities.publish','activities.unpublish',
                'activity_resources.create','activity_resources.delete',
                'assignments.configure','assignments.view_submissions','assignments.grade',
                'assignment_submissions.view_any',
                'quizzes.configure','quizzes.view','quiz_questions.create','quiz_questions.update','quiz_questions.delete',
                'quiz_options.create','quiz_options.update','quiz_options.delete',
                'quiz_attempts.view_any','quiz_answers.view_any','quiz_answers.grade',
                'attendance.take','attendance.update','attendance.view_reports',
                'reports.view_academic',
            ],
            'homeroom_teacher' => [
                // todo lo de teacher +
                'discipline.create_report','discipline.update_report','discipline.view_reports','notifications.send_group',
            ],
            'substitute_teacher' => [
                'activities.view_any','activities.view','attendance.take',
            ],
            'content_creator' => [
                'syllabus_units.*','syllabus_topics.*','activities.view_any','activities.create','activities.update','activity_resources.create','activity_resources.delete',
            ],
            'examiner' => [
                'quizzes.configure','quizzes.view','quiz_questions.*','quiz_options.*','quiz_answers.view_any','quiz_answers.grade','reports.view_academic',
            ],

            'student' => [
                'activities.view',
                'assignment_submissions.view_own','assignment_submissions.submit','assignment_submissions.resubmit',
                'quiz_attempts.start','quiz_attempts.answer','quiz_attempts.finish','quiz_attempts.view_own',
                'quiz_answers.view_own',
            ],
            'parent' => [
                'reports.view_school','attendance.view_reports','activities.view',
            ],
        ];

        // 4) Crear roles y asignar permisos
        foreach ($rolesMap as $roleName => $patterns) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $perms = $expand($patterns);
            $role->syncPermissions($perms);
        }
    }
}

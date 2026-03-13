<?php

return [
    // All available roles for clinic staff (excluding super_admin and patient)
    'roles' => ['admin', 'doctor', 'accountant', 'secretary'],

    // All available permissions grouped by module
    'modules' => [
        'dashboard' => ['dashboard.view'],
        'appointments' => ['appointments.view', 'appointments.create', 'appointments.edit', 'appointments.change_status', 'appointments.complete'],
        'patients' => ['patients.view', 'patients.create', 'patients.edit'],
        'doctors' => ['doctors.view', 'doctors.create', 'doctors.edit'],
        'invoices' => ['invoices.view', 'invoices.edit'],
        'staff' => ['staff.view', 'staff.create', 'staff.edit'],
        'permissions' => ['permissions.manage'],
    ],

    // Default permissions for each role when a clinic is created
    'defaults' => [
        'admin' => '*', // admin gets all permissions

        'secretary' => [
            'dashboard.view',
            'appointments.view',
            'appointments.create',
            'appointments.edit',
            'appointments.change_status',
            'appointments.complete',
            'patients.view',
            'patients.create',
            'patients.edit',
            'doctors.view',
        ],

        'accountant' => [
            'dashboard.view',
            'appointments.view',
            'invoices.view',
            'invoices.edit',
            'patients.view',
        ],
    ],
];

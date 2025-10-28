<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
  use HasFactory;

  public static function sidebar()
  {
    $menu = [
      [
        'title' => 'Dashboard',
        'route' => 'dashboard',
        'icon' => 'i-mdi-view-dashboard',
        'roles' => ['Super Admin', 'Admin SKPD', 'Pegawai']
      ],
      [
        'title' => 'Analytics',
        'icon' => 'i-mdi-chart-line',
        'roles' => ['Super Admin', 'Admin SKPD'],
        'items' => [
          [
            'title' => 'Performance Report',
            'route' => 'analytics.performance',
            'icon' => 'i-mdi-chart-bar'
          ],
          [
            'title' => 'Employee Statistics',
            'route' => 'analytics.employees',
            'icon' => 'i-mdi-account-group'
          ],
        ],
      ],
      [
        'title' => 'Standar Kompetensi Jabatan',
        'route' => 'features.skj',
        'image' => asset('images/features-white/1.svg'),
        'roles' => ['Super Admin'],
      ],
      [
        'title' => 'Assessment Center',
        'route' => 'features.assessment-center',
        'image' => asset('images/features-white/3.svg'),
        'roles' => ['Super Admin', 'Admin SKPD', 'Pegawai'],
      ],
      [
        'title' => 'Sumbu Kinerja',
        'route' => 'features.manja',
        'image' => asset('images/features-white/2.svg'),
        'roles' => ['Super Admin', 'Admin SKPD', 'Pegawai'],
      ],
      [
        'title' => 'Sumbu Potensial',
        'route' => 'features.asn-potensial',
        'image' => asset('images/features-white/4.svg'),
        'roles' => ['Super Admin', 'Admin SKPD', 'Pegawai'],
      ],
      [
        'title' => 'Profil Talenta ASN',
        'route' => 'features.profil-talenta-asn',
        'image' => asset('images/features-white/5.svg'),
        'roles' => ['Super Admin', 'Admin SKPD', 'Pegawai'],
      ],
      [
        'title' => 'Talent Pool',
        'route' => 'features.talent-pool',
        'image' => asset('images/features-white/6.svg'),
        'roles' => ['Super Admin', 'Admin SKPD'],
      ],
      [
        'title' => 'Data Pegawai',
        'icon' => 'i-mdi-account-multiple',
        'roles' => ['Super Admin', 'Admin SKPD', 'Pegawai'],
        'items' => [
          [
            'title' => 'Profil Pegawai',
            'route' => 'employees.profile',
            'icon' => 'i-mdi-account-card-details',
          ],
          [
            'title' => 'Berkas Pegawai',
            'route' => 'employees.documents',
            'icon' => 'i-mdi-file-document-multiple',
          ],
        ],
      ],
      [
        'title' => 'Data Master',
        'icon' => 'i-mdi-database',
        'roles' => ['Super Admin'],
        'items' => [
          [
            'title' => 'Nilai SKP',
            'route' => 'master.skp',
            'icon' => 'i-mdi-clipboard-text',
          ],
          [
            'title' => 'Kehadiran',
            'route' => 'master.attendances',
            'icon' => 'i-mdi-calendar-check',
          ],
          [
            'title' => 'Pegawai',
            'route' => 'master.employees',
            'icon' => 'i-mdi-account-group',
          ],
          [
            'title' => 'SKPD',
            'route' => 'master.units',
            'icon' => 'i-mdi-office-building',
          ],
          [
            'title' => 'Master Jabatan',
            'url' => '#',
            'icon' => 'i-mdi-briefcase',
          ],
          [
            'title' => 'Jenis Jabatan',
            'url' => '#',
            'icon' => 'i-mdi-briefcase-variant',
          ],
        ],
      ],
      [
        'title' => 'Pengaturan',
        'icon' => 'i-mdi-cog',
        'roles' => ['Super Admin'],
        'items' => [
          [
            'title' => 'User Management',
            'route' => 'settings.users',
            'icon' => 'i-mdi-account-multiple-plus',
          ],
          [
            'title' => 'Roles & Permissions',
            'route' => 'settings.roles',
            'icon' => 'i-mdi-shield-account',
          ],
          [
            'title' => 'System Settings',
            'route' => 'settings.system',
            'icon' => 'i-mdi-tune',
          ],
          [
            'title' => 'Backup & Restore',
            'route' => 'settings.backup',
            'icon' => 'i-mdi-backup-restore',
          ],
        ],
      ],
    ];

    return $menu;
  }

  public static function flatRoutes($menus = [])
  {
    return collect(Arr::dot($menus))
      ->filter(function ($_, $key) {
        return str($key)->endsWith('.route');
      })
      ->values();
  }
}

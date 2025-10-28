<?php

// resources/lang/id/validation.php

return [
  'required' => ':attribute wajib diisi.',
  'required_if' => ':attribute wajib diisi.',
  'unique' => ':attribute sudah digunakan (:attribute harus unik)',
  'email' => ':attribute harus berisi alamat email yang valid.',
  'numeric' => ':attribute harus berupa angka',
  'min' => [
    'string' => ':attribute harus memiliki setidaknya :min karakter.',
    'numeric' => ':attribute harus memiliki nilai minimal :min.',
  ],
  'max' => [
    'string' => ':attribute tidak boleh lebih dari :max karakter.',
    'numeric' => 'Nilai :attribute tidak boleh lebih dari :max.',
  ],
  'custom' => [
    'password.confirmed' => ':attribute tidak cocok dengan konfirmasi.',
  ],
  // Add more custom messages for other rules as needed.
  'attributes' => [
    'type' => 'Jenis',
    'code' => 'Kode',
    'role' => 'Role',
    'type_code' => 'Kode Jenis',
    'organization_code' => 'Kode Organisasi',
    'name' => 'Nama',
    'acronym' => 'Akronim',
    'description' => 'Keterangan',
    'address' => 'Alamat',
    'grade' => 'Kelas',
    'rank' => 'Pangkat',
    'required' => 'Kebutuhan',
    'bezetting' => 'Bezetting',
    'password' => 'Password',
    'level' => 'Level',
    'password_confirmation' => 'Konfirmasi Password',
    'other_field_name' => 'Nama Bidang Lain',
    'user.name' => 'Username',
    'user.password' => 'Password user',
    // Add more attribute name m'password_confirmation' => 'Konfirmasi Password'appings as needed.
  ],
];

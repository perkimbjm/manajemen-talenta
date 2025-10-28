<?php

namespace App\Livewire;

use App\Models\Employee;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.authenticated')]
class EmployeePage extends Component
{

  public function deleteEmployee(Employee $employee)
  {
    try {
      $employee->delete();
      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Data pegawai berhasil dihapus',
      ]);

      $this->dispatch('pg:eventRefresh-EmployeeTable');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Gagal menghapus data pegawai',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function render()
  {
    return view('livewire.employee-page');
  }
}

<?php

namespace App\Livewire;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
class CompanyTable extends Component
{
    use WithPagination, WithFileUploads;

    // public $companies;
    public $showModal = false;
    public $name;
    public $email;
    public $logo_path;
    public $website_url;
    public $editingCompanyId;
    public $existingLogoPath;
    public function render()
    {
        // dd(Company::all());
        return view('livewire.company-table', 
            [
                'companies' => Company::paginate(10)
            ]
        );
    }

    public function showAddModal()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'logo_path', 'website_url', 'editingCompanyId']);
    }

    public function saveCompany()
    {
        $this->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('companies', 'email')],
            'logo_path' => 'nullable|image|max:1024', // 1MB Max
            'website_url' => ['nullable', 'url', Rule::unique('companies', 'website_url')],
        ]);

        $logoPath = null;
        if ($this->logo_path) {
            $logoPath = $this->logo_path->store('logos', 'public');
        }

        if ($this->editingCompanyId) {
            $company = Company::find($this->editingCompanyId);
            $company->update([
                'name' => $this->name,
                'email' => $this->email,
                'logo_path' => $logoPath ?: $company->logo_path,
                'website_url' => $this->website_url,
            ]);
        } else {
            Company::create([
                'name' => $this->name,
                'email' => $this->email,
                'logo_path' => $logoPath,
                'website_url' => $this->website_url,
            ]);
        }

        $this->companies = Company::all();
        $this->closeModal();
    }

    public function showEditModal($id)
    {
        $company = Company::find($id);
        if ($company) {
            $this->editingCompanyId = $company->id;
            $this->name = $company->name;
            $this->email = $company->email;
            $this->website_url = $company->website_url;
            $this->existingLogoPath = $company->logo_path; // Store existing logo path
            $this->showModal = true;
        }
    }

    public function deleteCompany($id)
    {
        Company::find($id)->delete();
    }
}

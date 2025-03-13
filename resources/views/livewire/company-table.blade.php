<div>
    <section class="mt-10">
       
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->
            <div class="border-rose-500 bg-white dark:bg-gray-500 relative shadow-md drop-shadow-lg sm:rounded-lg overflow-hidden">
                <div class="flex items-center justify-between p-4">
                    <div class="flex">
                     <button wire:click="showAddModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        Add Company
                    </button>
                        
                    </div>
                    
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                {{-- <th scope="col" class="pl-2 py-3"></th> --}}
                                <th scope="col" class="px-4 py-3">Name</th>
                                <th scope="col" class="px-4 py-3">Email</th>
                                <th scope="col" class="px-4 py-3">Website</th>
                               
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($companies as $company)
                                
                                <tr class="border-b dark:border-gray-700">
                                    {{-- <th class="pl-2 py-3 text-green-500"> --}}
                                        
                                    {{-- </th> --}}
                                    <td scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <img class="inline-block size-10 rounded-full ring-2 ring-white" width="30" height="30"
                                         src="{{asset('storage/'. $company->logo_path)}}" alt="">
                                        {{$company->name}}</td>
                                    <td class="px-4 py-3">{{$company->email}}</td>
                                    <td class="px-4 py-3">{{$company->website_url}}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button wire:click="showEditModal({{ $company->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                        <button wire:click="deleteCompany({{ $company->id }})" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="py-4 px-3">
                    <div class="flex ">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-36 text-sm font-medium text-gray-900">Per Page</label>
                            <select
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </section>

    @if ($showModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-10 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-start mb-2 pb-2">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $editingCompanyId ? 'Edit Company' : 'Add New Company' }}
                            </h3>
                            <div class="flex">
                                <button type="button" wire:click="closeModal" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                    Cancel
                                </button>
                                <button type="submit" wire:click="saveCompany" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Save
                                </button>
                            </div>
                        </div>
                        <div class="mt-4">
                            <form wire:submit.prevent="saveCompany">
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                                        <input type="text" wire:model="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email address:</label>
                                        <input type="email" wire:model="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="logo_path" class="block text-gray-700 text-sm font-bold mb-2">Logo:</label>
                                        <input type="file" wire:model="logo_path" id="logo_path" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('logo_path') <span class="text-red-500">{{ $message }}</span> @enderror
                                        @if ($logo_path)
                                        <img src="{{ $logo_path->temporaryUrl() }}" class="mt-2 h-16 w-16">
                                        @elseif ($existingLogoPath)
                                            <img src="{{ Storage::url($existingLogoPath) }}" class="mt-2 h-16 w-16">
                                        @endif
                                    </div>
                                    <div>
                                        <label for="website_url" class="block text-gray-700 text-sm font-bold mb-2">Website URL:</label>
                                        <input type="url" wire:model="website_url" id="website_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('website_url') <span class="text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                {{-- <div class="flex justify-end">
                                    <button type="button" wire:click="closeModal" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                        Cancel
                                    </button>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Save
                                    </button>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

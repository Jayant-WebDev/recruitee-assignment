<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Companies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href='{{ url('employees/create') }}'>Add Employees</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-2 px-4 border-b text-left text-gray-700 font-bold">S No</th>
                                <th class="py-2 px-4 border-b text-left text-gray-700 font-bold">First Name</th>
                                <th class="py-2 px-4 border-b text-left text-gray-700 font-bold">Last Name</th>
                                <th class="py-2 px-4 border-b text-left text-gray-700 font-bold">Company Name</th>
                                <th class="py-2 px-4 border-b text-left text-gray-700 font-bold">Email</th>
                                <th class="py-2 px-4 border-b text-left text-gray-700 font-bold">Phone Number</th>
                                <th class="py-2 px-4 border-b text-left text-gray-700 font-bold">#</th>
                                <th class="py-2 px-4 border-b text-left text-gray-700 font-bold">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $index => $employee)
                                <tr class="hover:bg-gray-100">
                                    <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                                    <td class="py-2 px-4 border-b">{{ $employee->first_name }}</td>
                                    <td class="py-2 px-4 border-b">{{ $employee->last_name }}</td>
                                    <td class="py-2 px-4 border-b">{{ $employee->company_name }}</td>
                                    <td class="py-2 px-4 border-b">{{ $employee->email }}</td>
                                    <td class="py-2 px-4 border-b">{{ $employee->phone_number }}</td>
                                    <td class="py-2 px-4 border-b"><a
                                            href='{{ url('employees/' . $employee->id . '/edit') }}'>Edit</a></td>
                                    <td class="py-2 px-4 border-b">
                                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                                            class="inline">
                                            @csrf <!-- CSRF protection -->
                                            @method('DELETE') <!-- Method spoofing for DELETE -->

                                            <button type="submit"
                                                class="text-red-500 hover:text-red-700">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

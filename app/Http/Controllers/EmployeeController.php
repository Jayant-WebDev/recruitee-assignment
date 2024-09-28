<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $employees = DB::table('employees')->select('employees.*', 'companies.name as company_name')
                    ->leftJoin('companies', 'companies.id', 'employees.company_id')
                    ->paginate(10);

        return view('employees.employees', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();

        return view('employees.add', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $values = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'company_id' => $request->company_id,

        ];

        $rules = [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required',
            'phone_number' => 'required | max:15',
            'company_id' => 'required'
        ];

        $validator = Validator::make($values, $rules);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator, 'employees')->withInput();
        }



        // $values['file'] = $filePath;
        // $company = Company::create($values);
        $employee = new Employee();
        $employee->first_name = $request->first_name;
        $employee->last_name = $request->last_name;
        $employee->email = $request->email;
        $employee->phone_number = $request->phone_number;
        $employee->company_id = $request->company_id;
        $employee->save();

        return redirect('employees')->with('messages', 'Employee Added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = DB::table('employees')->select('employees.*', 'companies.name as company_name')
        ->leftJoin('companies', 'companies.id', 'employees.company_id')
        ->first();
        $companies = Company::all();

        return view('employees.edit', compact('employee', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = Employee::find($id);


        $values = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'company_id' => $request->company_id,

        ];

        $rules = [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:companies,email,' . $employee->id,
            'phone_number' => 'required | max:15',
            'company_id' => 'required'
        ];

        $validator = Validator::make($values, $rules);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator, 'employees')->withInput();
        }



        $employee->first_name = $request->first_name;
        $employee->last_name = $request->last_name;
        $employee->email = $request->email;
        $employee->phone_number = $request->phone_number;
        $employee->company_id = $request->company_id;
        $employee->save();

        return redirect('employees')->with('messages', 'Employee Updated');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return redirect()->route('employees.index')->with('error', 'employee not found.');
        }
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'employee deleted successfully.');
    }
}

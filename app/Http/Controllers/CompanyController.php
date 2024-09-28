<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::paginate(15);
        return view('companies.companies', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $values = [
            'name' => $request->name,
            'email' => $request->email,
            'website' => $request->website,
            'file' => $request->file

        ];

        $rules = [
            'name' => 'required|string',
            'website' => 'required|string',
            'email' => 'required|unique:companies,email',
            'file' => 'required|file'
        ];

        $validator = Validator::make($values, $rules);

        if($validator->fails()){

            return redirect()->back()->withErrors($validator, 'companies')->withInput();
        }

        if ($request->hasFile('file')) {

            $filePath = $request->file('file')->store('logos', 'public');

        }


        // $values['file'] = $filePath;
        // $company = Company::create($values);
        $company = new Company();
        $company->name = $request->name;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->file = $filePath;
        $company->save();

        return redirect('companies')->with('messages', 'Company added');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::find($id);

        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $company = Company::find($id);
        $values = [
            'name' => $request->name,
            'email' => $request->email,
            'website' => $request->website,
            'file' => $request->file,


        ];

        $rules = [
            'name' => 'required|string',
            'website' => 'required|string',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'file' => 'required',
        ];

        $validator = Validator::make($values, $rules);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator, 'companies')->withInput();
        }

        if ($request->hasFile('file')) {

            $filePath = $request->file('file')->store('logos', 'public');

        }

        $company = Company::find($id);
        $company->name = $request->name;
        $company->website = $request->website;
        $company->email = $request->email;
        $company->file = isset($filePath) ? $filePath : '';
        $company->save();

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::find($id);

        if (!$company) {
            return redirect()->route('companies.index')->with('error', 'Company not found.');
        }
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}

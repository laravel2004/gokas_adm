<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeRequestStoreForm;
use App\Models\Account;
use App\Models\Employee;
use App\Models\Position;
use App\Models\SettingLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Rap2hpoutre\FastExcel\FastExcel;
use function Termwind\parse;

class EmployeeManagementController extends Controller
{
    private Employee $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function index()
    {
        $employees = $this->employee->all();
        return view('pages.employee.index', compact('employees'));
    }

    public function create()
    {
        $positions = Position::all();
        return view('pages.employee.create', compact('positions'));
    }

    public function store(EmployeeRequestStoreForm $request)
    {
        try {
            $validated = $request->validated();

            $validated['password'] = Hash::make($validated['password']);
            DB::beginTransaction();
            if ($validated['role'] == 'user') {
                $limit = SettingLimit::where('position', $validated['position'])->first();
                $employee = $this->employee->create($validated);
                $account = Account::create([
                   'employee_id' => $employee->id,
                    'limit_paylater' => $limit->limit_paylater,
                    'limit_paylater_used' => 0,
                    'limit_credit' => $limit->limit_credit,
                    'limit_credit_used' => 0,
                    'limit_loan' => $limit->limit_loan,
                    'limit_loan_used' => 0,
                    'point' => 0,
                    'balance' => 100000
                ]);
            } else {
                $this->employee->create($validated);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Employee created successfully',
                'data' => null
            ], 201);
        }
        catch (\Exception $exception){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error creating employee',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $employee = $this->employee->findOrFail($id);
        $positions = Position::all();
        return view('pages.employee.edit', compact('employee', 'positions'));
    }

    public function update(Request $request, $id)
    {
        try {
            $employee = $this->employee->findOrFail($id);
            $validated = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:employees,email,'.$id,
                'role' => 'required',
                'position' => 'required',
                'department' => 'required',
            ]);

            $employee->update($validated);
            return response()->json([
                'status' => true,
                'message' => 'Employee updated successfully',
                'data' => null
            ], 200);
        }
        catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => 'Error updating employee',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $employee = $this->employee->findOrFail($id);
            $employee->delete();

            return response()->json([
                'status' => true,
                'message' => 'Employee deleted successfully',
                'data' => null
            ], 200);
        }
        catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => 'Error deleting employee',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    public function storeWithExcel(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        ini_set('max_execution_time', 300); // Optional: Tambah timeout saat testing

        $errors = [];
        $lineNumber = 1;
        $totalCount = 0;
        $errorCount = 0;

        try {
            $fastExcel = new FastExcel();
            DB::beginTransaction();

            $fastExcel->import($validated['file']->getRealPath(), function ($line) use (&$totalCount, &$errorCount, &$errors, &$lineNumber) {
                try {
                    $employee = Employee::create([
                        'nik' => $line['nik'],
                        'name' => $line['name'],
                        'email' => $line['email'],
                        'role' => "user",
                        'position' => $line['position'],
                        'department' => $line['department'],
                        'password' => Hash::make($line['password']),
                    ]);

                    $limit = SettingLimit::where('position', $line['position'])->first();

                    Account::create([
                        'employee_id' => $employee->id,
                        'limit_paylater' => $limit->limit_paylater ?? 0,
                        'limit_paylater_used' => 0,
                        'limit_credit' => $limit->limit_credit ?? 0,
                        'limit_credit_used' => 0,
                        'limit_loan' => $limit->limit_loan ?? 0,
                        'limit_loan_used' => 0,
                        'point' => 0,
                        'balance' => 100000,
                    ]);

                    $totalCount++;
                } catch (\Exception $e) {
                    $errors[] = ['line' => $lineNumber, 'error' => $e->getMessage()];
                    $errorCount++;
                }
                $lineNumber++;
            });

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Import selesai',
                'total' => $totalCount,
                'errors' => $errors,
                'error_count' => $errorCount,
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error importing employees',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

}

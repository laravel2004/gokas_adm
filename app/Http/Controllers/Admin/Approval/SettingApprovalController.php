<?php

namespace App\Http\Controllers\Admin\Approval;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Approval;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingApprovalController extends Controller
{
    private Employee $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function index()
    {
        $employees = $this->employee->where('role', 'approval')->get();
        return view('pages.setting-approval.index', compact('employees'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                "members" => "required|array",
                "head_id" => "required|integer",
            ]);

            DB::beginTransaction();
            foreach ($validated["members"] as $member) {
                $approval = Approval::where('employee_id', $member)->first();
                if ($approval) {
                    $approval->head_employee_id = $validated["head_id"];
                    $approval->save();
                } else {
                    Approval::create([
                        'employee_id' => $member,
                        'head_employee_id' => $validated["head_id"]
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'data' => null,
                'status' => true,
                'message' => 'Member added successfully'
            ]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error add member',
                'error' => $e->getMessage()
            ], 500);
        }


    }

    public function show($id)
    {
        $head = $this->employee->findOrFail($id);
        $employees = Approval::where('head_employee_id', $id)->with('employee')->get();

        $candidates = $this->employee
            ->where('role', 'user')
            ->whereNotIn('id', function($query) {
                $query->select('employee_id')
                    ->from('approvals');
            })
            ->get();

        return view('pages.setting-approval.show', compact('employees', 'head', 'candidates'));
    }

    public function destroy($id)
    {
        try {
            $record = Approval::findOrFail($id);
            $record->delete();

            return response()->json([
                'data' => null,
                'status' => true,
                'message' => 'Member deleted successfully'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting member',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function check($id)
    {
        try {
            $account = Account::findOrFail($id);
            $employee = Approval::where('employee_id', $account->employee_id)->with('headEmployee')->first();
            if ($employee) {
                return response()->json([
                    'status' => true,
                    'message' => 'Employee found',
                    'data' => $employee,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Employee not found',
                    'data' => null,
                ], 404);
            }
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error add employee',
            ]);
        }
    }
}

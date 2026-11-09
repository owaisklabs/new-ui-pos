<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
{

    public function index()
    {
        $customers = Customer::query()
            ->when($this->hasBranchColumn() && $this->resolveCurrentBranchId(), function ($query) {
                return $query->byBranch($this->resolveCurrentBranchId());
            })
            ->latest()
            ->paginate(50);

        return view('customer.index',compact('customers'));
    }


    public function create()
    {
        return  view('customer.create');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        if ($this->hasBranchColumn()) {
            $data['branch_id'] = $this->resolveCurrentBranchId();
        }

        $customer = Customer::create($data);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => JsonResponse::HTTP_CREATED,
                'message' => 'Customer created successfully',
                'data' => $customer,
            ], JsonResponse::HTTP_CREATED);
        }

        return redirect()->route('customer.index');
    }


    public function show(Customer $customer)
    {
        //
    }


    public function edit(Customer $customer)
    {
        return view('customer.update',compact('customer'));
    }


    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        if ($this->hasBranchColumn() && !$customer->branch_id) {
            $data['branch_id'] = $this->resolveCurrentBranchId();
        }

        $customer->update($data);
        return redirect()->route('customer.index');
    }


    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customer.index');
    }
    public function getAllCustomers()
    {
        $branchId = $this->resolveCurrentBranchId();
        if ($this->hasBranchColumn() && $branchId) {
            Customer::firstOrCreate(
                ['name' => 'Walking Customer', 'branch_id' => $branchId],
                ['phone' => null, 'address' => null]
            );
        } else {
            Customer::firstOrCreate(
                ['name' => 'Walking Customer'],
                ['phone' => null, 'address' => null]
            );
        }

        $customers = Customer::query()
            ->when($this->hasBranchColumn() && $branchId, function ($query) use ($branchId) {
                return $query->byBranch($branchId);
            })
            ->orderByRaw("CASE WHEN LOWER(name) = 'walking customer' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        $walkingCustomerId = optional(
            $customers->first(fn ($customer) => strtolower($customer->name) === 'walking customer')
        )->id;

        return response()->json([
            "status"  => JsonResponse::HTTP_OK,
            "message" => "customers Fetch successfully",
            "data"  => $customers,
            "walking_customer_id" => $walkingCustomerId,
        ], JsonResponse::HTTP_OK);
    }

    private function resolveCurrentBranchId(): ?int
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        if (!empty($user->default_branch_id)) {
            $defaultBranchId = (int) $user->default_branch_id;
            if ($this->isValidBranchId($defaultBranchId)) {
                return $defaultBranchId;
            }
        }

        $firstAccessibleBranchId = (int) optional($user->getAccessibleBranches()->first())->id;

        if ($firstAccessibleBranchId && $this->isValidBranchId($firstAccessibleBranchId)) {
            return $firstAccessibleBranchId;
        }

        return null;
    }

    private function hasBranchColumn(): bool
    {
        try {
            return Schema::hasColumn('customers', 'branch_id');
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function isValidBranchId(?int $branchId): bool
    {
        if (!$branchId) {
            return false;
        }

        try {
            if (!Schema::hasTable('branches')) {
                return false;
            }

            return Branch::whereKey($branchId)->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }
}

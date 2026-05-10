<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function index()
    {
        $customers = Customer::latest()->paginate(50);
        return view('customer.index',compact('customers'));
    }


    public function create()
    {
        return  view('customer.create');
    }


    public function store(Request $request)
    {
        Customer::create($request->all());
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
        $customer->update($request->all());
        return redirect()->route('customer.index');
    }


    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customer.index');
    }
    public function getAllCustomers(){
        $customers =  Customer::latest()->get();
        return response()->json([
            "status"  => JsonResponse::HTTP_OK,
            "message" => "customers Fetch successfully",
            "data"  => $customers,
        ], JsonResponse::HTTP_OK);
    }
}

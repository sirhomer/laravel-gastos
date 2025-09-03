<?php

namespace App\Http\Controllers;

use App\Models\Incomes;
use Illuminate\Http\Request;

class IncomesController extends Controller
{
	/**
	 * Store a newly created income in storage.
	 */
	public function store(Request $request)
	{

		$validated = $request->validate([
			'amount' => 'required|numeric',
			'concept' => 'required|string|max:255',
			'month' => 'string',
		]);

		$income = new Incomes();
		// user_id from authenticated user
		//$income->user_id = $request->user()?->id ?? auth()->id();
        $income->user_id = 1;
		$income->amount = $validated['amount'];
		$income->concept = $validated['concept'];
		$income->month = $validated['month'];
		$income->save();

		return response()->json($income, 201);
	}
}

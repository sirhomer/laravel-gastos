<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ExpenseInstallments;
use App\Models\Incomes;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'concept' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'installments' => 'nullable|integer|min:1',
            'is_paid' => 'nullable|boolean',
            'is_recurring' => 'nullable|boolean',
        ]);

        $data['installments'] = $data['installments'] ?? 1;

    // intentar obtener user_id del auth, si no, fallback a 1
    $userId = $request->input('usuario', Auth::id());

        DB::beginTransaction();
        try {
            $expense = Expense::create([
                'user_id' => $userId,
                'concept' => $data['concept'],
                'amount' => $data['amount'],
                'date' => $data['date'],
                'installments' => $data['installments'],
                'is_paid' => $data['is_paid'] ?? true,
                'is_recurring' => $data['is_recurring'] ?? false,
            ]);

            // crear cuotas
            $total = (float) $data['amount'];
            $parts = (int) $data['installments'];
            $base = floor(($total / $parts) * 100) / 100; // truncar a 2 decimales
            $remainder = round($total - ($base * $parts), 2);

            $start = Carbon::parse($data['date'])->startOfMonth();

            for ($i = 1; $i <= $parts; $i++) {
                $amt = $base;
                if ($i === $parts) {
                    $amt = round($amt + $remainder, 2);
                }

                ExpenseInstallments::create([
                    'expense_id' => $expense->id,
                    'installment_number' => $i,
                    'amount' => $amt,
                    'month' => $start->copy()->addMonths($i - 1)->toDateString(),
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Gasto creado', 'expense' => $expense], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error guardando gasto', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        //
    }
    public function currentMonthExpenses( Request $request)
    {

        // intentar obtener user_id del auth, si no, fallback a 1
        $userId = $request->input('user_id', Auth::id());

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Obtener las cuotas (installments) que pertenecen al usuario para el mes actual.
        // Hacemos un join con la tabla expenses para filtrar por user_id.
        $installments = ExpenseInstallments::join('expenses', 'expense_installments.expense_id', '=', 'expenses.id')
            ->where('expenses.user_id', $userId)
            ->whereBetween('expense_installments.month', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get(['expense_installments.*', 'expenses.concept as expense_concept']);

        // Total gastado en el mes (sumatoria de las cuotas)
        $totalExpenses = $installments->sum('amount');

        // Obtener ingresos del usuario. Según la especificación, en `Incomes` solo hay un valor
        // (no se importa el mes), así que sumamos todos los registros del usuario.
        $totalIncomes = Incomes::where('user_id', $userId)->sum('amount');

        // Calcular resto para gastar
        $remaining = round($totalIncomes - $totalExpenses, 2);

        return response()->json([
            'installments' => $installments,
            'total_expenses' => $totalExpenses,
            'total_incomes' => $totalIncomes,
            'remaining' => $remaining,
        ]);
    }
}

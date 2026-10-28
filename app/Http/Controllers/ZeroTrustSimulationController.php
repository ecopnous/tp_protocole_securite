<?php

namespace App\Http\Controllers;

use App\Services\ZeroTrustSimulator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ZeroTrustSimulationController extends Controller
{
    public function index(Request $request, ZeroTrustSimulator $simulator): View
    {
        return view('dashboard', [
            'scenarios' => $simulator->scenarios(),
            'simulationResult' => $request->session()->get('simulation_result'),
        ]);
    }

    public function simulate(Request $request, ZeroTrustSimulator $simulator): RedirectResponse
    {
        $validated = $request->validate([
            'scenario' => ['required', Rule::in(array_keys($simulator->scenarios()))],
        ], [
            'scenario.required' => 'Choisissez un scénario à simuler.',
            'scenario.in' => 'Le scénario sélectionné n’existe pas.',
        ]);

        return to_route('dashboard')->with('simulation_result', $simulator->simulate($validated['scenario']));
    }
}

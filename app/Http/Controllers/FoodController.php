<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodRequest;
use App\Models\Food;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class FoodController extends Controller
{
    /**
     * List the foods owned by the current user, narrowed by the search terms.
     */
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();

        return Inertia::render('foods/Index', [
            'foods' => Food::query()
                ->ownedBy($request->user())
                ->matching($search)
                ->orderBy('name')
                ->get(),
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a food.
     */
    public function create(): Response
    {
        return Inertia::render('foods/Create');
    }

    /**
     * Store a newly created food.
     */
    public function store(FoodRequest $request): RedirectResponse
    {
        $request->user()->foods()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Food added.')]);

        return to_route('foods.index');
    }

    /**
     * Show the form for editing a food.
     */
    public function edit(Food $food): Response
    {
        Gate::authorize('update', $food);

        return Inertia::render('foods/Edit', [
            'food' => $food,
        ]);
    }

    /**
     * Update the given food.
     */
    public function update(FoodRequest $request, Food $food): RedirectResponse
    {
        $food->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Food updated.')]);

        return to_route('foods.index');
    }

    /**
     * Delete the given food.
     *
     * The row is soft deleted: the five values were typed by hand, and an
     * accidental tap should not cost that.
     */
    public function destroy(Food $food): RedirectResponse
    {
        Gate::authorize('delete', $food);

        $food->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Food deleted.')]);

        return to_route('foods.index');
    }
}

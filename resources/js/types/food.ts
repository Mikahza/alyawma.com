export type FoodUnit = 'g' | 'ml' | 'piece';

export type Food = {
    id: number;
    name: string;
    /** The values below are given for this much of `reference_unit`. */
    reference_quantity: string;
    reference_unit: FoodUnit;
    /** Fixed-precision strings, never floats: see decision D4. */
    protein_grams: string;
    calories: number;
    fibre_grams: string;
    fat_grams: string;
    carbohydrate_grams: string;
    created_at: string;
    updated_at: string;
};

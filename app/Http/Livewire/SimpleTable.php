<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Dish;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

class SimpleTable extends PowerGridComponent
{
    use ActionButton;

    public string $tableName = 'simpleTable';

    public string|int $selectedChef = '';

    public function setUp(): array
    {
        return [
            //            Cache::make()
            //                ->forever(),

            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource()
    {
        return Dish::with('category');
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('name')
            ->addColumn('chef_name')
            ->addColumn('category_id', function ($dish) {
                return $dish->category_id;
            })
            ->addColumn('category_name', function (Dish $dish) {
                return $dish->category->name;
            })
            ->addColumn('price_fmt', function (Dish $dish) {
                return (new \NumberFormatter('en_US', \NumberFormatter::CURRENCY))
                    ->formatCurrency($dish->price, 'USD');
            })
            ->addColumn('in_stock')
            ->addColumn('in_stock_label', function (Dish $dish) {
                if ($dish->in_stock) {
                    return Blade::render('Yes');
                }

                return Blade::render('No');
            })
            ->addColumn('created_at_formatted', function (Dish $dish) {
                return Carbon::parse($dish->created_at)
                    ->timezone('America/Sao_Paulo')->format('d/m/Y');
            });
    }

    public function summarizeFormat(): array
    {
        return [
            'price' => function ($value) {
                return (new \NumberFormatter('en_US', \NumberFormatter::CURRENCY))
                    ->formatCurrency($value, 'USD');
            }
        ];
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Name', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Category', 'category_name'),

            Column::make('Chef', 'chef_name')
                ->searchable()
                ->sortable(),

            Column::make('Price', 'price')
                ->withSum('Sum Price', true, false)
                ->withCount('Count Price', true, false)
                ->withAvg('Avg Price', true, false)
                ->sortable(),

            Column::make('Price', 'price_fmt')
                ->sortable(),

            Column::make('In Stock', 'in_stock_label'),

            Column::make('Created At', 'created_at_formatted'),

            Column::action('Action'),
        ];
    }

    #[On('execute')]
    public function execute(string $component, array $parameters = [])
    {
        dd(get_defined_vars());
    }

    public function actions(Dish $dish): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: '.$dish->id)
                ->id()
                ->class('cursor-pointer block bg-white text-sm text-gray-700 border border-gray-300 rounded py-1.5 px-2 leading-tight focus:outline-none focus:bg-white focus:border-gray-600 dark:border-gray-500 dark:bg-gray-500 2xl:dark:placeholder-gray-300 dark:text-gray-200 dark:text-gray-300')
                ->openModal('edit-stock', ['dishId' => $dish->id])
                ->hideWhen(fn () => $dish->id === 2),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('category_name', 'category_id')
                ->dataSource(Category::all())
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::select('chef_name', 'category_id')
                ->dataSource(collect($this->getCategoryByChefFilter($this->selectedChef)))
                ->optionLabel('name')
                ->optionValue('id')
        ];
    }

    public function afterChangedSelectFilter(string $field, string $label, mixed $value): void
    {
        $this->selectedChef = $value;
    }

    public function getCategoryByChefFilter(string|int $categoryId = null): array
    {
        if (blank($categoryId)) {
            return [
                [
                    'id' => 1,
                    'name' => 'Luan'
                ],
                [
                    'id' => 2,
                    'name' => 'Dan'
                ],
                [
                    'id' => 3,
                    'name' => 'Vitor'
                ],
                [
                    'id' => 4,
                    'name' => 'Claudio'
                ],
            ];
        }

        $values = [
            '1' => [
                [
                    'id' => 'Luan',
                    'name' => 'Luan'
                ],
                [
                    'id' => 2,
                    'name' => 'Dan'
                ],
            ],
            '2' => [
                [
                    'id' => 3,
                    'name' => 'Vitor'
                ],
                [
                    'id' => 4,
                    'name' => 'Claudio'
                ],
            ],
        ];

        return $values[$categoryId];
    }
}

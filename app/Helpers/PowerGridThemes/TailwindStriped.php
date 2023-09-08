<?php

namespace App\Helpers\PowerGridThemes;

use PowerComponents\LivewirePowerGrid\Themes\Components\Table;
use PowerComponents\LivewirePowerGrid\Themes\Theme;

class TailwindStriped extends \PowerComponents\LivewirePowerGrid\Themes\Tailwind
{
    public function table(): Table
    {
        return Theme::table('min-w-full')
            ->div('my-3 rounded-lg relative border border-pg-primary-200 dark:bg-pg-primary-700 dark:border-pg-primary-600')
            ->thead('shadow-sm rounded-t-lg bg-pg-primary-200 dark:bg-gray-900')
            ->thAction('!font-bold')
            ->tdAction('')
            ->tr('')
            ->trFilters('bg-white shadow-sm dark:bg-pg-primary-800')
            ->th('font-semibold px-2 pr-4 py-3 text-left text-xs font-semibold text-pg-primary-700 tracking-wider whitespace-nowrap dark:text-pg-primary-300')
            ->tbody('text-pg-primary-800')
            ->trBody('odd:bg-slate-100 hover:odd:bg-slate-200 dark:odd:bg-pg-primary-700 dark:hover:odd:bg-pg-primary-900 border-b border-pg-primary-100 dark:border-pg-primary-600 hover:bg-pg-primary-50 dark:bg-pg-primary-800 dark:hover:bg-pg-primary-800')
            ->tdBody('pl-[19px] px-3 py-2 whitespace-nowrap dark:text-pg-primary-200')
            ->tdBodyEmpty('px-3 py-2 whitespace-nowrap dark:text-pg-primary-200')
            ->tdBodyTotalColumns('px-3 py-2 whitespace-nowrap dark:text-pg-primary-200 text-sm text-pg-primary-600 text-right space-y-2');
    }
}

<?php

namespace App\Filament\Admin\Clusters;

use Filament\Clusters\Cluster;

class ManagementCluster extends Cluster
{
    protected static ?int $navigationSort = 10;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function getNavigationLabel(): string
    {
        return __('Management');
    }

    public function getTitle(): string
    {
        return __('Management');
    }

    public static function getModelLabel(): string
    {
        return __('Management');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Management');
    }

    public static function getLabel(): string
    {
        return __('Management');
    }
}

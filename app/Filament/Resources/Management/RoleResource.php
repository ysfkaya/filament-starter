<?php

namespace App\Filament\Resources\Management;

use App\Filament\Resources\Management\RoleResource\Pages;
use App\Models\Admin;
use BezhanSalleh\FilamentShield\FilamentShield;
use BezhanSalleh\FilamentShield\Resources\RoleResource as BaseRoleResource;
use BezhanSalleh\FilamentShield\Support\Utils;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class RoleResource extends BaseRoleResource
{
    protected static function getNavigationGroup(): ?string
    {
        return 'Management';
    }

    protected static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? (string) static::getEloquentQuery()->count()
            : null;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNotIn('name', Admin::superRoles());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('filament-shield::filament-shield.field.name'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Hidden::make('guard_name')
                                    ->label(__('filament-shield::filament-shield.field.guard_name'))
                                    ->default(Utils::getFilamentAuthGuard())
                                    ->nullable(),
                                Forms\Components\Toggle::make('select_all')
                                    ->onIcon('heroicon-s-shield-check')
                                    ->offIcon('heroicon-s-shield-exclamation')
                                    ->label(__('filament-shield::filament-shield.field.select_all.name'))
                                    ->helperText(__('filament-shield::filament-shield.field.select_all.message'))
                                    ->reactive()
                                    ->afterStateUpdated(function (Closure $set, $state) {
                                        static::refreshEntitiesStatesViaSelectAll($set, $state);
                                    })
                                    ->dehydrated(fn ($state): bool => $state),
                            ])
                            ->columns([
                                'sm' => 2,
                                'lg' => 3,
                            ]),
                    ]),
                Forms\Components\Tabs::make('Permissions')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('filament-shield::filament-shield.resources'))
                            ->visible(fn (): bool => (bool) Utils::isResourceEntityEnabled())
                            ->reactive()
                            ->schema([
                                Forms\Components\Grid::make([
                                    'sm' => 2,
                                    'lg' => 3,
                                ])
                                ->schema(static::getResourceEntitiesSchema())
                                ->columns([
                                    'sm' => 2,
                                    'lg' => 3,
                                ]),
                            ]),
                        Forms\Components\Tabs\Tab::make(__('filament-shield::filament-shield.pages'))
                            ->visible(fn (): bool => (bool) Utils::isPageEntityEnabled() && (count(FilamentShield::getPages()) > 0 ? true : false))
                            ->reactive()
                            ->schema([
                                Forms\Components\Grid::make([
                                    'sm' => 3,
                                    'lg' => 4,
                                ])
                                ->schema(static::getPageEntityPermissionsSchema())
                                ->columns([
                                    'sm' => 3,
                                    'lg' => 4,
                                ]),
                            ]),
                        Forms\Components\Tabs\Tab::make(__('filament-shield::filament-shield.widgets'))
                            ->visible(fn (): bool => (bool) Utils::isWidgetEntityEnabled() && (count(FilamentShield::getWidgets()) > 0 ? true : false))
                            ->reactive()
                            ->schema([
                                Forms\Components\Grid::make([
                                    'sm' => 3,
                                    'lg' => 4,
                                ])
                                ->schema(static::getWidgetEntityPermissionSchema())
                                ->columns([
                                    'sm' => 3,
                                    'lg' => 4,
                                ]),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('filament-shield::filament-shield.custom'))
                            ->visible(fn (): bool => (bool) Utils::isCustomPermissionEntityEnabled())
                            ->reactive()
                            ->schema([
                                Forms\Components\Grid::make([
                                    'sm' => 3,
                                    'lg' => 4,
                                ])
                                ->schema(static::getCustomEntitiesPermisssionSchema())
                                ->columns([
                                    'sm' => 3,
                                    'lg' => 4,
                                ]),
                            ]),
                    ])
                    ->columnSpan('full'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('name')
                    ->label(__('filament-shield::filament-shield.column.name'))
                    ->formatStateUsing(fn ($state): string => str($state)->headline())
                    ->colors(['primary'])
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('permissions_count')
                    ->label(__('filament-shield::filament-shield.column.permissions'))
                    ->counts('permissions')
                    ->colors(['success']),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament-shield::filament-shield.column.updated_at'))
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'view' => Pages\ViewRole::route('/{record}'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}

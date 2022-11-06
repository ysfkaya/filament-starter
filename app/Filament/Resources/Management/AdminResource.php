<?php

namespace App\Filament\Resources\Management;

use App\Filament\Resources\Management\AdminResource\Pages;
use App\Models\Admin;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $slug = 'management/admins';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->unique(ignoreRecord:true)
                    ->required()
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn ($livewire) => $livewire instanceof Pages\CreateAdmin)
                    ->maxLength(255)
                    ->afterStateHydrated(fn ($component) => $component->state(null))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->disableAutocomplete(),

                Forms\Components\BelongsToManyMultiSelect::make('roles')->rules([
                    Rule::notIn(Admin::superRoles()),
                ])
                ->preload()
                ->relationship('roles', 'name', fn ($query) => $query->whereNotIn('name', Admin::superRoles()))
                ->label(__('Roles')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\BadgeColumn::make('roles.name')->label(__('Roles')),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}

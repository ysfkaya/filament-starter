<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\Pages\EditAdmin;
use App\Models\Admin;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use STS\FilamentImpersonate\Impersonate;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

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
                    ->required(fn () => ! (func_get_arg(2)) instanceof EditAdmin)
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => ! empty($state) ? Hash::make($state) : ''),

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
            ->prependActions([
                Impersonate::make('impersonate'),
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

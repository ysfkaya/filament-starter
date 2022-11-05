<?php

namespace App\Filament\Resources\Management;

use App\Filament\Resources\Management\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $slug = 'management/users';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->unique(ignoreRecord:true)
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                    ->maxLength(255)
                    ->afterStateHydrated(fn ($component) => $component->state(null))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->disableAutocomplete(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

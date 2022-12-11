<?php

namespace App\Filament\Resources\Management;

use App\Filament\Resources\Management\FormResource\Pages;
use App\Models\Form as FormModel;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Ysfkaya\FilamentPhoneInput\PhoneInput;

class FormResource extends Resource
{
    protected static ?string $model = FormModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $slug = 'management/forms';

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Form';

    protected static ?string $modelPluralLabel = 'Forms';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('type')
                ->label('Type')
                ->options([
                    'contact' => 'Contact',
                ])->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'open' => 'Open',
                        'closed' => 'Closed',
                    ])->required(),

                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->usesMeta(),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->usesMeta(),

                Forms\Components\TextInput::make('email')
                    ->label('E-posta')
                    ->required()
                    ->usesMeta(),

                PhoneInput::make('phone')
                    ->usesMeta(),

                Forms\Components\Textarea::make('message')
                    ->required()
                    ->usesMeta(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('type')
                    ->enum([
                        'contact' => 'İletişim',
                    ])
                    ->colors([
                        'warning' => 'contact',
                    ])
                    ->label('Tür')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->extraAttributes([
                        'class' => 'text-left',
                    ])
                    ->searchable()
                    ->sortable()
                    ->usesMeta(),

                Tables\Columns\TextColumn::make('email')
                    ->extraAttributes([
                        'class' => 'text-left',
                    ])
                    ->searchable()
                    ->sortable()
                    ->usesMeta(),

                Tables\Columns\TextColumn::make('subject')
                    ->formatStateUsing(fn ($state) => str($state)->limit(30)->value())
                    ->searchable()
                    ->extraAttributes([
                        'class' => 'text-left',
                    ])
                    ->sortable()
                    ->usesMeta(),

                Tables\Columns\BadgeColumn::make('status')
                    ->enum([
                        'open' => 'Open',
                        'closed' => 'Closed',
                    ])
                    ->colors([
                        'danger' => 'open',
                        'success' => 'closed',
                    ])
                    ->label('Status')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('id')
                    ->formComponent(Forms\Components\TextInput::class)
                    ->label('ID')
                    ->query(fn ($data, $query) => $query->when(! empty(data_get($data, 'isActive')), fn ($q) => $q->whereId(data_get($data, 'isActive')))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('close')
                    ->label('Close')
                    ->color('success')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Close Form')
                    ->modalSubheading('Are you sure you want to close this form?')
                    ->successNotificationTitle('Form successfully closed.')
                    ->action(function ($record, $action) {
                        $record->update(['status' => 'closed']);

                        return $action->success();
                    })->visible(fn ($record) => $record->status === 'open'),
                Tables\Actions\Action::make('open')
                    ->color('danger')
                    ->label('Re-open')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Re-open Form')
                    ->modalSubheading('Are you sure you want to re-open this form?')
                    ->successNotificationTitle('Form successfully re-opened.')
                    ->action(function ($record, $action) {
                        $record->update(['status' => 'open']);

                        return $action->success();
                    })->visible(fn ($record) => $record->status === 'closed'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('meta');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListForms::route('/'),
        ];
    }
}

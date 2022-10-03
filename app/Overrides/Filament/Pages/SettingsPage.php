<?php

namespace App\Overrides\Filament\Pages;

use Filament\Forms\ComponentContainer;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Contracts\HasFormActions;
use Filament\Pages\Concerns;
use Filament\Pages\Page;

/**
 * @property ComponentContainer $form
 */
abstract class SettingsPage extends Page implements HasFormActions
{
    use Concerns\HasFormActions;

    protected static string $settings;

    protected static string $view = 'filament.pages.settings';

    public $data;

    abstract public function group(): string;

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        $data = $this->mutateFormDataBeforeFill(data_get(setting()->all(), $this->group()));

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    public function save(): void
    {
        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('afterValidate');

        $data = $this->mutateFormDataBeforeSave($data);

        // Starts with the group name each of the keys in the data
        $data = collect($data)->mapWithKeys(function ($value, $key) {
            $key = str($key)->start($this->group() . '.')->value();

            return [$key => $value];
        })->toArray();

        $this->callHook('beforeSave');

        setting($data)->save();

        $this->callHook('afterSave');

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl);
        }

        if (filled($this->getSavedNotificationMessage())) {
            Notification::make()
                ->title($this->getSavedNotificationMessage())
                ->success()
                ->send();
        }
    }

    protected function getSavedNotificationMessage(): ?string
    {
        return __('filament::resources/pages/edit-record.messages.saved');
    }

    protected function callHook(string $hook): void
    {
        if (!method_exists($this, $hook)) {
            return;
        }

        $this->{$hook}();
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('filament::resources/pages/edit-record.form.actions.save.label'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getSubmitFormAction(): Action
    {
        return $this->getSaveFormAction();
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->statePath('data')
                ->inlineLabel(config('filament.layout.forms.have_inline_labels')),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return null;
    }
}

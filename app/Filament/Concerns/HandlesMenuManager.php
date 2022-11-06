<?php

namespace App\Filament\Concerns;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Concerns\HasFormActions;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\TemporaryUploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Ysfkaya\Menu\Entities\MenuModel;
use Ysfkaya\Menu\Facades\Menu;
use Ysfkaya\Menu\Http\Requests\MenuRequest;
use Ysfkaya\Menu\MenuItem;
use Ysfkaya\Menu\Rules\MaxDepthRule;

/**
 * @property \Illuminate\Support\Collection $tree
 * @property ComponentContainer $customLinkForm
 */
trait HandlesMenuManager
{
    use HasFormActions;

    public $mountedItem;

    public $mountedItemData = [];

    public $mountedItemMediaUuid = null;

    public $mountedChildTarget;

    public $menuItemForm = [];

    public function addItem(array $data)
    {
        $item = new MenuItem([
            'title' => $data['title'],
            'url' => $data['url'],
            'target' => $data['target'],
            'group' => $this->selectedGroup,
            'exists' => false,
        ]);

        $this->tree->put((string) Str::uuid(), $item->toArray());

        $this->hasChanges = true;
    }

    public function editItem(string $statePath)
    {
        $this->mountedItem = $statePath;

        $this->mountedItemData = $data = Arr::except(data_get($this, $statePath), 'children');

        $this->mountedItemMediaUuid = data_get($data, 'options.media');

        $this->mountAction('item');
    }

    public function removeItem(string $statePath)
    {
        $tree = [
            'tree' => $this->tree->all(),
        ];

        $uuid = Str::afterLast($statePath, '.');

        $parentPath = Str::beforeLast($statePath, '.');
        $parent = data_get($tree, $parentPath);

        data_set($tree, $parentPath, Arr::except($parent, $uuid));

        $this->tree = collect($tree['tree']);

        $this->hasChanges = true;
    }

    public function indentItem(string $statePath)
    {
        $tree = [
            'tree' => $this->tree->all(),
        ];

        $item = data_get($tree, $statePath);
        $uuid = Str::afterLast($statePath, '.');

        $parentPath = Str::beforeLast($statePath, '.');
        $parent = data_get($tree, $parentPath);

        $keys = array_keys($parent);
        $position = array_search($uuid, $keys);

        $previous = $parent[$keys[$position - 1]];

        if (! isset($previous['children'])) {
            $previous['children'] = [];
        }

        $previous['children'][(string) Str::uuid()] = $item;
        $parent[$keys[$position - 1]] = $previous;

        data_set($tree, $parentPath, Arr::except($parent, $uuid));

        $this->tree = collect($tree['tree']);

        $this->hasChanges = true;
    }

    public function dedentItem(string $statePath)
    {
        $tree = [
            'tree' => $this->tree->all(),
        ];

        $item = data_get($tree, $statePath);
        $uuid = Str::afterLast($statePath, '.');

        $parentPath = Str::beforeLast($statePath, '.');
        $parent = data_get($tree, $parentPath);

        $pathToMoveInto = Str::of($statePath)->beforeLast('.')->rtrim('.children')->beforeLast('.');
        $pathToMoveIntoData = data_get($tree, $pathToMoveInto);

        $pathToMoveIntoData[(string) Str::uuid()] = $item;

        data_set($tree, $pathToMoveInto, $pathToMoveIntoData);

        data_set($tree, $parentPath, Arr::except($parent, $uuid));

        $this->tree = collect($tree['tree']);

        $this->hasChanges = true;
    }

    public function moveItemUp(string $statePath)
    {
        $tree = [
            'tree' => $this->tree->all(),
        ];

        $parentPath = Str::beforeLast($statePath, '.');
        $uuid = Str::afterLast($statePath, '.');

        $parent = data_get($tree, $parentPath);
        $hasMoved = false;

        uksort($parent, function ($_, $b) use ($uuid, &$hasMoved) {
            if ($b === $uuid && ! $hasMoved) {
                $hasMoved = true;

                return 1;
            }

            return 0;
        });

        data_set($tree, $parentPath, collect($parent));

        $this->tree = collect($tree['tree']);

        $this->hasChanges = true;
    }

    public function moveItemDown(string $statePath)
    {
        $tree = [
            'tree' => $this->tree->all(),
        ];

        $parentPath = Str::beforeLast($statePath, '.');
        $uuid = Str::afterLast($statePath, '.');

        $parent = data_get($tree, $parentPath);
        $hasMoved = false;

        uksort($parent, function ($a, $_) use ($uuid, &$hasMoved) {
            if ($a === $uuid && ! $hasMoved) {
                $hasMoved = true;

                return 1;
            }

            return 0;
        });

        data_set($tree, $parentPath, $parent);

        $this->tree = collect($tree['tree']);

        $this->hasChanges = true;
    }

    protected function getActions(): array
    {
        return [
            Action::make('addItem')
                ->label('Add Item')
                ->icon('heroicon-o-plus-circle')
                ->color('secondary')
                ->action('addItem')
                ->form([
                    TextInput::make('title')
                        ->label('Title')
                        ->rules(['required', 'string', 'max:255'])
                        ->required(),

                    TextInput::make('url')
                        ->label('URL')
                        ->rules(['required', 'string'])
                        ->required(),

                    Select::make('target')->options([
                        '_self' => 'Same tab',
                        '_blank' => 'New tab',
                    ])->rules(['required'])->default('_self')->required(),
                ]),

            Action::make('save')
                ->label(__('filament::resources/pages/edit-record.form.actions.save.label'))
                ->action(function (Action $action) {
                    try {
                        $availableGroups = array_keys(config('menu.groups'));

                        $rules = [
                            'selectedGroup' => ['required', 'string', Rule::in($availableGroups)],
                            'tree' => ['required', 'array', new MaxDepthRule($this->selectedGroup)],
                        ];

                        MenuRequest::depthValidationRules($rules, $this->tree->all());

                        $messages = [];

                        MenuRequest::depthValidationMessages($messages, $this->tree->all());

                        $this->validate($rules, $messages);
                    } catch (ValidationException $e) {
                        $messages = '<li>'.implode('</li><li>', ($validationMessages = $e->validator->errors()->all())).'</li>';

                        $title = $e->getMessage() == $validationMessages[0] ? 'The given data was invalid.' : $e->getMessage();

                        Notification::make()
                            ->title($title)
                            ->body('<ul>'.$messages.'</ul>')
                            ->danger()
                            ->send();

                        return;
                    }

                    $menuRequest = MenuRequest::createFromGlobals()->replace([])->merge([
                        'tree' => $this->tree->all(),
                        'group' => $this->selectedGroup,
                        'locale' => app()->getLocale(),
                    ]);

                    Menu::update($menuRequest);

                    Notification::make()
                        ->title('Menu saved')
                        ->success()
                        ->send();

                    $this->hasChanges = false;

                    $action->color('primary');
                })->color($this->getSaveButtonColor()),

            Action::make('item')
                ->mountUsing(function (ComponentContainer $form) {
                    if (! $this->mountedItem) {
                        return;
                    }

                    if ($uuid = $this->mountedItemMediaUuid) {
                        $this->mountedItemData['image'] = [
                            (string) Str::uuid() => $uuid,
                        ];
                    } else {
                        $this->mountedItemData['image'] =
                            isset($this->mountedItemData['image']) ? [
                                (string) Str::uuid() => $this->mountedItemData['image'],
                            ] :
                            null;
                    }

                    $form->fill($this->mountedItemData);
                })
                ->view('filament.components.menu.hidden-action')
                ->form([
                    TextInput::make('title')
                        ->label('Title')
                        ->required(),
                    TextInput::make('url')
                        ->label('URL')
                        ->required(),
                    Select::make('target')->options([
                        '_self' => 'Same tab',
                        '_blank' => 'New tab',
                    ]),
                    SpatieMediaLibraryFileUpload::make('image')
                        ->hidden(fn () => $this->selectedGroup === 'footer')
                        ->getUploadedFileUrlUsing(function (string $file) {
                            if (Str::isUuid($file) && $media = Media::findByUuid($file)) {
                                // @phpstan-ignore-next-line
                                return $media->getUrl();
                            }

                            $storage = TemporaryUploadedFile::createFromLivewire($file);

                            if (! $storage->exists()) {
                                return null;
                            }

                            return $storage->temporaryUrl();
                        })
                        ->deleteUploadedFileUsing(function (string $file) {
                            if (Str::isUuid($file) && $media = Media::findByUuid($file)) {
                                $options = $media->model->options ?? [];

                                // @phpstan-ignore-next-line
                                $media->model->update([
                                    'options' => Arr::except($options, 'media'),
                                ]);

                                $media->delete();

                                return true;
                            }

                            $storage = TemporaryUploadedFile::createFromLivewire($file);

                            if (! $storage->exists()) {
                                return;
                            }

                            $storage->delete();
                        })
                        ->afterStateHydrated(function ($state, $component) {
                            if (blank($state)) {
                                $component->state([]);

                                return;
                            }

                            $temporaryFiles = collect(Arr::wrap($state))
                                ->whereInstanceOf(TemporaryUploadedFile::class)
                                ->filter(static fn ($file) => $file->exists())
                                ->mapWithKeys(static fn ($file): array => [((string) Str::uuid()) => $file->getFilename()]);

                            if ($temporaryFiles->isNotEmpty()) {
                                $component->state($temporaryFiles->all());
                            } else {
                                $mediaFiles = collect(Arr::wrap($state))
                                    ->filter(fn ($uuid) => Str::isUuid($uuid))
                                    ->all();

                                $component->state($mediaFiles);
                            }
                        })
                        ->image()
                        ->preserveFilenames(),
                ])
                ->modalWidth('lg')
                ->action(function (array $data) {
                    $image = head($this->mountedActionData['image'] ?? []);

                    if ($image instanceof TemporaryUploadedFile) {
                        $data['image'] = $image->serializeForLivewireResponse();
                    }

                    if ($this->mountedItem) {
                        $modelData = data_get($this, $this->mountedItem);

                        $tree = [
                            'tree' => $this->tree->all(),
                        ];

                        data_set($tree, $this->mountedItem, array_merge($modelData, $data));

                        $this->tree = collect($tree['tree']);

                        if (isset($modelData['id']) && MenuModel::where('id', $modelData['id'])->exists()) {
                            $this->mountAction('save');
                        } else {
                            $this->hasChanges = true;
                        }

                        $this->mountedItem = null;
                        $this->mountedItemData = [];
                    }

                    $this->mountedActionData = [];
                })
                ->modalButton('Save')
                ->label('Item'),
        ];
    }
}

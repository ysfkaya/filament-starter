<?php

namespace Ysfkaya\Menu;

use App\Traits\HasTemporaryFiles;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;
use Ysfkaya\Menu\Entities\MenuModel;
use Ysfkaya\Menu\Facades\Menu;
use Ysfkaya\Menu\Http\Requests\MenuRequest;

class MenuRepository
{
    use HasTemporaryFiles;

    public function __construct(protected MenuModel $model)
    {
        // code...
    }

    public function query()
    {
        return $this->model->newScopedQuery()->defaultOrder();
    }

    public function create(MenuItemCollection $items)
    {
        $items->each(fn (MenuItem $item) => $this->model::create($item->toArray()));

        $this->uploadImages($items->original);
    }

    public function createFromRequest(MenuRequest $request)
    {
        return $this->create($request->getCollection());
    }

    public function update(MenuRequest $request)
    {
        $query = $this->query()->whereGroup($request->group)->whereLocale($request->locale);

        $query->rebuildTree(
            $request->getCollection()->toArray(),
            delete: true
        );

        $this->uploadImages(
            $request->getCollection(transform:false)
                    ->toArray()
        );
    }

    protected function uploadImages($data)
    {
        $group = data_get(head($data), 'group');

        $tree = Menu::getTreeByGroupAndCurrentLocale($group)->get();

        $this->uploadTemporaryImages(
            data:$data,
            valueFrom: 'children',
            parentPostFix: '.children',
            uploadCallback: function (TemporaryUploadedFile $file, $index) use ($tree) {
                $modelIndex = Str::before($index, '.image');

                /** @var MenuModel $model */
                $model = data_get($tree, $modelIndex);

                if ($model) {
                    $media = add_media_from_disk($model, $file)->toMediaCollection();

                    $model->update([
                        'options' => array_merge(Arr::wrap($model->options), [
                            'media' => $media->uuid,
                        ]),
                    ]);

                    return $media;
                }
            }
        );
    }
}

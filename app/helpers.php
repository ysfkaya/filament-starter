<?php

use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use Brick\Money\Context\CustomContext;
use Brick\Money\Money;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\FileAdder;
use Symfony\Component\Intl\Currencies;

if (! function_exists('serializeMediaUuid')) {
    function serializeMediaUuid(string|array $uuid): string|array
    {
        if (is_array($uuid)) {
            return array_map(fn ($item) => serializeMediaUuid($item), $uuid);
        }

        if (strpos($uuid, 'media_uuid:') !== false) {
            return $uuid;
        }

        return sprintf('media_uuid:%s', $uuid);
    }
}

if (! function_exists('unserializeUuid')) {
    function unserializeMediaUuid(string|array|null $uuid): string|array|null
    {
        if (is_null($uuid)) {
            return null;
        }

        if (is_array($uuid)) {
            return array_map(fn ($item) => unserializeMediaUuid($item), $uuid);
        }

        return str_replace('media_uuid:', '', $uuid);
    }
}

if (! function_exists('add_media_from_disk')) {
    function add_media_from_disk(HasMedia $model, TemporaryUploadedFile $file): FileAdder
    {
        $disk = config('livewire.temporary_file_upload.disk', 'default');

        $pathToTemporaryLivewireFile = (string) Str::of($file->getRealPath())
            ->after(Storage::disk($disk)->path('/'))
            ->ltrim('/');

        $fileName = $file->getClientOriginalName();

        $name = str($fileName)->beforeLast('.')->title()->value();

        // @phpstan-ignore-next-line
        return $model->addMediaFromDisk($pathToTemporaryLivewireFile, $disk)->usingName($name)->usingFileName($fileName);
    }
}

if (! function_exists('price')) {
    /**
     * @param  float  $amount
     * @return Money
     */
    function price(float $amount, $currency = 'USD', $roundingMode = RoundingMode::HALF_UP): Money
    {
        $context = new CustomContext(Currencies::getFractionDigits($currency));

        try {
            return Money::of($amount, $currency, $context, roundingMode: RoundingMode::UNNECESSARY);
        } catch (RoundingNecessaryException $th) {
            return Money::of($amount, $currency, $context, roundingMode: $roundingMode);
        }
    }
}

if (! function_exists('formatMoney')) {
    /**
     * @param  Money  $money
     * @return string
     */
    function formatMoney(Money $money, ?string $locale = null)
    {
        $locale = $locale ?? config('app.locale');

        return $money->formatTo($locale, allowWholeNumber: true);
    }
}

if (! function_exists('summary_data')) {
    /**
     * @return string
     */
    function summary_data($target, $keys, $default = null, $limit = 25)
    {
        $data = data_get_sequence($target, $keys, $default);

        $content = preg_replace('/<h[1-6]>.*?<\/h[1-6]>/', '', $data);

        // Replace all within {} with nothing
        $content = preg_replace('/{.*?}/', '', $content);

        return str($content)->stripTags()->words($limit)->squish()->value();
    }
}

if (! function_exists('data_get_sequence')) {
    function data_get_sequence($target, $keys, $default = null)
    {
        if (is_array($keys)) {
            foreach ($keys as $key) {
                $value = data_get($target, $key, $default);

                if ($value !== $default) {
                    return $value;
                }
            }
        }

        return data_get($target, $keys, $default);
    }
}

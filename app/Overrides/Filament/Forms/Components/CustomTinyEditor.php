<?php

namespace App\Overrides\Filament\Forms\Components;

use Closure;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor as BaseTinyEditor;

class CustomTinyEditor extends BaseTinyEditor
{
    // TinyMCE var: relative_urls
    protected bool $relativeUrls = false;

    protected string|Closure|null $fileAttachmentsDiskName = 'public';

    protected string|Closure|null $fileAttachmentsDirectory = 'attachments';
}

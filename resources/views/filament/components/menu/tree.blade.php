@props([
    'level' => 1,
    'maxDepth' => 1,
    'tree' => [],
    'path' => 'tree',
])

<ul {{ $attributes->class(['flex flex-col', 'pl-' . ($level - 1) * 4, 'w-full main-tree' => $level === 1]) }}>
    @foreach ($tree as $uuid => $item)
        <x-menu::tree-item :path="$path . '.' . $uuid" :item="$item" :moveUp="!$loop->first" :moveDown="!$loop->last" :indent="$level < $maxDepth && !$loop->first && $loop->count > 1"
            :dedent="$level > 1" :child="$level > 1">

            @if ($level < $maxDepth && $item['children'])
                <x-slot:sub-tree>
                    <x-menu::tree :path="$path . '.' . $uuid . '.children'" :tree="$item['children']" :level="$level + 1" :maxDepth="$maxDepth" />
                </x-slot:sub-tree>
            @endif
        </x-menu::tree-item>
    @endforeach
</ul>

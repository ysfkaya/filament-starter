<?php

namespace Ysfkaya\FilamentNotification\Http\Livewire;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Filament\Facades\Filament;
use Filament\Forms;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;
use Livewire\WithPagination;
use Ysfkaya\FilamentNotification\Concerns\HasActions;

class NotificationFeed extends Component implements Forms\Contracts\HasForms
{
    use WithPagination;
    use HasActions;
    use Forms\Concerns\InteractsWithForms;

    protected $feed;

    public $totalUnread;

    /**
     * @return Model<Authenticatable>
     */
    protected function user()
    {
        return Filament::auth()->user();
    }

    public function boot()
    {
        $this->refresh();
    }

    public function refresh()
    {
        $this->hydrateNotificationFeed();
        $this->prepareActions();
    }

    public function hydrateNotificationFeed()
    {
        $perPage = config('filament-notification::feed.perPage', 10);

        $notifications = $this->user()->unreadNotifications()->orderByDesc('created_at');

        $onlyTypes = config('filament-notification::feed.onlyTypes', []);

        if (! empty($onlyTypes)) {
            $this->feed->whereIn('type', $onlyTypes);
        }

        $interval = config('filament-notification::feed.interval');

        if (! empty($interval)) {
            $this->feed->where('created_at', '>=', Carbon::now()->sub(CarbonInterval::create($interval)));
        }

        $this->feed = $notifications->paginate($perPage);

        $this->totalUnread = $this->user()->unreadNotifications()->count();
    }

    public function markAllAsRead()
    {
        $this->user()->unreadNotifications()->update(['read_at' => now()]);

        $this->refresh();
    }

    protected function getForms(): array
    {
        return [
            'mountedNotificationActionForm' => $this->makeForm()
                ->schema(($action = $this->getMountedNotificationAction()) ? $action->getFormSchema() : [])
                ->model($this->getMountedNotificationActionRecord() ?? DatabaseNotification::class)
                ->statePath('mountedNotificationActionData'),
        ];
    }

    protected function resolveNotificationRecord(?string $key): ?Model
    {
        return DatabaseNotification::find($key);
    }

    protected function prepareActions(): void
    {
        foreach ($this->feed as $notification) {
            if (isset($this->cachedNotificationActions[$notification->type])) {
                continue;
            }
            $actions = [];
            if (method_exists($notification->type, 'notificationFeedActions')) {
                $actions = call_user_func([$notification->type, 'notificationFeedActions']);
            }
            $this->cacheNotificationActions($notification->type, $actions);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('filament-notification::feed', [
            'notifications' => $this->feed,
        ]);
    }
}

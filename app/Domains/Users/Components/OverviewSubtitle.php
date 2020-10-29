<?php

namespace App\Domains\Users\Components;

use App\Domains\Users\Enums\GroupEnum;
use Illuminate\View\Component;

/**
 * Class OverviewSubtitle
 *
 * @package App\Domains\Users\Components
 */
class OverviewSubtitle extends Component
{
    public ?string $filter = null;

    public function __construct(?string $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('users._subtitle', ['text' => $this->determineSubtitleText()]);
    }

    private function determineSubtitleText(): string
    {
        $applicationName = config('app.name');

        switch ($this->filter) {
            case GroupEnum::USER:      return __('overview from all users is the default user group.');
            case GroupEnum::DEVELOPER: return __('overview from all the users in the developer user group');
            case GroupEnum::WEBMASTER: return __('overview from all the users in the webmaster user group.');
            case 'deleted':            return __('Overview from all the users that are marked for deletion');
            default:                   return __("overview from all the users currently in :application", ['application' => $applicationName]);
        }
    }
}

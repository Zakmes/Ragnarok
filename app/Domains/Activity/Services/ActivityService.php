<?php

namespace App\Domains\Activity\Services;

use App\Domains\Activity\Models\Activity;
use App\Support\Services\BaseService;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Str;

/**
 * Class ActivityService
 *
 * @package App\Domains\Activity\Services
 */
class ActivityService extends BaseService
{
    /**
     * ActivityService constructor.
     *
     * @param  Activity $activityModel The database model for the activity logs in the application.
     * @return void
     */
    public function __construct(Activity $activityModel)
    {
        $this->model = $activityModel;
    }

    /**
     * Method for getting
     *
     * @param  string|null $filter  The filter u want to apply on your overview.
     * @param  int         $perPage The amount of results u want to display in your overview
     * @param  array       $columns The column names you want to user in your view.
     * @return Paginator
     */
    public function getActivities(?string $filter = null, $perPage = 15, array $columns = ['*']): Paginator
    {
        $filter = $this->prepareFilterKeyword($filter);
        $query = $this->model->newModelQuery();

        foreach ($this->getFilterKeywords() as $keyword) {
            $query->when($this->filterMatchesKeyword($filter, $keyword->log_name), static function (Builder $query) use ($filter): Builder {
                return $query->where('log_name', $filter);
            });
        }

        return $query->paginate($perPage, $columns);
    }

    /**
     * Method for getting all the log names from the activity table and make them them loopable for the filter keywords.
     *
     * @return Collection
     */
    public function getFilterKeywords(): Collection
    {
        return $this->model->distinct()->get('log_name');
    }

    /**
     * Method for preparing the given filter name to match against the keywords in the database resource.
     *
     * @param  string|null $filter The given filter name from the user in the application.
     * @return string|null
     */
    private function prepareFilterKeyword(?string $filter): ?string
    {
        if ($this->keywordIsNotfilled($filter) || $this->doesntNeedPreparation($filter)) {
            return $filter;
        }

        return str_replace('%20', ' ', $filter);
    }

    /**
     * Method for determining that the keyword needs to be converted or not.
     * ----
     * IF yes: than te conversion will be executed
     * IF not: simply return the raw given keyword.
     *
     * @param  string|null $filter The given filter name from the user in the application.
     * @return bool
     */
    private function doesntNeedPreparation(?string $filter): bool
    {
        return Str::contains($filter, '%20');
    }

    /**
     * Method for determining if the keyword is filled in the application.
     *
     * @param  string|null $filter The given filter name from the user in the application.
     * @return bool
     */
    private function keywordIsNotfilled(?string $filter)
    {
        return $filter === null;
    }

    /**
     * Method for determining whether we have a match against a keyword or not.
     *
     * @param  string|null $filter  The given filter name from the user in the application.
     * @param  string      $keyword The keyword value from the distinct query that is runned against the activities table.
     * @return bool
     */
    private function filterMatchesKeyword(?string $filter, string $keyword)
    {
        return $filter === $keyword;
    }

    /**
     * Method for getting all the information that is needed for the dashboard.
     *
     * @return SupportCollection
     */
    public function getDashBoardInfo(): SupportCollection
    {
        return collect([
            'logs' => $this->model->orderBy('id', 'DESC')->limit(10)->get(),
            'todayCount' => $this->model->whereDate('created_at', now()->today())->count(),
            'totalCount' => $this->count(),
        ]);
    }
}

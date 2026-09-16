<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

function durationFormat($seconds)
{
    if ($seconds < 60) {

        return strval($seconds) . " giây";

    } else if ($seconds >= 60 && $seconds < 3600) {

        return strval(round($seconds / 60)) . " phút";

    } else if ($seconds >= 3600 && $seconds < 86400) {

        return strval(round($seconds / 3600)) . " giờ";

    } else if ($seconds >= 86400 && $seconds < 604800) {

        return strval(round($seconds / 86400)) . " ngày";

    } else if ($seconds >= 604800 && $seconds < 2630000) {

        return strval(round($seconds / 604800)) . " tuần";

    } else if ($seconds >= 2630000 && $seconds < 31557600) {

        return strval(round($seconds / 2630000)) . " tháng";

    } else {

        return strval(round($seconds / 31557600)) . " năm";

    }
}

function convertDurationSeconds($seconds)
{
    if ($seconds < 60) {

        return $seconds;

    } else if ($seconds >= 60 && $seconds < 3600) {

        return round($seconds / 60);

    } else if ($seconds >= 3600 && $seconds < 86400) {

        return round($seconds / 3600);

    } else if ($seconds >= 86400 && $seconds < 604800) {

        return round($seconds / 86400);

    } else if ($seconds >= 604800 && $seconds < 2630000) {

        return round($seconds / 604800);

    } else if ($seconds >= 2630000 && $seconds < 31557600) {

        return round($seconds / 2630000);

    } else {

        return round($seconds / 31557600);

    }
}

function periodDetection($seconds)
{
    if ($seconds < 60) {

        return 'seconds';

    } else if ($seconds >= 60 && $seconds < 3600) {

        return 'minutes';

    } else if ($seconds >= 3600 && $seconds < 86400) {

        return 'hours';

    } else if ($seconds >= 86400 && $seconds < 604800) {

        return 'days';

    } else if ($seconds >= 604800 && $seconds < 2630000) {

        return 'weeks';

    } else if ($seconds >= 2630000 && $seconds < 31557600) {

        return 'months';

    } else {

        return 'years';

    }
}

function update_env($data = []): void
{

    $path = base_path('.env');

    if (file_exists($path)) {
        foreach ($data as $key => $value) {
            file_put_contents($path, str_replace(
                $key . '=' . env($key), $key . '=' . $value, file_get_contents($path)
            ));
        }
    }

}

if (!function_exists('query_by_cols')) {
    function query_by_cols(Builder &$query, array $cols = [], array $params = []): Builder
    {
        if (blank($cols) || blank($params)) return $query;

        foreach ($cols as $col) {
            $value = Arr::get($params, $col);
            $query = $query->when(filled($value), fn($query) => $query->where($col, $value));
        }
        return $query;
    }
}

if (!function_exists('search_by_cols')) {
    function search_by_cols(Builder &$query, $value, array $cols = []): Builder
    {
        if (!filled($value)) return $query;

        $query = $query->where(function ($query) use ($value, $cols) {
            foreach ($cols as $col) {
                $query->orWhere($col, 'like', '%' . $value . '%');
            }
        });

        return $query;
    }
}

if (!function_exists('paginate_with_params')) {
    function paginate_with_params($query, array $params = []): LengthAwarePaginator
    {
        $perPage = config('app.pagination') ?: 10;

        if (!empty($params['perPage'])) {
            $perPage = (int)$params['perPage'];
        }

        if (!empty($params['row'])) {
            $perPage = (int)$params['row'];
        }

        return $query->paginate($perPage);
    }
}

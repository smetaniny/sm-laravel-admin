<?php

namespace Smetaniny\SmLaravelAdmin\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Smetaniny\SmLaravelAdmin\Contracts\QueryStrategyInterface;

/**
 * Класс `GetAllStrategy` реализует стратегию для получения всех записей из результата запроса.
 */
class GetAllStrategy implements QueryStrategyInterface
{
    /**
     * Метод `execute` принимает объект запроса Builder и возвращает все записи из результата запроса.
     *
     * @param Builder $query
     *
     * @return array|Collection
     */
    public function execute(Builder $query): array|Collection
    {
        return $query->get();
    }
}

<?php

namespace Smetaniny\SmLaravelAdmin\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Smetaniny\SmLaravelAdmin\Contracts\QueryStrategyInterface;

/**
 * Класс `GetFirstStrategy` реализует стратегию для получения первой записи из результата запроса.
 */
class GetFirstStrategy implements QueryStrategyInterface
{
    /**
     * Метод `execute` принимает объект запроса Builder и возвращает первую запись из результата запроса.
     *
     * @param Builder $query
     *
     * @return Model|null
     */
    public function execute(Builder $query): null|Model
    {
        return $query->first();
    }
}

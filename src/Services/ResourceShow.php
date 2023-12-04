<?php

namespace Smetaniny\SmLaravelAdmin\Services;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Smetaniny\SmLaravelAdmin\Contracts\QueryStrategyInterface;
use Smetaniny\SmLaravelAdmin\Contracts\ResourceShowInterface;
use Smetaniny\SmLaravelAdmin\Exceptions\SmAdminException;

/**
 * Отображение списка ресурсов
 */
class ResourceShow implements ResourceShowInterface
{
    // Экземпляр запроса Builder для работы с базой данных
    private Builder $query;

    // Стратегия для ответа
    private QueryStrategyInterface $queryStrategy;

    // Экземпляр запроса HTTP Request для доступа к данным запроса
    protected Request $request;

    // Начальная позиция для пагинации
    protected int $start = 0;

    // Конечная позиция для пагинации
    protected int $end = 0;

    // Количество результатов
    protected int $count = 0;

    // Массив значений сортировки (_order и _sort)
    protected ?array $sort = null;

    // Массив параметров пагинации (_start и _end)
    protected ?array $pagination= null;

    // Массив параметров фильтрации
    protected ?array $filter= null;

    public function __construct(GetAllStrategy $getAllStrategy)
    {
        // Устанавливаем GetAllStrategy как стратегию по умолчанию
        $this->setQueryStrategy($getAllStrategy);
    }

    /**
     * Настройка объекта
     *
     * @param Builder $query - Экземпляр запроса Builder
     * @param Request $request - Экземпляр запроса HTTP Request
     *
     * @return ResourceShow
     * @throws SmAdminException
     */
    public function queryBuilder(Builder $query, Request $request): self
    {
        // Присваиваем экземпляр запроса Builder
        $this->query = $query;

        // Присваиваем экземпляр HTTP Request
        $this->request = $request;

        $this->count();

        // Возвращаем экземпляр класса
        return $this;
    }

    /**
     * Вычисление количества результатов
     *
     * @return ResourceShow
     * @throws SmAdminException
     */
    public function count(): self
    {
        // Получаем начальную позицию из параметра запроса '_start'
        $this->start = (int) $this->request->input('_start', 0);

        // Получаем конечную позицию из параметра запроса '_end'
        $this->end = (int) $this->request->input('_end', 0);

        // Выбрасываем исключение
        if (!is_numeric($this->start) || !is_numeric($this->end)) {
            throw new SmAdminException('Недопустимые значения для _start или _end');
        }

        // Вычисляем количество результатов
        if ($this->end > 0) {
            $this->count = min($this->query->count(), $this->end - $this->start + 1);
        } else {
            $this->count = max($this->query->count() - $this->start, 0);
        }

        return $this;
    }

    /**
     * Сортировка
     *
     * @return ResourceShow
     */
    public function sort(): self
    {
        if ($this->sort === null) return $this;

        // Получаем массив значений сортировки из параметров запроса
        $this->sort = $this->request->only('_order', '_sort');

        // Если заданы параметры сортировки (_order и _sort), применяем сортировку
        if (isset($this->sort['_order'], $this->sort['_sort'])) {
            $this->query->orderBy($this->sort['_sort'], $this->sort['_order']);
        }

        // Получаем параметр сортировки из запроса и декодируем его из JSON
        $this->sort = json_decode($this->request->input('sort', ''), true);

        // Если параметр сортировки задан, применяем сортировку
        if (isset($this->sort['field'], $this->sort['order'])) {
            $field = $this->sort['field'];
            // Преобразуем в верхний регистр (ASC или DESC)
            $order = strtoupper($this->sort['order']);
            // Сортируем
            $this->query->orderBy($field, $order);
        }

        // Возвращаем экземпляр класса
        return $this;
    }


    /**
     * Фильтрация
     *
     * @return ResourceShow
     * @throws SmAdminException
     */
    public function filter(): self
    {
        if ($this->filter === null) return $this;

        // Массив полей для, content
        $table = "";
        $contentFields = [];
        // Получаем параметры фильтрации из запроса
        $this->filter = json_decode($this->request->input('filter', ''), true);

        // Проверяем на наличие ошибок при декодировании JSON
        if ($this->filter === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new SmAdminException('Недопустимый JSON в фильтре: ' . json_last_error_msg());
        }

        // Если в фильтре указаны contentFields и это массив, сохраняем его и удаляем из фильтра
        if (isset($this->filter['contentFields']) && is_array($this->filter['contentFields'])) {
            $contentFields = $this->filter['contentFields'];
            unset($this->filter['contentFields']);
        }

        // Если в фильтре указаны table, сохраняем его и удаляем из фильтра
        if (isset($this->filter['table'])) {
            $table = $this->filter['table'];
            unset($this->filter['table']);
        }

        // Применяем фильтры к запросу
        if (!empty($this->filter)) {
            foreach ($this->filter as $field => $value) {
                // Удаляем пробелы в начале и в конце значения
                $value = trim($value);

                // Применяем фильтр для date_start
                if ($field === 'date_start') {
                    $this->query->where($table . '.created_at', '>=', $value);
                } // Применяем фильтр для date_end
                else if ($field === 'date_end') {
                    $date_end = Carbon::createFromFormat('Y-m-d', $value)->endOfDay();
                    $this->query->where($table . '.created_at', '<=', $date_end);
                } // Применяем фильтр для content c полями contentFields
                else if ($field === 'content') {
                    $this->query->where(function ($query) use ($contentFields, $value) {
                        foreach ($contentFields as $fieldToSearch) {
                            $query->orWhere($fieldToSearch, 'like', '%' . $value . '%');
                        }
                    });
                } else {
                    // Применяем остальные фильтры
                    $this->query->where($field, $value);
                }
            }
        }

        // Возвращаем экземпляр класса
        return $this;
    }

    /**
     * Пагинация
     *
     * @return ResourceShow
     */
    public function pagination(): self
    {
        if ($this->pagination === null) return $this;

        // Получаем массив параметров пагинации из параметров запроса
        $this->pagination = $this->request->only('_start', '_end');

        // Если заданы параметры пагинации (_start и _end), применяем пагинацию
        if (isset($this->pagination['_start'], $this->pagination['_end'])) {
            $this->query->skip($this->pagination['_start'])->take($this->pagination['_end'] - $this->pagination['_start']);
        }

        // Возвращаем экземпляр класса
        return $this;
    }

    /**
     * Выполняет запрос с использованием текущей стратегии, и возвращает результат.
     *
     * @param Builder $query - Экземпляр запроса Builder
     *
     * @return mixed - Результат выполнения запроса
     */
    public function execute(Builder $query): mixed
    {
        return $this->queryStrategy->execute($this->query);
    }

    /**
     * Изменение стратегии
     *
     * @param QueryStrategyInterface $strategy
     *
     * @return ResourceShow
     */
    public function setQueryStrategy(QueryStrategyInterface $strategy): self
    {
        $this->queryStrategy = $strategy;

        return $this;
    }

    /**
     * Изменение Builder
     *
     * @param Builder $query
     *
     * @return ResourceShow
     */
    public function setQuery(Builder $query): self
    {
        $this->query = $query;

        // Возвращаем экземпляр класса
        return $this;
    }

    /**
     * Получение результата
     *
     * @return JsonResponse
     */
    public function responseJson(): JsonResponse
    {
        // Возвращаем JSON-ответ с данными и необходимыми заголовками
        return response()
            // Возвращаем данные, полученные из запроса Builder
            ->json($this->execute($this->query))
            // Заголовок с типом содержимого ответа (JSON)
            ->header('Content-Type', 'application/json')
            // Заголовок с общим количеством элементов
            ->header('X-Total-Count', $this->count)
            // Заголовок с информацией о диапазоне и общем количестве элементов
            ->header('Content-Range', 'items ' . $this->start . '-' . ($this->start + $this->count - 1) . '/' . $this->count);
    }

    /**
     *  Получение результата
     *
     * @return mixed
     */
    public function response(): mixed
    {
        return $this->execute($this->query);
    }
}


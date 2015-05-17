<?php namespace App\Repositories\Eloquent;

use App\Repositories\BaseInterface;
use Carbon\Carbon;
use Exception;

abstract class BaseRepository {

	public function update($id, array $data) {
		$this->model->where('id', $id)->update($data);
	}

	/**
	 * Sort the model
	 * @param  string $col
	 * @param  string $order
	 * @return $this
	 */
	public function sort($col = 'id', $order = 'asc') {
		
		$this->model = $this->model->orderBy($col, $order);

		return $this;
	}

	/**
	 * Filter results by created date range
	 * 
	 * @param  string $start
	 * @param  string $end
	 * @param  string $table
	 * @return $this
	 */
	public function whereCreated($start = null, $end = null, $table = null) {

		if (!$start || !$end) {
			return $this;
		}

		$start = Carbon::createFromFormat('m/d/Y', trim($start))->startOfDay()->toDateTimeString();
		$end = Carbon::createFromFormat('m/d/Y', trim($end))->endOfDay()->toDateTimeString();
		
		$this->model = $this->model
			->where($table . '.created_at', '>', $start)
			->where($table . '.created_at', '<', $end);

		return $this;
	}

	/**
	 * Search filter on query
	 * 
	 * @param  array $query
	 * @param  array $cols
	 * @return $this
	 */
	public function whereSearch(array $query = [], array $cols = []) {

		if (empty($query)) {
			return $this;
		}

		foreach ($query as $term) {

			$this->model = $this->model->where(function($query) use ($term, $cols) {

				foreach ($cols as $col) {

					$query = $query->orWhere($col, 'LIKE', '%' . $term . '%');

				}
			});

		}

		return $this;
	}

	/**
	 * Generic wherein filter on query
	 * 
	 * @param  string $col
	 * @param  array $values
	 * @return $this
	 */
	public function where($col, array $values) {

		if (empty($values)) {
			return $this;
		}

		$this->model = $this->model->whereIn($col, $values);

		return $this;

	}

	/**
	 * __get method to mainly ensure $this->model is set in child class.
	 * 
	 * @param  string $key
	 * @return mixed
	 */
	public function __get($key) {

        if ( !isset(static::$$key)) {
            throw new Exception('Child class '.get_called_class().' failed to define static $'.$key.' property');
        }

        return static::$$key;
    }

}
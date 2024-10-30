<?php
namespace Context360\Model\Registration\API\Registration;


class CategoriesCollection
{
	protected $categories;

	/**
	 * CategoriesCollection constructor.
	 *
	 * @param array $categoriesResponse - array of items [ 'id' => int, 'name' => string ]
	 */
	public function __construct( $categoriesResponse )
	{
		$this->categories = array_map(function ($item) {
			return new Category($item['id'],$item['name']);
		}, $categoriesResponse);
	}

	/**
	 * @return Category[]
	 */
	public function getCategories()
	{
		return $this->categories;
	}
}
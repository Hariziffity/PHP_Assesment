<?php
namespace Home;

$products_file = 'products.json';
$products_data = file_get_contents($products_file);
$products_array = json_decode($products_data, true);
define('PRODUCTS', $products_array);

class ProductsProcessing
{
    public $search;
    public $category;
    public $page;
    public $show;
    public $limit;
    public $offset;
    public $total_items;
    public $total_pages;
    public $specific_products;
    public $final_specific_products;
    public $final_specific_products_count;
    public $final_limited_specific_products;
    public $result;
    public function getSpecificProducts($category)
    {
        return array_filter(
            PRODUCTS,
            function ($obj) use ($category) {
                if ($category === 'all') {
                    return $obj;
                }
                return ($obj['category'] == $category);
            }
        );
    }
    public function getFinalSpecificProducts($products, $search)
    {
        return array_filter(
            $products,
            function ($obj) use ($search) {
                if ($search === '') {
                    return $obj;
                }
                return stristr($obj['name'], $search);
            }
        );
    }
    public function getLimitedFinalSpecificProducts($products, $offset, $limit)
    {
        return array_splice($products, $offset, $limit);
    }

    public function getFinalProducts($search, $category, $page, $show)
    {
        $this->search = $search;
        $this->category = $category;
        $this->page = $page;
        $this->show = $show;
        $this->limit = 5;
        $this->offset = ($this->page - 1) * $this->limit;
        $this->total_items = count(PRODUCTS);
        $this->total_pages = ceil($this->total_items / $this->limit);
        $this->specific_products = $this->getSpecificProducts($this->category);
        $this->final_specific_products = $this->getFinalSpecificProducts($this->specific_products, $this->search);
        $this->final_specific_products_count = count($this->final_specific_products);
        $this->final_limited_specific_products = $this->getLimitedFinalSpecificProducts(
            $this->final_specific_products,
            $this->offset,
            $this->limit
        );
        $this->result = array("final_products"=>$this->final_limited_specific_products,
        "count"=>$this->final_specific_products_count);
        return $this->result;
    }
}

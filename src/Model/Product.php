<?php

/**
 * Created by Michael A. Sivolobov
 * Date: 06/08/14
 */
class Product
{
    /**
     * @var integer Идентификатор товара в партнерской системе
     */
    protected $id;
    /**
     * @var string Наименование товара
     */
    protected $title;
    /**
     * @var integer Рекомендуемая цена товара в копейках
     */
    protected $price;
    /**
     * @var integer Количество товара на складе
     */
    protected $amount;
    /**
     * @var array<int> Массив идентификаторов категорий товара
     */
    protected $category_id;
    /**
     * @var integer Идентификатор Брэнда товара
     */
    protected $brand_id;
    /**
     * @var string Описание товара
     */
    protected $description;
    /**
     * @var array<string> Массив ссылок на фотографии товара
     */
    protected $photo;
    /**
     * @var string Артикул товара
     */
    protected $article;
    /**
     * @var array<string> Массив значений дополнительных параметров (например, габариты)
     */
    protected $options;

    public static function fromArray($array) {
        $result = new self();
        foreach ($array as $key => $value) {
            $result->$key = $value;
        }
        return $result;
    }

    /**
     * @return int Количество товара на складе
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return int Идентификатор Брэнда товара
     */
    public function getBrandId()
    {
        return $this->brand_id;
    }

    /**
     * @return array Массив идентификаторов категорий товара
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @return string Описание товара
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int Идентификатор товара в партнерской системе
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array Массив ссылок на фотографии товара
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @return int Рекомендуемая цена товара в копейках
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string Наименование товара
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string Артикул
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @return array Массив значений дополнительных параметров (например, габариты)
     */
    public function getOptions()
    {
        return $this->options;
    }
}
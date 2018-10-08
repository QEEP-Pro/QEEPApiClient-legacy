<?php
/**
 * Created by Michael A. Sivolobov
 * Date: 19.06.13
 */

class APIController
{
    const PAYMENT_TYPE_YANDEX = 'yandex_money';

    const API_ROUTE_PREFIX = '/api/';

    const API_IMAGE_PREFIX = 'images.';

    private $clientId;

    private $clientSecret;

    private $crmUrl;

    private $salesChannel;

    private $imageUrl;

    public function __construct() {
        $config = Config::getInstance();
        $this->clientId     = $config->get('clientId');
        $this->clientSecret = $config->get('clientSecret');
        $this->crmUrl       = $config->get('crmUrl');
        $this->salesChannel = $config->get('salesChannel');
        $this->imageUrl     = $config->get('imageUrl');
    }

    /**
     * Основной метод класса, который отправляет запрос методом GET на URL, указанный в конфиге.
     *
     * @param array $params Массив параметров, которые будут переданы на сервер партнерской системы
     * @throws Exception В случае ошибок в процессе подготовки данных будет выброшена соответствующая ошибка
     * @return string Метод возвращает ответ сервера партнерской системы
     */
    private function sendRequest($controller, $method, $params, $format = 'json')
    {
        if (!array($params)) {
            throw new Exception('Параметры должны быть массивом');
        }
        if (!$this->clientId || !$this->clientSecret) {
            throw new Exception('Не заданы параметры clientId и clientSecret в конфигурационном файле');
        }

        $access_token = $this->generateToken($params, $this->clientId, $this->clientSecret);

        $params['client_id'] = $this->clientId;
        $params['access_token'] = $access_token;
        $query = http_build_query($params);
        $response = file_get_contents($this->crmUrl . '/' . $controller . '.' . $format . '/' . $method . '?' . $query);
        return $response;
    }

    public function generateToken($params) {
        $at = $this->clientId . $this->clientSecret;
        ksort($params);
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                unset($params[$key]);
            } else {
                $at .= $value;
            }
        }
        return md5($at);
    }

    /**
     * Метод для получения полного каталога товаров по заданным брендам и категориям.
     * Если не задан ни один бренд/категория то каталог выводится по всем брендам/категориям
     *
     * @param array $brands Массив идентификаторов брендов
     * @param array $categories Массив идентификаторов категорий
     * @return array
     */
    public function getCatalog($brands = array(), $categories = array())
    {
        $params = array(
            'brand_id' => $brands,
            'category_id' => $categories,
        );
        $json = $this->sendRequest('catalog', 'getAll', $params);
        $collection = json_decode($json, true);
        $products = array();
        foreach ($collection as $element) {
            $products[] = Product::fromArray($element);
        }
        return $products;
    }

    /**
     * Метод для получения обновлений каталога.
     * Получаются только те товары, который как-то менялись с последней синхронизации
     *
     * @param array $brands Массив идентификаторов брендов
     * @param array $categories Массив идентификаторов категорий
     * @return array
     */
    public function getCatalogUpdates($brands = array(), $categories = array())
    {
        $params = array(
            'brand_id' => $brands,
            'category_id' => $categories,
        );
        $json = $this->sendRequest('catalog', 'getUpdates', $params);
        $collection = json_decode($json, true);
        $products = array();
        foreach ($collection as $element) {
            $products[] = Product::fromArray($element);
        }
        return $products;
    }

    /**
     * Метод для создания заказа
     *
     * @param   int       $order_id       Идентификатор заказа в ИС партнера
     * @param   string    $buyer_phone    Телефон покупателя
     * @param   array     $products       Массив товаров, каждый товар - это ассоциативный массив вида:
     * <code>
     * $product = array(
     *  'code' => 1, // Идентификатор товара в партнерской системе
     *  'price' => 50000 // Цена товара на сайте партнера (в копейках)
     *  'amount' => 2 // Количество продаваемого товара
     * );
     * </code>
     * @param   int         $delivery_price Цена доставки, заданная партнером (в копейках)
     * @param   string      $website        Домен, с которого пришел заказ
     * @param   string|null $buyer_name     Имя покупателя
     * @param   string|null $buyer_email    E-mail покупателя
     * @param   string|null $buyer_address  Адрес покупателя
     * @param   string|null $comment        Комментарий к заказу
     * @param   string|null $delivery_type  Код способа доставки (код берется из справочника)
     * @param   int|null    $delivery_time  Предполагаемое время доставки заказа в unixtime
     * @param   string|null $payment_method Код метода оплаты (код берется из справочника)
     * @param   int|null    $discount_price Цена скидки, заданная партнером (в копейках)
     *
     * @see     getDeliveryTypes()
     * @see     getPaymentMethods()
     *
     * @return bool Метод возвращает <b>true</b> в случае успешного создания заказа на стороне партнерской системы и
     *              <b>false</b> - в случае неудачи
     */
    public function createOrder(
        $order_id,
        $buyer_phone,
        $products,
        $delivery_price,
        $website,
        $buyer_name = null,
        $created_time = null,
        $buyer_email = null,
        $buyer_address = null,
        $comment = null,
        $delivery_type = null,
        $payment_method = null,
        $discount_price = null
    )
    {
        $params = array(
            'order_id' => $order_id,
            'buyer_name' => $buyer_name,
            'buyer_phone' => $buyer_phone,
            'buyer_email' => $buyer_email,
            'buyer_address' => $buyer_address,
            'comment' => $comment,
            'products' => $products,
            'delivery_price' => $delivery_price,
            'delivery_type' => $delivery_type,
            'created_time' => $created_time,
            'payment_method' => $payment_method,
            'discount_price' => $discount_price,
            'website' => $website,
        );
        $json = $this->sendRequest('orders', 'createOrder', $params);
        $response = json_decode($json, true);
        // return $response;
        if ($response['status'] == 'success') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Получить статус заказа
     *
     * @param int $order_id Номер заказа в ИС партнера
     *
     * @return OrderStatus Метод возвращает объект Статус заказа
     */
    public function getOrderStatus($order_id)
    {
        $params['order_id'] = $order_id;
        $json = $this->sendRequest('orders', 'getStatus', $params);
        $response = json_decode($json, true);
        $order_status = OrderStatus::fromArray($response);
        return $order_status;
    }

    /**
     * Отменить заказ
     *
     * @param int $order_id Номер заказа в ИС партнера
     *
     * @return bool Метод возвращает <b>true</b> в случае успешной отмены заказа и <b>false</b> - в обратном
     */
    public function cancelOrder($order_id)
    {
        $params['order_id'] = $order_id;
        $json = $this->sendRequest('orders', 'cancelOrder', $params);
        $response = json_decode($json, true);
        if ($response->status == 'success') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Получить значения справочника
     *
     * @param string $method Название метода для получения справочника
     * @return array Метод возвращает ассоциативный массив с элементами справочника.
     *               Ключом каждого элемента массива является код, значением - соответствующее значение справочника
     */
    private function getDictionary($method)
    {
        $json = $this->sendRequest('list', $method, array());
        $response = json_decode($json, true);
        $result = array();
        foreach ($response as $row) {
            $result[$row['code']] = $row['value'];
        }
        return $result;
    }

    /**
     * Получить справочник кодов категорий
     *
     * @return array Метод возвращает ассоциативный массив с элементами справочника.
     *               Ключом каждого элемента массива является код, значением - соответствующее значение справочника
     */
    public function getCategories()
    {
        return $this->getDictionary('getCategories');
    }

    /**
     * Получить справочник брендов
     *
     * @return array Метод возвращает ассоциативный массив с элементами справочника.
     *               Ключом каждого элемента массива является код, значением - соответствующее значение справочника
     */
    public function getBrands()
    {
        return $this->getDictionary('getBrands');
    }

    /**
     * Получить справочник статусов заказа
     *
     * @return array Метод возвращает ассоциативный массив с элементами справочника.
     *               Ключом каждого элемента массива является код, значением - соответствующее значение справочника
     */
    public function getStatuses()
    {
        return $this->getDictionary('getStatuses');
    }

    /**
     * Получить справочник способов доставки
     *
     * @return array Метод возвращает ассоциативный массив с элементами справочника.
     *               Ключом каждого элемента массива является код, значением - соответствующее значение справочника
     */
    public function getDeliveryTypes()
    {
        return $this->getDictionary('getDeliveryTypes');
    }

    /**
     * Получить справочник методов оплаты
     *
     * @return array Метод возвращает ассоциативный массив с элементами справочника.
     *               Ключом каждого элемента массива является код, значением - соответствующее значение справочника
     */
    public function getPaymentMethods()
    {
        return $this->getDictionary('getPaymentMethods');
    }

    /**
     * Получить параметры для создания платежа по заданному заказу.
     * Чтобы передать заказ в платежную систему необходимо по полученному URL
     * отправить полученные параметры в POST или GET запросе
     *
     * @param $order_id Идентификатор заказа в ИС партнера
     * @return Payment Объект-представление параметров заказа в платежную систему
     */
    public function getPaymentParameters($order_id)
    {
        $json = $this->sendRequest('payments', 'getParameters', array('order_id' => $order_id));
        $response = json_decode($json, true);
        return Payment::fromArray($response);
    }
}

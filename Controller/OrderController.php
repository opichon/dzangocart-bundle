<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use DateTime;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\OrderFilterType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class OrderController
{
    protected $dzangocart;
    protected $dzangocart_config;
    protected $form_factory;

    public function __construct($dzangocart, $dzangocart_config, FormFactory $form_factory)
    {
        $this->dzangocart = $dzangocart;
        $this->dzangocart_config = $dzangocart_config;
        $this->form_factory = $form_factory;
    }

    /**
     * @Template("DzangocartBundle:Order:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $form = $this->form_factory->create(
            new OrderFilterType(),
            array(
                'date_from' => (new DateTime())->modify('first day of this month'),
                'date_to' => new DateTime()
            )
        );

        return array(
            'form' => $form->createView(),
            'config' => $this->dzangocart_config
        );
    }

    /**
     * @Template("DzangocartBundle:Order:list.json.twig")
     */
    public function listAction(Request $request)
    {
        $params = $this->getFilters($request->query);
        $params['sort_by'] = $this->getSortOrder($request->query);

        $data = $this->dzangocart
            ->getOrders($params);

        $data['datetime_format'] = $this->dzangocart_config['datetime_format'];

        return $data;
    }

    /**
     * @Route("/{id}", name="dzangocart_order", requirements={"id": "\d+"})
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $params = array(
            'id' => $id
        );

        $order = $this->dzangocart
            ->getOrder($params);

        return array(
            'order' => $order,
            'config' => $this->dzangocart_config,
            'data' => print_r($order, true)
        );
    }

    protected function getFilters(ParameterBag $query)
    {
        $filters = array();

        $filters['limit'] = $query->get('length');
        $filters['offset'] = $query->get('start');

        $_filters = $query->get('filters');

        foreach ($date_fields = array('date_from', 'date_to') as $field) {
            $value = $_filters[$field];

            if (!empty($value)) {
                $filters[$field] = $value;
            }
        }

        $filters['test'] = @$_filters['test'] ? true : false;

        if (array_key_exists('customer', $_filters)) {
            $filters['customer'] = $_filters['customer'];
        }

        return $filters;
    }

    protected function getSortOrder(ParameterBag $query)
    {
        $sort_by = array();

        $columns = $this->getSortColumns();

        $n = $query->get('sortingCols');

        for ($i = 0; $i < $n; $i++) {
            $index = $query->get('sortCol_' . $i);

            if (array_key_exists($index, $columns)) {

                $column = $columns[$index];

                if (!is_array($column)) {
                    $column = array($column);
                }

                foreach ($column as $c) {
                    $sort_by[] = $c;
                    $sort_by[] = $query->get('sortDir_' . $i, 'asc');
                }
            }
        }

        if (empty($sort_by)) {
            $sort_by = $this->getDefaultSortOrder();
        }

        return $sort_by;
    }

    protected function getDefaultSortOrder()
    {
        return array('cart.DATE', 'asc');
    }

    protected function getSortColumns()
    {
        return array(
            1 => 'cart.DATE',
            2 => 'cart.ID',
            3 => array('user_profile.SURNAME', 'user_profile.GIVEN_NAMES'),
            4 => 'cart.CURRENCY_ID',
            5 => 'cart.AMOUNT_EXCL',
            6 => 'cart.TAX_AMOUNT',
            7 => 'cart.AMOUNT_INCL',
            9 => 'cart.AFFILIATE_ID',
            10 => 'cart.TEST'
        );
    }
}

<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use \DateTime;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\OrderFilterType;

/**
 * @Route("/")
 * @Template
 */
class OrderController extends Controller
{
    /**
     * @Route("/", name="dzangocart_orders")
     * @Template("DzangocartBundle:Order:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $dzangocart_config = $this->container->getParameter('dzangocart.config');

        if ($request->isXmlHttpRequest() || $request->getRequestFormat() == 'json') {

            $params = $this->getFilters($request->query);
            $params['sort_by'] = $this->getSortOrder($request->query);

            $data = $this->get('dzangocart')
                ->getOrders($params);

            $data['datetime_format'] = $dzangocart_config['datetime_format'];

            $view = $this->renderView('DzangocartBundle:Order:index.json.twig', $data);

            return new Response($view, 200, array('Content-Type' => 'application/json'));
        } else {
            $form = $this->createForm(
                new OrderFilterType(),
                array(
                    'date_from' => (new DateTime())->modify('first day of this month'),
                    'date_to' => new DateTime()
                )
            );

            return array(
                'form' => $form->createView(),
                'config' => $dzangocart_config
            );
        }
    }

    /**
     * @Route("/{id}", name="dzangocart_order")
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $dzangocart_config = $this->container->getParameter('dzangocart.config');

        $params = array(
            'id' => $id
        );

        $order = $this->get('dzangocart')
            ->getOrder($params);

        return array(
            'order' => $order,
            'config' => $dzangocart_config,
            'data' => print_r($order, true)
        );
    }

    protected function getFilters(ParameterBag $query)
    {
        $filters = array();

        $filters['search'] = $query->get('sSearch');
        $filters['limit'] = $query->get('iDisplayLength');
        $filters['offset'] = $query->get('iDisplayStart');

        $_filters = $query->get('filters');

        foreach ($date_fields = array('date_from', 'date_to') as $field) {
            $value = $_filters[$field];
            if (!empty($value)) {
                $filters[$field] = $value;
            }
        }

        $filters['test'] = @$_filters['test'] ? true : false;
/*
        if (array_key_exists('customer', $_filters)) {
            $filters['customer'] = $_filters['customer'];
        }
*/

        return $filters;
    }

    protected function getSortOrder(ParameterBag $query)
    {
        $sort_by = array();

        $columns = $this->getSortColumns();

        $n = $query->get('iSortingCols');

        for ($i = 0; $i < $n; $i++) {
            $index = $query->get('iSortCol_' . $i);

            if (array_key_exists($index, $columns)) {

                $column = $columns[$index];

                if (!is_array($column)) {
                    $column = array($column);
                }

                foreach ($column as $c) {
                    $sort_by[] = $c;
                    $sort_by[] = $query->get('sSortDir_' . $i, 'asc');
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

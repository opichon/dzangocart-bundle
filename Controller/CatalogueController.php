<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CatalogueController extends Controller
{   
    /**
     * @Route("/", name="dzangocart_catalogue")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $catalogue = $this->get('dzangocart')
            ->getCatalogue();

        return array(
            'catalogue' => $catalogue
        );
    }
}

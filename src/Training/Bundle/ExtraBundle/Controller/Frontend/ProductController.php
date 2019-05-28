<?php

namespace Training\Bundle\ExtraBundle\Controller\Frontend;

use Oro\Bundle\LayoutBundle\Annotation\Layout;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductController extends Controller
{
    /**
     * @Route("/", name="training_extra_storefront_product_index")
     * @AclAncestor("oro_product_frontend_view")
     * @Layout()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route(
     *      "/name-by-sku/{id}",
     *      name="training_extra_storefront_ajax_product_name_by_sku",
     *      requirements={"id"="\d+"},
     *      options={"expose"=true}
     * )
     * @Method("GET")
     * @AclAncestor("oro_product_frontend_view")
     *
     * @return JsonResponse
     */
    public function productNameBySkuAction(Product $product)
    {
        return new JsonResponse([
            'id'   => $product->getId(),
            'name' => (string) $product->getName()
        ]);
    }
}

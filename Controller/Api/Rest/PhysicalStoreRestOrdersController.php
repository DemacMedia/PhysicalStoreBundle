<?php
namespace DemacMedia\Bundle\PhysicalStoreBundle\Controller\Api\Rest;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;
use Oro\Bundle\CommentBundle\Entity\Manager\CommentApiManager;
use DemacMedia\Bundle\PhysicalStoreBundle\Entity\OroPhysicalStoreOrders;



/**
 * @NamePrefix("demacmedia_api_")
 */
class PhysicalStoreRestOrdersController extends RestController implements ClassResourceInterface
{
    /**
     * REST GET list of PhysicalStore Orders
     *
    $response = $oroClient->get('api/rest/latest/physicalstore/orders.json', [
        'query' => [
            'page' => 1,
            'limit' => 5,
        ]
    ]);
     *
     * @QueryParam(
     *      name="page",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Page number, starting from 1. Defaults to 1."
     * )
     * @QueryParam(
     *      name="limit",
     *      requirements="\d+", nullable=true,
     *      description="Number of items per page. defaults to 10."
     * )
     * @ApiDoc(
     *      resource=true,
     *      description="Get all PhysicalStore Orders",
     * )
     * @AclAncestor("demacmedia_physicalstore_orders")
     * @return Response
     */
    public function cgetAction()
    {
        $page = (int) $this->getRequest()->get('page', 1);
        $limit = (int) $this->getRequest()->get('limit', self::ITEMS_PER_PAGE);

        return $this->handleGetListRequest($page, $limit);
    }


    /**
     * Get specific account data
     *
        // Get a specific account using a id. In this example id=1
        $physicalOrdersResponse = $oroClient->get('api/rest/latest/physicalstore/orders/1.json', []);
     * @param int $id Account id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @ApiDoc(
     * description="Get a specific Physical Store account info",
     * resource=true,
     * requirements={
     * {"name"="id", "dataType"="integer"},
     * }
     * )
     * @AclAncestor("demacmedia_physicalstore_orders_view")
     */
    public function getAction($id)
    {
        return $this->handleGetRequest($id);
    }


    /**
     * Create new PhysicalStore Order
     *
        // Example creating a new Order.
        $response = $oroClient->post('api/rest/latest/physicalstore/orders.json', [
            'body' => [
                'custno'            => 'AAAAA', // Required
                'company'           => 'Acme Company',
                'contactname'       => 'AAAAA AAAAA', // Required
                'title'             => 'Dr.',
                'address1'          => 'Street Foo, Bar, 1',
                'address2'          => 'Street Foo2, Bar2, 2',
                'city'              => 'My City', // Required
                'addrstate'         => 'ON',
                'zip'               => 'M9MM9M',
                'country'           => 'Canada',
                'phone'             => '9999999', // Required
                'phone2'            => '88888888',
                'source'            => 'Radio',
                'type'              => 'Client Type X',
                'email'             => 'aaaaa@example.org',
                'custmemo'          => 'Comment about anything',
                'url'               => 'http://example.org',
                'owner'             => '17',
            ]
        ]);
     *
     * @ApiDoc(
     * description="Create new Physical Store Account.",
     * resource=true
     * )
     * @AclAncestor("demacmedia_physicalstore_orders_create")
     */
    public function postAction()
    {
        return $this->handleCreateRequest();
    }



    /**
     * Update Physical Store account
     *
        $request = $oroClient->put('api/rest/latest/physicalstore/orders/6.json', [
            'body' => [
                'custno'            => 'AAAAA', // Required
                'company'           => 'Acme Company',
                'contact'           => 'AAAAA AAAAA', // Required
                'title'             => 'Dr.',
                'address1'          => 'Street Foo, Bar, 1',
                'address2'          => 'Street Foo2, Bar2, 2',
                'city'              => 'Toronto', // Required
                'addrstate'         => 'ON',
                'zip'               => 'M9MM9M',
                'country'           => 'Canada',
                'phone'             => '9999999', // Required
                'phone2'            => '88888888',
                'source'            => 'Radio',
                'type'              => 'Client Type X',
                'email'             => 'aaaaa@example.org',
                'custmemo'          => 'Comment about anything',
                'url'               => 'http://example.org',
                'owner'             => '17',
            ]
        ]);
     *
     * @param int $id Comment item id
     *
     * @ApiDoc(
     * description="Update Physical Store account",
     * resource=true
     * )
     * @AclAncestor("demacmedia_physicalstore_orders_update")
     *
     * @return Response
     */
    public function putAction($id)
    {
        return $this->handleUpdateRequest($id);
    }


    /**
     * Delete Physical Store account
     *
        // Example deleting Account with id: 1
        $response = $oroClient->delete('api/rest/latest/physicalstore/orders/1.json');
     *
     * @param int $id comment id
     *
     * @ApiDoc(
     *      description="Delete PhysicalStore account",
     *      resource=true
     * )
     * @Acl(
     * id="demacmedia_physicalstore_orders_delete",
     *      type="entity",
     *      permission="DELETE",
     *      class="DemacMediaPhysicalStoreBundle:OroPhysicalStoreOrders"
     * )
     * @return Response
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }



    /**
     * Get entity Manager
     *
     * @return ApiEntityManager
     */
    public function getManager()
    {
        return $this->get('demacmedia_physicalstore_orders.manager.api');
    }


    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->get('demacmedia_physicalstore_orders.form.orders.api');
    }


    /**
     * @return ApiFormHandler
     */
    public function getFormHandler()
    {
        return $this->get('demacmedia_physicalstore_orders.form.handler.orders_api');
    }
}


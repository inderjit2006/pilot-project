<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Item;
use App\Entity\ItemList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ItemRepository;
use Doctrine\Common\Persistence\ObjectManager;

class ItemsController extends AbstractController
{

    /**
     * @Route("user/item_save", name="item_save", methods={"POST"})
     */
    public function save( Request $request, ItemRepository $repository, ObjectManager $manager )
    {       
        // Only include it if the function is reserved for ajax calls only.
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'status' => 'Error',
                'message' => 'Error'),
            400);
        }

        if(isset($request->request))
        {
            $postData =  $request->get('item');   

            $entityManager = $this->getDoctrine()->getManager();
            $db_record = $entityManager->getRepository(Item::class)->findOneBy(array('list' => $postData['list_id'], 'item_name' => $postData['item_name']));
                
            if ($db_record === null)
            {
                $item = new Item();
                $item->setItemName($postData['item_name']);
                $item->setColorCode($postData['color_code']);
                $item->setPlacement($postData['placement']);

                $item_list = $entityManager->getRepository(ItemList::class)->find( $postData['list_id'] );
                $item->setList($item_list);

                $entityManager->persist($item);
                $entityManager->flush();

                return new JsonResponse(array(
                    'status' => 'Success',
                    'message' => 'List item saved in database successfully'),
                200);
            }
            else
            {                
                return new JsonResponse(array(
                    'status' => 'Error',
                    'message' => 'Item name already exists in database'),
                200);
            }
        }

        // If we reach this point, it means that something went wrong
        return new JsonResponse(array(
            'status' => 'Error',
            'message' => 'Error'),
        400);
    }


     /**
     * @Route("user/item_delete/{id}", name="item_delete", methods={"GET"}) 
     */
    public function delete( $id )
    {
    	$repository = $this->getDoctrine()->getRepository(Item::class);
    	$item = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
		$em->remove($item);
		 
		$em->flush(); 

		$this->addFlash('success', "Item deleted successfully");
		return $this->redirectToRoute('user_listing');

    }
}

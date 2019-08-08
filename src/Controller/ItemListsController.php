<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\ItemList;
use App\Entity\Item;
use App\Form\ItemListType;
use App\Form\ItemType;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ItemListRepository;
use Doctrine\Common\Persistence\ObjectManager;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;


class ItemListsController extends AbstractController
{
     /**
     * @Route("user/lists", name="user_listing")
     */
    public function index( UserInterface $user, Request $request, PaginatorInterface $paginator )
    {
    	ini_set('memory_limit', -1);

    	$user_id = $user->getId();
    	$limit = $this->getParameter('knp_paginator.page_range');
    	
    	$item_list = new ItemList();
    	$form = $this->createForm(ItemListType::class, $item_list);

        $item = new Item();
        $form2 = $this->createForm(ItemType::class, $item);

     	// Retrieve the entity manager of Doctrine
        $entityManager = $this->getDoctrine()->getManager();
        
        // Get some repository of data, in our case we have an Appointments entity
        $listsRepository = $entityManager->getRepository(ItemList::class);
                
        // Find all the data on the Appointments table, filter your query as you need
        $listQuery = $listsRepository->createQueryBuilder('l')
            ->where('l.user= :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery();
        
        // Paginate the results of the query
        $my_list = $paginator->paginate(
            // Doctrine Query, not results
            $listQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            $limit
        );        

        return $this->render('item_lists/index.html.twig', [
           'form' => $form->createView(),
           'my_list' => $my_list,
           'form2' => $form2->createView()
        ]);
    }

     /**
     * @Route("user/list_save", name="list_save", methods={"POST"}) 
     */
    public function save( UserInterface $user, Request $request, ItemListRepository $repository, ObjectManager $manager )
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
    		$postData =  $request->get('item_list'); 
    		$user_id = $user->getId();

            // Check if record exists or not in db with same title and of same user
			$db_record = $repository->findOneByUserIdAndTitle($user_id,$postData['title']);
			    
            if ($db_record === null)
            { 
            	$item_list = new ItemList();
            	$item_list->setTitle($postData['title']);
            	$item_list->setUser($user);

            	$manager = $this->getDoctrine()->getManager();
                $manager->persist($item_list);

                $manager->flush();

            	return new JsonResponse(array(
            		'status' => 'Success',
            		'message' => 'List title saved in database successfully'),
            	200);
            }
            else
            {            	 
            	return new JsonResponse(array(
            		'status' => 'Error',
            		'message' => 'Title already exists in database'),
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
     * @Route("user/list_delete/{id}", name="list_delete", methods={"GET"}) 
     */
    public function delete( $id )
    {
    	$repository = $this->getDoctrine()->getRepository(ItemList::class);
    	$item_list = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
		$em->remove($item_list);
		 
		$em->flush(); 

		$this->addFlash('success', "Item's list deleted successfully");
		return $this->redirectToRoute('user_listing');

    }
}
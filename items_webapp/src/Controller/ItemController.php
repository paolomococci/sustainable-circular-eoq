<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * ItemController
 *
 * Manages CRUD operations on the entity {@see App\Entity\Item}.
 */
#[Route('/item')]
final class ItemController extends AbstractController
{
    /**
     * Show a list of all items.
     *
     * @param ItemRepository $itemRepository injected automatically by Symfony
     * Symfony injects this repository automatically via autowiring.
     * It is registered as a service by DoctrineBundle.
     *
     * @return Response
     */
    #[Route(name: 'app_item_index', methods: ['GET'])]
    public function index(ItemRepository $itemRepository): Response
    {
        // Retrieve every Item from the database.
        $items = $itemRepository->findAll();
        // Show the value of the variable in the debug bar without interrupting the execution flow.
        dump($items);

        // Render the Twig template and passing the $items array as a local variable.
        return $this->render('item/index.html.twig', [
            'items' => $items,
        ]);
    }

    /**
     * Create a new Item.
     *
     * @param Request $request
     * Current HTTP request.
     *
     * @param EntityManagerInterface $entityManager
     * Doctrine's persistence manager.
     *
     * @return Response
     */
    #[Route('/new', name: 'app_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // CSRF not required.
        // Instantiate an empty Item object that will be bound to the form.
        $item = new Item();
        // Create a symfony form based on ItemType class and bind it to $item.
        $form = $this->createForm(ItemType::class, $item);
        // Handle request data, populate the form and sets its.
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Tell Doctrine to persist the new entry.
            $entityManager->persist($item);
            // Write all pending changes to DB.
            $entityManager->flush();

            // After a successful POST, redirect to the index page.
            return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
        }

        // View the form if the request is a GET or if the submission was unsuccessful, pre-filled with the valid data.
        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    /**
     * Display a single Item.
     *
     * @param Item $item
     * Automatically fetched by Symfony on the {id} route placeholder.
     *
     * @return Response
     */
    #[Route('/{id}', name: 'app_item_show', methods: ['GET'])]
    public function show(Item $item): Response
    {
        // Render the Twig template and passing the $item as a local variable.
        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }

    /**
     * Edit an existing Item.
     *
     * @param Request $request
     * Current HTTP request.
     *
     * @param Item $item
     * Entry loaded by {id}.
     *
     * @param EntityManagerInterface $entityManager
     * Doctrine's persistence manager.
     *
     * @return Response
     */
    #[Route('/{id}/edit', name: 'app_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        // CSRF not required.
        // Build a form pre-filled with the current values of $item.
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Now there is no need to call persist() method, Doctrine already tracks this entity.
            $entityManager->flush();

            // After a successful POST, redirect to the index page.
            return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
        }

        // View the form if the request is a GET or if the submission was unsuccessful, pre-filled with the valid data.
        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    /**
     * Delete an Item.
     *
     * @param Request $request
     * @param Item $item
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    #[Route('/{id}', name: 'app_item_delete', methods: ['POST'])]
    public function delete(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        // CSRF protection, the token is usually sent as a hidden field.
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->getPayload()->getString('_token'))) {
            // Tell Doctrine to remove this entity from the database.
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
    }
}

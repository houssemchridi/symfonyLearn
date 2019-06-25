<?php
/**
 * Created by IntelliJ IDEA.
 * User: hchridi
 * Date: 09/05/2019
 * Time: 19:36
 */

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\This;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * AdminPropertyController constructor.
     * @param PropertyRepository $repository
     * @param ObjectManager $em
     */
    public function __construct(PropertyRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin",name="admin.property.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index()
    {

        $properties = $this->repository->findAll();

        return $this->render('admin/property/index.html.twig', compact('properties'));
    }

    /**
     * @Route("/admin/property/create",name="admin.property.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // here we need to persist the data cause we created a new property and it 's
            // not tracked by the form handler
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success','Bien crée avec succées ');
            return $this->redirectToRoute("admin.property.index");
        }
        return $this->render("admin/property/new.html.twig",
            [
            'property'=>$property,
            'form'=>$form->createView()
            ]);
    }

    /**
     * @Route("/admin/property/{id}",name="admin.property.edit",methods="GET|POST")
     * @param Property $property
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Property $property, Request $request)
    {

        $form = $this->createForm(PropertyType::class, $property);

        //le handlerRequest permet de gerer automatiquement  (gerer les setters selon le nouveau formulaire)

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success','Bien modifié avec succées ');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/edit.html.twig',
            [
                'property' => $property,
                'form' => $form->createView()
            ]);
    }

    /**
     * @param Property $property
     * @Route("admin/property/{id}",name="admin.property.delete",methods="DELETE")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Property $property,Request $request){
        if ($this->isCsrfTokenValid('delete'.$property->getId(),$request->get('token'))) {
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success','Bien supprimé avec succées ');

        }
        return $this->redirectToRoute('admin.property.index');

    }
}
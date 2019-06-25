<?php
/**
 * Created by IntelliJ IDEA.
 * User: hchridi
 * Date: 22/02/2019
 * Time: 14:41
 */

namespace App\Controller;


use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    private $repository;
    public function __construct(PropertyRepository $repository , ObjectManager $em)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/biens",name="property.index",)
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request) : Response
    {
//        $property = new Property();
//        $property->setTitle('Mon premier bien')
//                 ->setPrice(200000)
//                 ->setRooms(4)
//                 ->setDescription('Une petite description')
//                 ->setSurface(60)
//                 ->setFloor(4)
//                 ->setHeat(1)
//                 ->setCity('Tunis')
//                 ->setAddress('ariana')
//                 ->setPostalCode('7000');
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($property);
//        $em->flush();
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class,$search);
        $form->handleRequest($request);
        $properties = $paginator->paginate($this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page',1)
        ,12);


        return $this->render('property/index.html.twig',[
            'properties'=>$properties,
            'form'      =>$form->createView()
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}",name="property.show",requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show($slug , $id) : Response{
        $property = $this->repository->find($id);
        if ($property->getSlug()!==$slug){
          return  $this->redirectToRoute('property.show',[
                'id' =>$property->getId(),
                'slug'=>$property->getSlug()
            ],301);
        }
        return $this->render("property/show.html.twig",[
            'property'=>$property,
            'current_menu'=>'properties'
        ]);
    }

}
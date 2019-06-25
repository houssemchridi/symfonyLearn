<?php
/**
 * Created by IntelliJ IDEA.
 * User: hchridi
 * Date: 22/02/2019
 * Time: 12:36
 */

namespace App\Controller;


use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;

    /**
     * HomeController constructor.
     * @param PropertyRepository $repository
     */
    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;

    }

    /**
     * @return Response
     */
    public function index() : Response{

        $properties = $this->repository->findLatest();
        return $this->render('pages/home.html.twig',[
            'current_menu'=>'properties',
            'properties'  =>$properties
        ]);
    }

}
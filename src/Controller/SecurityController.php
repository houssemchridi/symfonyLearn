<?php
/**
 * Created by IntelliJ IDEA.
 * User: hchridi
 * Date: 12/05/2019
 * Time: 18:32
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * SecurityController constructor.
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $
     */
    public function __construct(ObjectManager $manager, UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->manager = $manager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/login" ,name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
        /*
         * The symfony system is very different from anythink i ever saw in PHP
         * we have just to much the route name with the .yaml file config and he does all the rest
         * by it self
         */
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUserName = $authenticationUtils->getLastUsername();

    return $this->render("security/login.html.twig",[
        'lastUserName'=>$lastUserName,
        'error'=> $error
    ]);
    }

    /**
     * @Route("/register",name="register")
     * @param Request $request
     */
    public function register(Request $request){
        $user = new User();
        $form = $this->createForm(UserType::class,$user);
        /*
         * IMPORTANT : Handle request is crucial for getting the data from the request and map them with the model
         */
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $user->setPassword($this->passwordEncoder->encodePassword($user,$form->get('password')->getData()));
            $this->manager->persist($user);
            $this->manager->flush();

            return $this->redirectToRoute("admin.property.index");
        }
        return $this->render('security/register.html.twig',[
            'form' => $form->createView(),
            'user' => $user
        ]);

    }
}
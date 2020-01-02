<?php


namespace App\Controller;


use App\Entity\MicroPost;
use App\Forms\MicroPostType;
use App\Repository\MicroPostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/micro-post")
 */
class MicroPostController extends AbstractController
{


    private $microPostRepository;

    private $formFactory;

    private $entityManager;

    private $flashBag;


    public function __construct( MicroPostRepository $microPostRepository, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager, FlashBagInterface $flashBag)
    {
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/",name="micro_post_index")
     */
    public function index()
    {
        return $this->render('micro-post/index.html.twig', ['posts' => $this->microPostRepository->findAll()]);
    }

    /**
     * @Route("/edit/{id}",name="micro_post_edit")
     */
    public function edit(MicroPost $post, Request $request)
    {
        $form = $this->formFactory->create(MicroPostType::class, $post );
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->flush();

            return $this->redirectToRoute('micro_post_index');
        }

        return $this->render('micro-post/add.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}",name="micro_post_delete")
     */
    public function delete(MicroPost $post)
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();

        $this->flashBag->add('notice', 'Micropost '. $post->getId() .' was deleted !');

        return $this->redirectToRoute('micro_post_index');
    }

    /**
     * @Route("/add",name="micro_post_add")
     */
    public function add( Request $request)
    {
        $microPost = new MicroPost();
        $microPost->setTime( new DateTime());

        $form = $this->formFactory->create(MicroPostType::class, $microPost );
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            return $this->redirectToRoute('micro_post_index');
        }

        return $this->render('micro-post/add.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}",name="micro_post_fetch")
     */
    public function post(MicroPost $post)
    {
        return $this->render('micro-post/post.html.twig',[
            'post' => $post
        ]);
    }


    /* Comment section
      - No need to import RouterInterface because of using AbstractController Extending
      - Don't use \Twig_environment because deprecated
      - To use the MicroPostType Class, we have to use a new dependency => FormFactory
      - Important to create form HandleRequest before the createView
      - EntityManager Import from Interface
      - Flash Message store inside the session
    */
}
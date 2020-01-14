<?php


namespace App\Controller;


use App\Entity\MicroPost;
use App\Entity\User;
use App\Forms\MicroPostType;
use App\Repository\MicroPostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface ;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route("/micro-post")
 */
class MicroPostController extends AbstractController
{
    private $microPostRepository;
    private $formFactory;
    private $entityManager;
    private $flashBag;
    private $authorizationChecker;


    public function __construct( MicroPostRepository $microPostRepository,
                                 FormFactoryInterface $formFactory,
                                 EntityManagerInterface $entityManager,
                                 FlashBagInterface $flashBag,
                                 AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->authorizationChecker = $authorizationChecker;
    }


    /**
     * @Route("/",name="micro_post_index")
     */
    public function index(TokenStorageInterface $tokenStorage, UserRepository $userRepository)
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        $allPosts  = $this->microPostRepository->findAll();
        $userToFollow = [];

        if($tokenStorage->getToken()->getRoleNames() == [User::ROLE_USER]){
            if($currentUser instanceof User){
               $posts = $this->microPostRepository->findAllByUsers($currentUser->getFollowing());
               $userToFollow = count($posts) !== 0 ? [] : $userRepository->findAllWithMoreThan5PostsExceptUser($currentUser);
            }

            return $this->render('micro-post/index.html.twig',[
                    'posts' => $posts,
                    'usersToFollow' => $userToFollow
                 ]
            );
        }

        return $this->render('micro-post/index.html.twig',[
            'posts' => $allPosts
        ]);
    }

    /**
     * @Route("/edit/{id}",name="micro_post_edit")
     * @Security("is_granted('edit', post)", message="Access Denied for Editing")
     */
    public function edit(MicroPost $post, Request $request)
    {
        /*
         * other option for security annotation
         *
         * if(!$this->authorizationChecker->isGranted('edit', $post))
        {
            throw new UnauthorizedHttpException();
        }*/

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
     * @Security("is_granted('delete', post)", message="Access Denied for Deleting")
     */
    public function delete(MicroPost $post)
    {
        $postId = $post->getId();
        $this->entityManager->remove($post);
        $this->entityManager->flush();

        $this->flashBag->add('notice', 'Micropost '. $postId .' was deleted !');

        return $this->redirectToRoute('micro_post_index');
    }


    /**
     * @Route("/add",name="micro_post_add")
     * @Security("is_granted('ROLE_USER')")
     */
    public function add( Request $request)
    {
        $authUser = $this->getUser();
        $microPost = new MicroPost();
        $microPost->setUser($authUser);

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
     * @Route("/user/{username}", name="micro_post_user")
     */
    public function userPosts(User $userWithPosts)
    {
        return $this->render('micro-post/user-posts.html.twig',[
                'posts' => $userWithPosts->getPosts(),
                'user'  => $userWithPosts
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

      - this->getUser() ; super method allow to retrieve authenticated user
    */
}
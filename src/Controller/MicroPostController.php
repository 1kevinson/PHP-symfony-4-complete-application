<?php


namespace App\Controller;


use App\Repository\MicroPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/micro-post")
 */
class MicroPostController extends AbstractController
{


    private $microPostRepository;


    public function __construct( MicroPostRepository $microPostRepository)
    {
        $this->microPostRepository = $microPostRepository;
    }

    /**
     * @Route("/",name="micro_post_index")
     */
    public function index()
    {
        return $this->render('micro-post/index.html.twig',
            [
                'posts' => $this->microPostRepository->findAll()
            ]
        );
    }




    /* Comment section

      - Don't use \Twig_environment because deprecated

    */
}
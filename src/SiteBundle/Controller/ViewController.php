<?php

namespace SiteBundle\Controller;

use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Entity\Annonce;
use SiteBundle\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewController extends Controller {

    /**
     * @Route("/annonces" , name = "annonces")
     */
    public function testIndex(Request $request) {
        
        

        $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SiteBundle:Annonce');
        $advert = $repository->findAll();

        $repository2 = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SiteBundle:Categories');
        $catego = $repository2->findAll();

        return $this->render('SiteBundle:Default:index.html.twig', array('test' => $advert, 'cate' => $catego ));
            
    }
    /**
     * @Route("/annonces/ajax" , name = "test")
     */
    public function test(Request $request) {
        
        $req = $request->get('select');   
        $reponse = new Response($req);
        
                         
        
        return $reponse ;
        

    }
    
    /**
     * @Route("/ajout")
     */
    public function ajoutAnnonce(Request $request) {

        $cate = new Categories;


        $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SiteBundle:Categories');
        $advert = $repository->findAll();
        for ($i = 0; $i < sizeof($advert); $i ++) {
            $categories[] = $advert[$i];
        }

        $annonce = new Annonce;
        $form = $this->createFormBuilder($annonce)
                ->add('Nom', TextType::class)
                ->add('auteur')
                ->add('prix', IntegerType::class)
                ->add('image', FileType::class)
                ->add('description', TextareaType::class)
                ->add('categorie')
                ->add('Envoyer', SubmitType::class)
                ->getForm();



        $form->handleRequest($request);
        if ($form->isValid()) {


            $image = $annonce->getImage();

            // On récupère le nom original du fichier de l'internaute
            $nom = $image->getClientOriginalName();

            // On déplace le fichier envoyé dans le répertoire de notre choix
            $image->move("img", $nom);

            $annonce->setDate(new DateTime);
            $annonce->setImage("img/" . $nom);



            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('annonces');
        }

        return $this->render('SiteBundle:Default:ajout.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/supprimer/{nom}")
     */
    public function supresionAnnonce($nom) {


        $em = $this->getDoctrine()->getManager();
        $annonce = $em->getRepository('SiteBundle:Annonce')->findOneBy(array('Nom' => $nom));
        $em->remove($annonce);


        $em->flush();
        return $this->redirectToRoute('annonces');
    }

}

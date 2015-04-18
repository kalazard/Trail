<?php namespace Site\TrailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Site\TrailBundle\Entity\Categorie;
use Site\TrailBundle\Entity\Image;
use Symfony\Component\HttpFoundation\Request;

class GalleryController extends Controller
{
    public function indexAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository("SiteTrailBundle:Categorie");
        $listeCategorie = $repository->findAll();
        
        $listeImage = array();
        
        //récupération des 4 premières images de la catégorie
        foreach($listeCategorie as $categorie)
        {
            $qb = $manager->createQueryBuilder();
            $qb->select('img')
                ->from('SiteTrailBundle:Image', 'img')
                ->where('img.categorie = :idCategorie')
                ->orderBy('img.id', 'DESC')
                ->setParameter('idCategorie', $categorie->getId())
                ->setMaxResults(4);
            
            $query = $qb->getQuery();
            
            $listeImage[] = $query->getResult();
        }
                        
        $content = $this->get("templating")->render("SiteTrailBundle:Gallery:index.html.twig", array(
                                                        'listeCategorie' => $listeCategorie,
                                                        'listeImage' => $listeImage
                                                    ));
        return new Response($content);
    }
	
    public function categoryAction($idCategorie)
    {
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository("SiteTrailBundle:Categorie");
        $categorie = $repository->findOneById($idCategorie);
        
        $qb = $manager->createQueryBuilder();
        $qb->select('img')
            ->from('SiteTrailBundle:Image', 'img')
            ->where('img.categorie = :idCategorie')
            ->orderBy('img.id', 'DESC')
            ->setParameter('idCategorie', $idCategorie);

        $query = $qb->getQuery();

        $listeImage = $query->getResult();

        $content = $this->get("templating")->render("SiteTrailBundle:Gallery:category.html.twig", array(
                                                            'categorie' => $categorie,
                                                            'listeImage' => $listeImage
                                                    ));
        return new Response($content);
    }
	
	public function pictureAction()
    {
        $content = $this->get("templating")->render("SiteTrailBundle:Gallery:picture.html.twig");
        return new Response($content);
    }
    
    public function categorieAjoutAction(Request $request)
    {
        if($request->isXmlHttpRequest() && $this->getUser()->getRole()->getId() == 1)
        {
            $categorie = new Categorie();
            $formBuilder = $this->get('form.factory')->createBuilder('form', $categorie);
            $formBuilder
                    ->setAction($this->generateUrl('site_trail_category_ajout'))
                    ->add('label', 'text', array('max_length' => 255));
            
            $form = $formBuilder->getForm();
            $form->handleRequest($request);

            $manager = $this->getDoctrine()->getManager();
            
            if ($form->isValid()) 
            {
                
                
                $repository = $manager->getRepository("SiteTrailBundle:Categorie");               
                $manager->persist($categorie);
                $manager->flush();

                return $this->redirect($this->generateUrl('site_trail_gallery'));
            }
            
            $formulaire = $this->get("templating")->render("SiteTrailBundle:Gallery:formAddCategorie.html.twig", array(
                                                                'form' => $form->createView()
                                                            ));

            return new Response($formulaire);
        }
        else
        {
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }
    
    public function categorieSuppressionAction(Request $request, $idCategorie)
    {
       /* if($request->isXmlHttpRequest())
        {
            $manager=$this->getDoctrine()->getManager();

            //On récupère l'objet catégorie
            $repository=$manager->getRepository("SiteTrailBundle:Categorie");        
            $categorie = $repository->findOneById($idCategorie);

            //On récupère les images liées à la catégorie
            $repository=$manager->getRepository("SiteTrailBundle:Image"); 
            $images = $repository->findBy(
                array('categorie' => $idCategorie)
            );

            foreach($images as $image)
            {
                $manager->remove($image);       
            }

            //Suppression de l'entité catégorie
            $manager->remove($categorie);
            $manager->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Catégorie supprimé');

            return $this->redirect($this->generateUrl('site_trail_gallery'));
        }
        else
        {
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }*/
    }
}    
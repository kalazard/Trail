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
    
    public function categorieSuppressionAction(Request $request)
    {
        if($request->isXmlHttpRequest() && $this->getUser()->getRole()->getId() == 1)
        {
            $idCategorie = $request->request->get('idCategorie', '');
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
        }
    }
    
    public function CategorieModifAction(Request $request)
    {
        if($request->isXmlHttpRequest() && $this->getUser()->getRole()->getId() == 1)
        {      
            $idCategorie = $request->request->get('idCategorie', '');
            
            $manager=$this->getDoctrine()->getManager();
            $repository=$manager->getRepository("SiteTrailBundle:Categorie");        
            $maCategorie = $repository->findOneById($idCategorie);

            $formBuilder = $this->get('form.factory')->createBuilder('form', $maCategorie);
            $formBuilder
                    ->setAction($this->generateUrl('site_trail_category_modification'))
                    ->add('label', 'text', array('max_length' => 255,
                                                    'data' => $maCategorie->getLabel()));

            $form = $formBuilder->getForm();
            $form->handleRequest($request);

            if ($form->isValid()) 
            {
                $manager = $this->getDoctrine()->getManager();
                $manager->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Catégorie ajouté');

                return $this->redirect($this->generateUrl('site_trail_gallery'));
            }
            
            $formulaire = $this->get("templating")->render("SiteTrailBundle:Gallery:modifCategoryForm.html.twig", array(
                                                                'categorie' => $maCategorie,
                                                                'form' => $form->createView()
                                                            ));

            return new Response($formulaire);
        }
        else
        {
            throw new NotFoundHttpException('Impossible de trouver la page demandée');
        }
    }
    
    public function showAddPictureAction()
    {
        //Création du select contenant les catégories
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository("SiteTrailBundle:Categorie"); 
        $listeCategorie = $repository->findAll();
        
        $selectCategorie = '<div class="form-group">';
        $selectCategorie .= '<div class="row">';
        $selectCategorie .= '<label class="col-sm-3 control-label">Catégorie :</label>';
        $selectCategorie .= '<div class="col-sm-9">';
        $selectCategorie .= '<select name="categorie" class="form-control">';
        foreach($listeCategorie as $uneCategorie)
        {
            $selectCategorie .= '<option value="'.$uneCategorie->getId().'">'.$uneCategorie->getLabel().'</option>';
        }
        $selectCategorie .= '</select>';
        $selectCategorie .= '</div>';
        $selectCategorie .= '</div>';
        $selectCategorie .= '</div>';

        $formulaire = $this->get("templating")->render("SiteTrailBundle:Gallery:formAddPicture.html.twig", array(
                                                            'selectCategorie' => $selectCategorie
        ));

        return new Response($formulaire);
    }
    
    public function addPictureAction(Request $request)
    {
        var_dump($_POST);
        var_dump($_FILES);
        
        //Sauvegarde du fichier   
        //$target_dir = "C:/testUp/";
        $target_dir = "/var/www/uploads";
        $target_file = $target_dir . basename($_FILES["fichier"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fichier"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "Le fichier n'est pas une image.";
                $uploadOk = 0;
            }
        }
        
        //On vérifie la taille du fichier
        if ($_FILES["fichier"]["size"] > 5000000) {
            echo "L'image est trop volomineuse.";
            $uploadOk = 0;
        }
        
        //Autorisation de certaines extensions de fichier
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Seules les extensions JPG, JPEG, PNG & GIF sont autorisées.";
            $uploadOk = 0;
        }
        
        //On vérifie qu'il n'y a pas eu d'erreurs lors de l'upload
        if ($uploadOk == 0)
        {
            echo "Il y a eu un problème lors de l'envoi du fichier.";
        }
        else
        {
            $date = new \DateTime;
            $newFile = $target_dir."image".date_format($date, 'U').".".$imageFileType;
                
            if (move_uploaded_file($_FILES["fichier"]["tmp_name"], $newFile)) {
                echo "Le fichier a bien été envoyé.";
                list($width, $height, $type, $attr) = getimagesize($newFile); 
                
                $manager = $this->getDoctrine()->getManager();
                
                //On rajoute l'image dans la base de données                
                $titre = $request->request->get('titre', '');
                $description = $request->request->get('description', '');
                $poids = $_FILES["fichier"]["size"];
                $taille = $height.'x'.$width;
                $auteur = $this->getUser();
                $repository = $manager->getRepository("SiteTrailBundle:Categorie");
                
                var_dump($request->request->get('categorie', ''));
                
                $categorie = $repository->findOneById($request->request->get('categorie', ''));
                $path = $newFile; 
                
                $repository = $manager->getRepository("SiteTrailBundle:Image");
                $newImage = new Image();
                $newImage->setTitre($titre);
                $newImage->setDescription($description);
                $newImage->setPoids($poids);
                $newImage->setTaille($taille);
                $newImage->setAuteur($auteur);
                $newImage->setCategorie($categorie);
                $newImage->setPath($path);

                $manager->persist($newImage);
                $manager->flush();
                
            } else {
                echo "Il y a eu un problème lors de l'envoi du fichier.";
            }
        }
        
        return new Response('OK');
    }
}    
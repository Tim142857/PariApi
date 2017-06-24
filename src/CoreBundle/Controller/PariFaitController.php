<?php
namespace CoreBundle\Controller;

use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Head;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\Entity\PariFait;


class PariFaitController extends FOSRestController
{

    /**
     * Return a collection of parisFaits
     * @Get("/parisFaits")
     * @ApiDoc(
     * description = "Return a collection of parisFaits. [require jwt]",
     * statusCodes={
     *      200 = "Return when successfull",
     *      204 = "No content to return",
     *      404 = "Return when resources are not found"
     * },
     * section = "ParisFaits"
     * )
     */
    public function getparisDisPosAction()
    {
        return $this->queryRepo()->findAll();
    }

    /**
     * Create a pariFait
     * @Post("/pariFait/create")
     * @ApiDoc(
     * description = "Create a pariFait.",
     * input = "CoreBundle\Entity\PariFait",
     * statusCodes = {
     *      201 = "Return when resource created",
     *      409 = "Return when resource already exists",
     *      400= "Return when bad request"
     * },
     * section = "ParisFaits"
     * )
     */
    public function postPariFaitAction(Request $request)
    {
        $data = $request->request->all();
        $http_response = new Response();


        $pariFait = new PariFait();
        $form = $this->createForm('CoreBundle\Form\PariFaitType', $pariFait);
        $form->setData($pariFait);
        $form->submit($data);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                try {
                    $em->persist($pariFait);
                    $em->flush();

                    return $http_response->setStatusCode(201);
                } catch (\Exception $e) {
                    return $http_response->setStatusCode(400)->setContent(json_encode(array('Equipe' => array($e->getMessage()))));
                }

            } else {
                return $http_response->setContent(json_encode($this->getErrorMessages($form)));
            }
        }
    }

    /**
     * @param $id
     * Return a pariFait by his id
     * @Get("/pariFait/{id}"),
     * @ApiDoc(
     * description = "Return one pariFait by his id. [require jwt]",
     * statusCodes = {
     *      200 = "Return when successfull",
     *      204 = "No content to return",
     *      404 = "Return when resource not found"
     * },
     * section = "ParisFaits"
     * )
     */
    public function getPariFaitByIdAction($id)
    {
        return $this->queryRepo()->findOneById($id);
    }

//    /**
//     * @param $id
//     * Delete a pariDispo by his id
//     * @Delete("/pariDispo/delete/{id}"),
//     * @ApiDoc(
//     * description = "Return one pariDispo by his id. [require jwt]",
//     * statusCodes = {
//     *      200 = "Return when successfull",
//     *      404 = "Return when resource not found"
//     * },
//     * section = "ParisDispos"
//     * )
//     * @return http_response
//     */
//    public function deletePariDispoByIdAction($id)
//    {
//        $http_response = new Response();
//
//        $pariDispo = $this->queryRepo()->findOneById($id);
//        if ($pariDispo == null) {
//            $http_response->setStatusCode(404);
//        } else {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($pariDispo);
//            $em->flush();
//            $http_response->setStatusCode(200);
//        }
//
//        return $http_response;
//    }

//    /**
//     * Update a pariDispo
//     * @Post("/pariDispo/update")
//     * @ApiDoc(
//     * description = "Update a pariDispo.[require jwt]",
//     * input = "CoreBundle\Entity\PariDispo",
//     * statusCodes = {
//     *      200 = "Return when resource updated",
//     *      404 = "Return when resource not found",
//     *      400= "Return when bad request"
//     * },
//     * section = "ParisDispos"
//     * )
//     */
//    public function updateParisDisposAction(Request $request)
//    {
//
//        $data = $request->request->all();
//        $http_response = new Response();
//
//        $pariDispo = $this->queryRepo()->findOneById($data['id']);
//        unset($data['id']);
//        if ($pariDispo == null) {
//            return $http_response->setStatusCode(404);
//        } else {
//            $form = $this->createForm('CoreBundle\Form\PariDispoType');
//            $form->handleRequest($request);
//            $form->setData($pariDispo);
//            $form->submit($data);
//
//            if ($form->isSubmitted()) {
//
//                if ($form->isValid()) {
//
//                    $em = $this->getDoctrine()->getManager();
//                    $em->persist($pariDispo);
//                    $em->flush();
//
//
//                    return $http_response->setStatusCode(200);
//                } else {
//                    return $http_response->setStatusCode(400)->setContent(json_encode($this->getErrorMessages($form)));
//                }
//            }
//        }
//    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    private function queryRepo()
    {
        return $this->getDoctrine()->getManager()->getRepository('CoreBundle:PariFait');
    }

    private function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            $errors[$key] = $template;
        }
        if ($form->count()) {
            foreach ($form as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        }
        return $errors;
    }

}
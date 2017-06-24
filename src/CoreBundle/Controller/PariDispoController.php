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
use CoreBundle\Entity\PariDispo;


class PariDispoController extends FOSRestController
{

    /**
     * Return a collection of parisDispos
     * @Get("/parisDispos")
     * @ApiDoc(
     * description = "Return a collection of parisDisPos. [require jwt]",
     * statusCodes={
     *      200 = "Return when successfull",
     *      204 = "No content to return",
     *      404 = "Return when resources are not found"
     * },
     * section = "ParisDispos"
     * )
     */
    public function getparisDisPosAction()
    {
        return $this->queryRepo()->findAll();
    }

    /**
     * Create a pariDispo
     * @Post("/pariDispo/create")
     * @ApiDoc(
     * description = "Create a pariDispo.",
     * input = "CoreBundle\Entity\PariDispo",
     * statusCodes = {
     *      201 = "Return when resource created",
     *      409 = "Return when resource already exists",
     *      400= "Return when bad request"
     * },
     * section = "ParisDispos"
     * )
     */
    public function postPariDispoAction(Request $request)
    {

        $data = $request->request->all();
        $http_response = new Response();


        $pariDispo = new PariDispo();
        $form = $this->createForm('CoreBundle\Form\PariDispoType', $pariDispo);
        $form->setData($pariDispo);
        $form->submit($data);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($pariDispo);
                $em->flush();

                return $http_response->setStatusCode(201);
            } else {
                return $http_response->setStatusCode(400)->setContent(json_encode($this->getErrorMessages($form)));
            }
        }
    }

    /**
     * @param $id
     * Return a pariDispo by his id
     * @Get("/sport/pariDispo/{id}"),
     * @ApiDoc(
     * description = "Return one pariDispo by his id. [require jwt]",
     * statusCodes = {
     *      200 = "Return when successfull",
     *      204 = "No content to return",
     *      404 = "Return when resource not found"
     * },
     * section = "ParisDispos"
     * )
     */
    public function getPariDispoByIdAction($id)
    {
        return $this->queryRepo()->findOneById($id);
    }

    /**
     * @param $id
     * Delete a pariDispo by his id
     * @Delete("/pariDispo/delete/{id}"),
     * @ApiDoc(
     * description = "Return one pariDispo by his id. [require jwt]",
     * statusCodes = {
     *      200 = "Return when successfull",
     *      404 = "Return when resource not found"
     * },
     * section = "ParisDispos"
     * )
     * @return http_response
     */
    public function deletePariDispoByIdAction($id)
    {
        $http_response = new Response();

        $pariDispo = $this->queryRepo()->findOneById($id);
        if ($pariDispo == null) {
            $http_response->setStatusCode(404);
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pariDispo);
            $em->flush();
            $http_response->setStatusCode(200);
        }

        return $http_response;
    }

    /**
     * Update a pariDispo
     * @Post("/pariDispo/update")
     * @ApiDoc(
     * description = "Update a pariDispo.[require jwt]",
     * input = "CoreBundle\Entity\PariDispo",
     * statusCodes = {
     *      200 = "Return when resource updated",
     *      404 = "Return when resource not found",
     *      400= "Return when bad request"
     * },
     * section = "ParisDispos"
     * )
     */
    public function updateParisDisposAction(Request $request)
    {

        $data = $request->request->all();
        $http_response = new Response();

        $pariDispo = $this->queryRepo()->findOneById($data['id']);
        unset($data['id']);
        if ($pariDispo == null) {
            return $http_response->setStatusCode(404);
        } else {
            $form = $this->createForm('CoreBundle\Form\PariDispoType');
            $form->handleRequest($request);
            $form->setData($pariDispo);
            $form->submit($data);

            if ($form->isSubmitted()) {

                if ($form->isValid()) {

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($pariDispo);
                    $em->flush();


                    return $http_response->setStatusCode(200);
                } else {
                    return $http_response->setStatusCode(400)->setContent(json_encode($this->getErrorMessages($form)));
                }
            }
        }
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    private function queryRepo()
    {
        return $this->getDoctrine()->getManager()->getRepository('CoreBundle:PariDispo');
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
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
use FOS\RestBundle\Controller\Annotations\Head;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\Entity\BaremeCote;
use Symfony\Component\Routing\Annotation\Route;


class BaremeCoteController extends FOSRestController
{

    /**
     * Return a collection of baremeCotes
     * @Get("/baremesCote")
     * @ApiDoc(
     * description = "Return a collection of baremeCotes. [require jwt]",
     * statusCodes={
     *      200 = "Return when successfull",
     *      204 = "No content to return",
     *      404 = "Return when resources are not found"
     * },
     * section = "BaremesCote"
     * )
     */
    public function getBaremeCotesAction()
    {
        return $this->queryRepo()->findAll();
    }

    /**
     * Create a baremeCote
     * @Post("/baremeCote/create")
     * @ApiDoc(
     * description = "Create a baremeCote.",
     * input = "CoreBundle\Entity\BaremeCote",
     * statusCodes = {
     *      201 = "Return when resource created",
     *      409 = "Return when resource already exists",
     *      400= "Return when bad request"
     * },
     * section = "BaremesCote"
     * )
     */
    public function postBaremeCoteAction(Request $request)
    {
        $data = $request->request->all();
        $http_response = new Response();


        $baremeCote = new BaremeCote();
        $form = $this->createForm('CoreBundle\Form\BaremeCoteType', $baremeCote);
        $form->setData($baremeCote);
        $form->submit($data);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->persist($baremeCote);
                $em->flush();
                return $http_response->setStatusCode(201);

            } else {
                return $http_response->setContent(json_encode($this->getErrorMessages($form)));
            }
        }
    }

    /**
     * Update a baremeCote
     * @Post("/baremeCote/update")
     * @ApiDoc(
     * description = "Update a baremeCote.[require jwt]",
     * input = "CoreBundle\Entity\BaremeCote",
     * statusCodes = {
     *      200 = "Return when resource updated",
     *      404 = "Return when resource not found",
     *      400= "Return when bad request"
     * },
     * section = "BaremesCote"
     * )
     */
    public function updateBaremeCoteAction(Request $request)
    {

        $data = $request->request->all();
        $http_response = new Response();


        $baremeCote = $this->queryRepo()->findOneById($data['id']);
        unset($data['id']);
        if ($baremeCote == null) {
            return $http_response->setStatusCode(404);
        } else {
            $form = $this->createForm('CoreBundle\Form\BaremeCoteType');
            $form->handleRequest($request);
            $form->setData($baremeCote);
            $form->submit($data);

            if ($form->isSubmitted()) {

                if ($form->isValid()) {


                    $em = $this->getDoctrine()->getManager();
                    $em->persist($baremeCote);
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
        return $this->getDoctrine()->getManager()->getRepository('CoreBundle:BaremeCote');
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
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
use CoreBundle\Entity\Sport;
use Symfony\Component\Routing\Annotation\Route;

use CoreBundle\Form\SportType;

class SportController extends FOSRestController
{

    /**
     * Return a collection of sports
     * @Get("/sports")
     * @ApiDoc(
     * description = "Return a collection of sports. [require jwt]",
     * statusCodes={
     *      200 = "Return when successfull",
     *      204 = "No content to return",
     *      404 = "Return when resources are not found"
     * },
     * section = "Sports"
     * )
     */
    public function getSportsAction()
    {
        return $this->queryRepo()->findAll();
    }

    /**
     * Create a sport
     * @Post("/sport/create")
     * @ApiDoc(
     * description = "Create a sport.",
     * input = "CoreBundle\Entity\Sport",
     * statusCodes = {
     *      201 = "Return when resource created",
     *      409 = "Return when resource already exists",
     *      400= "Return when bad request"
     * },
     * section = "Sports"
     * )
     */
    public function postSportAction(Request $request)
    {

        $data = $request->request->all();
        $http_response = new Response();


        $sport = new Sport();
        $form = $this->createForm('CoreBundle\Form\SportType', $sport);
        $form->submit($data);

        $logger = $this->get('logger');

        if ($form->isSubmitted()) {

            if ($form->isValid()) {

                if ($this->queryRepo()->findByNom($data['nom'])) {
                    return $http_response->setStatusCode(409);
                } else {
                    $sport = new Sport();
                    $sport->hydrate($data);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($sport);
                    $em->flush();


                    return $http_response->setStatusCode(201);
                }
            } else {
                return $http_response->setContent(json_encode($this->getErrorMessages($form)));
            }
        }
    }

    /**
     * @param $id
     * Return a sport by his id
     * @Get("/sport/id/{id}"),
     * @ApiDoc(
     * description = "Return one sport by his id. [require jwt]",
     * statusCodes = {
     *      200 = "Return when successfull",
     *      204 = "No content to return",
     *      404 = "Return when resource not found"
     * },
     * section = "Sports"
     * )
     */
    public function getSportByIdAction($id)
    {
        return $this->queryRepo()->findOneById($id);
    }

    /**
     * @param $id
     * Delete a sport by his id
     * @Delete("/sport/delete/{id}"),
     * @ApiDoc(
     * description = "Delete a sport by his id[require jwt]",
     * statusCodes = {
     *      200 = "Return when successfull",
     *      404 = "Return when resource not found"
     * },
     * section = "Sports"
     * )
     * @return http_response
     */
    public function deleteSportByIdAction($id)
    {
        $http_response = new Response();

        $sport = $this->queryRepo()->findOneById($id);
        if ($sport == null) {
            $http_response->setStatusCode(404);
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sport);
            $em->flush();
            $http_response->setStatusCode(200);
        }

        return $http_response;
    }

    /**
     * Update a sport
     * @Post("/sport/update")
     * @ApiDoc(
     * description = "Update a sport.[require jwt]",
     * input = "CoreBundle\Entity\Sport",
     * statusCodes = {
     *      200 = "Return when resource updated",
     *      404 = "Return when resource not found",
     *      400= "Return when bad request"
     * },
     * section = "Sports"
     * )
     */
    public function updateSportAction(Request $request)
    {

        $data = $request->request->all();
        $http_response = new Response();


        $sport = $this->queryRepo()->findOneById($data['id']);
        unset($data['id']);
        if ($sport == null) {
            return $http_response->setStatusCode(404);
        } else {
            $form = $this->createForm('CoreBundle\Form\SportType');
            $form->handleRequest($request);
            $form->submit($data);

            if ($form->isSubmitted()) {

                if ($form->isValid()) {

                    $sport->hydrate($data);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($sport);
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
        return $this->getDoctrine()->getManager()->getRepository('CoreBundle:Sport');
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
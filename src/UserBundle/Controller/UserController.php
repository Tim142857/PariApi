<?php
namespace UserBundle\Controller;

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
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use UserBundle\Entity\User;

class UserController extends FOSRestController
{

    /**
     * Return a collection of users
     * @Get("/users")
     * @ApiDoc(
     * description = "Return a collection of users. [require jwt]",
     * statusCodes={
     *      200 = "Return when successfull",
     *      204 = "No content to return",
     *      404 = "Return when resources are not found"
     * },
     * section = "Users"
     * )
     */
    public function getUsersAction()
    {
        return $this->queryRepo()->findAll();
    }

    /**
     * @param $username
     * Return a user by his username
     * @Get("/user/{username}"),
     * @ApiDoc(
     * description = "Return one user by his username. [require jwt]",
     * statusCodes = {
     *      200 = "Return when successfull",
     *      204 = "No content to return",
     *      404 = "Return when resource not found"
     * },
     * section = "Users"
     * )
     */
    public function getUserByUsernameAction($username)
    {
        return $this->queryRepo()->findOneByUsername($username);
    }

    /**
     * @param $id
     * Return a user by his id
     * @Get("/user/id/{id}"),
     * @ApiDoc(
     * description = "Return one user by his id. [require jwt]",
     * statusCodes = {
     *      200 = "Return when successfull",
     *      204 = "No content to return",
     *      404 = "Return when resource not found"
     * },
     * section = "Users"
     * )
     */
    public function getUserByIdAction($id)
    {
        return $this->queryRepo()->findOneById($id);
    }


    /**
     * Return a user by his email
     * @Get("/user/email/{email}"),
     * @ApiDoc(
     * description = "Return a user by his email. [require jwt]",
     * statusCodes = {
     *      200 = "return when successfull",
     *      204 = "No content to return",
     *      404 = "Return when resource not found"
     * },
     * section = "Users"
     * )
     */
    public function getUserByEmailAction($email)
    {
        return $this->queryRepo()->findOneByEmail($email);
    }

    /**
     * Create a user
     * @Post("/user")
     * @ApiDoc(
     * description = "Create a user.",
     * input = "FOS\UserBundle\Model\User",
     * statusCodes = {
     *      201 = "Return when resource created",
     *      409 = "Return when resource already exists"
     * },
     * section = "Users"
     * )
     */
    public function postUserAction(Request $request)
    {
        $http_response = new Response();

        $data = $request->request->all();

        $http_response = new Response();

//        /** @var $formFactory FactoryInterface */
//        $formFactory = $this->get('fos_user.registration.form.factory');
//        /** @var $userManager UserManagerInterface */
//        $userManager = $this->get('fos_user.user_manager');
//
//        $user = $userManager->createUser();
//        $user->setEnabled(true);
//
//        $tokenGenerator = $this->get('fos_user.util.token_generator');
//        $user->setConfirmationToken($tokenGenerator->generateToken());
//
//
//        $form = $formFactory->createForm();
//        $form->setData($user);
//
//        $form->submit($data);
//
//
//        if ($form->isSubmitted()) {
//            if ($form->isValid()) {
//                $userManager->updateUser($user);
//                die('test');
//                return $http_response->setStatusCode(201);
//            } else {
//                return $http_response->setStatusCode(400)->setContent(json_encode($this->getErrorMessages($form)));
//            }
//        }

        //------------------------------------------------------

        $data = $request->request->all();
        $http_response = new Response();

        $errors = $this->validator($data);
        if ($errors != null) {
            return $http_response->setStatusCode(400)->setContent(json_encode($errors));
        } else {
            $user = new User();
            $user->hydrate($data);

            $userManager = $this->get('fos_user.user_manager');
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
            $userManager->updateUser($user);

            return $http_response->setStatusCode(201);
        }
    }

    /**
     * Change password to current user
     * @Post("/user/change-password")
     * @ApiDoc(
     * description = "Create to current user.",
     * statusCodes = {
     *      201 = "Return when resource updated",
     *      204 = "No content to return",
     *      401 = "Bad credentials",
     *      409 = "Return when conficts on password confirmation"
     * },
     * section = "Users"
     * )
     */
    public function postUserChangePwAction(Request $request)
    {

        $data = $request->request->all();
        $http_response = new Response();

        $userManager = $this->get('fos_user.user_manager');
        $user = $this->getUser();

        // TODO: clear token when fail
//        $tmptoken = explode(' ', $request->headers->get('authorization'));
//        $token = $tmptoken[1];

        if (!is_object($user) || !$user instanceof UserInterface)
            throw new AccessDeniedException('This user does not have access to this section.');

        if (password_verify($data['plainpassword'], $user->getPassword())) {

            if ($data['new_password'] !== $data['confirm_password'])
                return $http_response->setStatusCode(409);

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $data['new_password']);
            $user->setPassword($encoded);
            $userManager->updateUser($user);
            return $http_response->setStatusCode(201);
        } else {
            $http_response->setStatusCode(401);
        }
    }


    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    private function queryRepo()
    {
        return $this->getDoctrine()->getManager()->getRepository('UserBundle:User');
    }

    private function validator($data)
    {
        $errors = array();

        $errors['email'] = array();
        $errors['pseudo'] = array();
        $errors['mot de passe'] = array();

        if (!isset($data['email'])) {
            array_push($errors['email'], "l'email ne peut pas etre vide");
        } else if ($data['email'] == "") {
            array_push($errors['email'], "l'email ne peut pas etre vide");
        } else if (count($this->queryRepo()->findByEmail($data['email'])) > 0) {
            array_push($errors['email'], "cet email est déja utilisé");
        } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            array_push($errors['email'], "cet email n'est pas valide");
        }

        if (!isset($data['username'])) {
            array_push($errors['pseudo'], "le pseudo ne peut pas etre vide");
        } else if ($data['username'] == "") {
            array_push($errors['pseudo'], "le pseudo ne peut pas etre vide");
        } else if (count($this->queryRepo()->findByUsername($data['username'])) > 0) {
            array_push($errors['pseudo'], "ce pseudo est déja utilisé");
        }

        if (!isset($data['plainPassword'])) {
            array_push($errors['mot de passe'], "le mot de passe ne peut pas etre vide");
        } else if ($data['plainPassword'] == "") {
            array_push($errors['mot de passe'], "le mot de passe ne peut pas etre vide");
        }

        $bool = count($errors['email']) + count($errors['pseudo']) + count($errors['mot de passe']) > 0;

        foreach ($errors as $errorName => $errorValues) {
            if (count($errorValues) == 0) {
                unset($errors[$errorName]);
            }
        }


        if ($bool) {
            return $errors;
        } else {
            return null;
        }

    }

}
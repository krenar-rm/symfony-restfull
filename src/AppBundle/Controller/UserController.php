<?php
/**
 * UserController.php
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package AppBundle\Controller
 *
 *
 * Тестовый класс, для демонстрации работы REST-запросов.
 * GET
 * POST
 * PUT
 * DELETE
 */
class UserController extends FOSRestController
{
    /**
     * @Rest\Get("/user")
     * @return array|View
     */
    public function getAction()
    {
        $restResult = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Users')
            ->findAll();

        if (null === $restResult) {
            return new View(
                'There are no users exist',
                Response::HTTP_NOT_FOUND
            );
        }

        return $restResult;
    }

    /**
     * @Rest\Post("/user/")
     *
     * @return View
     */
    public function saveAction(Request $request)
    {
        $name = $request->get('name');
        if(empty($name))
        {
            return new View(
                'Empty values',
                Response::HTTP_NOT_ACCEPTABLE
            );
        }
        $user = new Users();
        $user
            ->setName($name)
            ->setActual(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new View(
            'User Added Successfully',
            Response::HTTP_OK
        );
    }

    /**
     * @Rest\Put("/user/{id}")
     */
    public function updateAction($id, Request $request)
    {
        $name = $request->get('name');
        $user = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Users')
            ->find($id);

        if ($user) {
            $user->setName($name);

            $sn = $this->getDoctrine()->getManager();
            $sn->flush();

            return new View('User Name Updated Successfully', Response::HTTP_OK);
        }

        return new View('User not found', Response::HTTP_NOT_FOUND);
    }

    /**
     * @Rest\Delete("/user/{id}")
     */
    public function deleteAction($id)
    {
        $user = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Users')
            ->find($id);

        if ($user) {
            $sn = $this->getDoctrine()->getManager();
            $sn->remove($user);
            $sn->flush();

            return new View('Deleted successfully', Response::HTTP_OK);
        }

        return new View('User not found', Response::HTTP_NOT_FOUND);
    }
}

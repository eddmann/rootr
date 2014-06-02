<?php namespace Rootr\Fixtures;


class ProductController extends \Rootr\Controller
{

    public function indexAction()
    {
        return '/';
    }

    /**
     * @route /{id:\d+}
     */
    public function showAction($id)
    {
        return "/$id";
    }

    /**
     * @method DELETE
     */
    public function deleteAction($id)
    {
        return "/delete/$id";
    }

    public function helper()
    {
        return 'helper()';
    }
}

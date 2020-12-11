<?php
// src/Controller/ProductController.php
namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/products")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("", methods={"GET"}, name="app_product_list")
     */
    public function list()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $results = [];
        foreach ($products as $product) {
            /** @var Product $product */
            $results[] = [
                'id' => $product->getId(),
                'name' => $product->getName()
            ];
        }
        return $this->json($results);
    }

    /**
     * @Route("", methods={"POST"}, name="app_product_create")
     */
    public function createAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $product = new Product();
        $product->setName($data['name']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $this->json([
            'id' => $product->getId(),
            'name' => $product->getName()
        ], Response::HTTP_CREATED);
    }
}

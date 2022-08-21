<?php

namespace App\Controller\Catalog;


use App\Entity\ProductDomain\ProductName;
use App\Entity\ProductDomain\ProductPrice;
use App\Messenger\AddProductToCatalog;
use App\Messenger\MessageBusAwareInterface;
use App\Messenger\MessageBusTrait;
use App\ResponseBuilder\ErrorBuilder;
use App\Service\Catalog\ErrorMessageHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/products", methods={"POST"}, name="product-add")
 */
class AddController extends AbstractController implements MessageBusAwareInterface
{
    use MessageBusTrait;

    public function __construct(
        private readonly ErrorBuilder       $errorBuilder,
        private readonly ValidatorInterface $validator,
        private readonly ErrorMessageHelper $errorMessageHelper,
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        $name = trim($request->get('name'));
        $price = (int)$request->get('price');

        $productPriceMessages = $this->errorMessageHelper->getMessageFromErrors($this->validator->validate(new ProductPrice($price)));
        $productNameMessages = $this->errorMessageHelper->getMessageFromErrors($this->validator->validate(new ProductName($name)));

        if (!$this->errorMessageHelper->isValid()) {
            return new JsonResponse($this->errorBuilder->__invoke(json_encode(array_merge($productPriceMessages, $productNameMessages))),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->dispatch(new AddProductToCatalog($name, (int)$price));

        return new Response('', Response::HTTP_ACCEPTED);
    }
}

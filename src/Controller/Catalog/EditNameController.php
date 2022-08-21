<?php

declare(strict_types=1);

namespace App\Controller\Catalog;

use App\Entity\Product;
use App\Entity\ProductDomain\ProductName;
use App\Messenger\EditProductNameIntoCatalog;
use App\Messenger\MessageBusAwareInterface;
use App\Messenger\MessageBusTrait;
use App\ResponseBuilder\ErrorBuilder;
use App\Service\Catalog\ErrorMessageHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/products/{product}/name", methods={"PUT"}, name="product-name-edit")
 */
class EditNameController extends AbstractController implements MessageBusAwareInterface
{
    use MessageBusTrait;

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ErrorMessageHelper  $errorMessageHelper,
        private readonly ValidatorInterface  $validator,
        private readonly ErrorBuilder        $errorBuilder,
    )
    {
    }

    public function __invoke(Request $request, Product $product): Response
    {
        $productName = $this->serializer->deserialize($request->getContent(), ProductName::class, 'json');
        $productNameMessages = $this->errorMessageHelper->getMessageFromErrors($this->validator->validate($productName));

        if (!$this->errorMessageHelper->isValid()) {
            return new JsonResponse($this->errorBuilder->__invoke(json_encode($productNameMessages)),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->dispatch(new EditProductNameIntoCatalog($product->getId(), $productName->getName()));

        return new Response('Name edited', Response::HTTP_ACCEPTED);
    }
}

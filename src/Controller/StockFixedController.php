<?php

namespace App\Controller;

use App\Entity\LpStockFixed;
use App\Entity\PsProduct;
use App\Entity\PsProductAttribute;
use App\Entity\PsProductAttributeCombination;
use App\Entity\PsAttributeLang;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StockFixedController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/stock_fixed_list', name: 'stock_fixed_list')]
    public function list(): Response
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select(
            'sf.id_stock AS id_stock',
            'sf.ean13 AS ean13',
            'sf.quantity_shop_1 AS quantity_shop_1',
            'sf.quantity_shop_2 AS quantity_shop_2',
            'sf.quantity_shop_3 AS quantity_shop_3',
            'COALESCE(p.reference, p2.reference) AS reference_combination',
            "GROUP_CONCAT(DISTINCT al.name ORDER BY al.idAttribute SEPARATOR ' - ') AS combination_name"
        )
            ->from(LpStockFixed::class, 'sf')
            ->leftJoin(PsProductAttribute::class, 'pa', 'WITH', 'pa.ean13 = sf.ean13')
            ->leftJoin('pa.idProduct', 'p')
            ->leftJoin(PsProduct::class, 'p2', 'WITH', 'p2.ean13 = sf.ean13')
            ->leftJoin(PsProductAttributeCombination::class, 'pac', 'WITH', 'pa.id_product_attribute = pac.id_product_attribute')
            ->leftJoin(PsAttributeLang::class, 'al', 'WITH', 'pac.idAttribute = al.idAttribute AND al.id_lang = 1')
            ->groupBy('sf.id_stock')
            ->orderBy('sf.id_stock', 'ASC');

        $records = $qb->getQuery()->getResult();

        return new JsonResponse($records);
    }

    #[Route('/stock_fixed_add', name: 'stock_fixed_add', methods: ['POST'])]
    public function add(Request $request): Response
    {
        $payload = json_decode($request->getContent(), true);
        if (!is_array($payload)) {
            return new JsonResponse(['error' => 'Invalid payload'], Response::HTTP_BAD_REQUEST);
        }

        $items = array_is_list($payload) ? $payload : [$payload];
        $createdIds = [];

        foreach ($items as $data) {
            if (!isset($data['ean13'], $data['quantity_shop_1'], $data['quantity_shop_2'], $data['quantity_shop_3'])) {
                return new JsonResponse(['error' => 'Missing parameters'], Response::HTTP_BAD_REQUEST);
            }

            $record = new LpStockFixed();
            $record->setEan13($data['ean13']);
            $record->setQuantityShop1($data['quantity_shop_1']);
            $record->setQuantityShop2($data['quantity_shop_2']);
            $record->setQuantityShop3($data['quantity_shop_3']);

            $this->entityManager->persist($record);
            $createdIds[] = $record;
        }

        $this->entityManager->flush();

        $ids = array_map(fn (LpStockFixed $r) => $r->getIdStock(), $createdIds);

        return new JsonResponse(['message' => 'Records created', 'id_stocks' => $ids]);
    }

    #[Route('/stock_fixed_update_quantity', name: 'stock_fixed_update_quantity', methods: ['POST'])]
    public function updateQuantity(Request $request): Response
    {
        $payload = json_decode($request->getContent(), true);
        if (!is_array($payload)) {
            return new JsonResponse(['error' => 'Invalid payload'], Response::HTTP_BAD_REQUEST);
        }

        $items = array_is_list($payload) ? $payload : [$payload];
        $updatedIds = [];

        foreach ($items as $data) {
            if (!isset($data['id_stock'])) {
                return new JsonResponse(['error' => 'Missing id_stock'], Response::HTTP_BAD_REQUEST);
            }

            $record = $this->entityManager->getRepository(LpStockFixed::class)->find($data['id_stock']);
            if (!$record) {
                return new JsonResponse(['error' => 'Record not found'], Response::HTTP_NOT_FOUND);
            }

            if (isset($data['quantity_shop_1'])) {
                $record->setQuantityShop1($data['quantity_shop_1']);
            }
            if (isset($data['quantity_shop_2'])) {
                $record->setQuantityShop2($data['quantity_shop_2']);
            }
            if (isset($data['quantity_shop_3'])) {
                $record->setQuantityShop3($data['quantity_shop_3']);
            }

            $updatedIds[] = $record->getIdStock();
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Records updated', 'id_stocks' => $updatedIds]);
    }

    #[Route('/stock_fixed_delete', name: 'stock_fixed_delete', methods: ['POST'])]
    public function delete(Request $request): Response
    {
        $payload = json_decode($request->getContent(), true);
        if (!is_array($payload)) {
            return new JsonResponse(['error' => 'Invalid payload'], Response::HTTP_BAD_REQUEST);
        }

        $ids = [];
        if (array_is_list($payload)) {
            $ids = $payload;
        } elseif (isset($payload['id_stock'])) {
            $ids[] = $payload['id_stock'];
        } else {
            return new JsonResponse(['error' => 'Missing id_stock'], Response::HTTP_BAD_REQUEST);
        }

        foreach ($ids as $id) {
            $record = $this->entityManager->getRepository(LpStockFixed::class)->find($id);
            if (!$record) {
                return new JsonResponse(['error' => 'Record not found'], Response::HTTP_NOT_FOUND);
            }
            $this->entityManager->remove($record);
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Records deleted']);
    }
}

<?php

namespace App\Logic;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\PsCartRuleLang;
use App\Entity\PsCartRule;
use App\Entity\PsOrderCartRule;

class CartRuleLogic
{
    private $entityManagerInterface;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManagerInterface = $doctrine->getManager('default');
    }

    public function generateCartRuleJSON($cartRule)
    {
        $cartRuleLang = $this->entityManagerInterface->getRepository(PsCartRuleLang::class)
            ->findOneBy([
                'id_cart_rule' => $cartRule->getIdCartRule(),
                'id_lang' => 1,
            ]);

        $cartRuleData = [
            'name' => $cartRuleLang?->getName(),
            'date_from' => $cartRule->getDateFrom()?->format('Y-m-d H:i:s'),
            'date_to' => $cartRule->getDateTo()?->format('Y-m-d H:i:s'),
            'id_customer' => $cartRule->getIdCustomer(),
            'description' => $cartRule->getDescription(),
            'quantity' => $cartRule->getQuantity(),
            'quantity_per_user' => $cartRule->getQuantityPerUser(),
            'priority' => $cartRule->getPriority(),
            'partial_use' => $cartRule->getPartialUse(),
            'code' => $cartRule->getCode(),
            'minimum_amount' => $cartRule->getMinimumAmount(),
            'minimum_amount_tax' => $cartRule->getMinimumAmountTax(),
            'minimum_amount_currency' => $cartRule->getMinimumAmountCurrency(),
            'minimum_amount_shipping' => $cartRule->getMinimumAmountShipping(),
            'country_restriction' => $cartRule->getCountryRestriction(),
            'carrier_restriction' => $cartRule->getCarrierRestriction(),
            'group_restriction' => $cartRule->getGroupRestriction(),
            'cart_rule_restriction' => $cartRule->getCartRuleRestriction(),
            'product_restriction' => $cartRule->getProductRestriction(),
            'shop_restriction' => $cartRule->getShopRestriction(),
            'free_shipping' => $cartRule->getFreeShipping(),
            'reduction_percent' => $cartRule->getReductionPercent(),
            'reduction_amount' => $cartRule->getReductionAmount(),
            'reduction_tax' => $cartRule->getReductionTax(),
            'reduction_currency' => $cartRule->getReductionCurrency(),
            'reduction_product' => $cartRule->getReductionProduct(),
            'reduction_exclude_special' => $cartRule->getReductionExcludeSpecial(),
            'gift_product' => $cartRule->getGiftProduct(),
            'gift_product_attribute' => $cartRule->getGiftProductAttribute(),
            'highlight' => $cartRule->getHighlight(),
            'active' => $cartRule->getActive(),
            'date_add' => $cartRule->getDateAdd()?->format('Y-m-d H:i:s'),
            'date_upd' => $cartRule->getDateUpd()?->format('Y-m-d H:i:s'),
        ];

        return $cartRuleData;
    }

    public function createCartRuleFromJSON(array $data): PsCartRule
    {
        // Crear una nueva instancia de CartRule
        $cartRule = new PsCartRule();


        // Mapear los datos del JSON a las propiedades del CartRule
        $cartRule->setDateFrom(new \DateTime($data['date_from']));
        $cartRule->setDateTo(new \DateTime($data['date_to']));
        $cartRule->setDescription($data['description']);
        $cartRule->setQuantity(1);
        $cartRule->setQuantityPerUser(1);
        $cartRule->setPriority(1);
        $cartRule->setCode($this->generateUniqueCartRuleCode());
        $cartRule->setActive(true);
        $cartRule->setIdCustomer($data['id_customer']);
        $cartRule->setDateAdd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $cartRule->setDateUpd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));

        // Configurar descuento (por porcentaje o cantidad)
        $cartRule->setReductionPercent($data['reduction_percent']);
        $cartRule->setReductionAmount($data['reduction_amount']);

        $cartRule->setPartialUse(0);
        $cartRule->setMinimumAmount(0.00);
        $cartRule->setMinimumAmountTax(0);
        $cartRule->setMinimumAmountCurrency(0);
        $cartRule->setMinimumAmountShipping(0);
        $cartRule->setCountryRestriction(0);
        $cartRule->setCarrierRestriction(0);
        $cartRule->setGroupRestriction(0);
        $cartRule->setCartRuleRestriction(0);
        $cartRule->setProductRestriction(0);
        $cartRule->setShopRestriction(0);
        $cartRule->setFreeShipping(0);
        $cartRule->setReductionTax(0);
        $cartRule->setReductionCurrency(0);
        $cartRule->setReductionProduct(0);
        $cartRule->setReductionExcludeSpecial(0);
        $cartRule->setGiftProduct(0);
        $cartRule->setGiftProductAttribute(0);
        $cartRule->setHighlight(0);

        $this->entityManagerInterface->persist($cartRule);
        $this->entityManagerInterface->flush();
        // Crear una nueva instancia de PsCartRuleLang
        $cartRuleLang = new PsCartRuleLang();
        $cartRuleLang->setName($data['name']);
        $cartRuleLang->setIdLang(1);
        $cartRuleLang->setIdCartRule($cartRule->getIdCartRule());

        // Persistir la entidad PsCartRuleLang
        $this->entityManagerInterface->persist($cartRuleLang);
        $this->entityManagerInterface->flush();

        return $cartRule;
    }

    public function generateUniqueCartRuleCode(): string
    {
        $length = 9;
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        do {
            // Genera una referencia aleatoria de 9 caracteres
            $reference = '';
            for ($i = 0; $i < $length; $i++) {
                $reference .= $characters[rand(0, strlen($characters) - 1)];
            }

            // Verifica si la referencia ya existe en la base de datos
            $existingOrder = $this->entityManagerInterface->getRepository(PsCartRule::class)
                ->findOneBy(['code' => $reference]);
        } while ($existingOrder !== null); // Repetir si ya existe

        return $reference;
    }

    
    public function generateOrderCartRule($newPsOrder, $cart_rule, $discount)
    {
        $orderCartRule = new PsOrderCartRule();

        $cart_rule_lang = $this->getCartRuleLangByCartRuleIdAndOrigin($cart_rule, 'mayret');

        $orderCartRule->setIdOrder($newPsOrder->getIdOrder());
        $orderCartRule->setIdCartRule($cart_rule->getIdCartRule());
        $orderCartRule->setName($cart_rule_lang->getName());
        $orderCartRule->setValue($discount['amount']);
        $orderCartRule->setValueTaxExcl($cart_rule->getReductionAmount() / (1 + $cart_rule->getReductionTax() / 100));
        $orderCartRule->setFreeShipping(0);

        $this->entityManagerInterface->persist($orderCartRule);
        $this->entityManagerInterface->flush();

        return $orderCartRule;
    }

    public function getCartRulesByOrderIdAndOrigin($id_order, $origin)
    {
        return $this->entityManagerInterface->getRepository(PsOrderCartRule::class)
            ->findByIdOrder($id_order);
    }

    public function getCartRuleByIdAndOrigin($orderCartRule,$origin)
    {
        return $this->entityManagerInterface->getRepository(PsCartRule::class)
            ->findByIdCartRule($orderCartRule->getIdCartRule());
    }

    public function getCartRuleLangByCartRuleIdAndOrigin($cartRule, $origin)
    {
        return $this->entityManagerInterface->getRepository(PsCartRuleLang::class)
            ->findOneBy(['id_cart_rule' => $cartRule->getIdCartRule()]);
    }

    public function generateCartRulesJSON($orderData, $orderCartRules, $origin): array
    {
        // Procesar los cart rules de la orden
        foreach ($orderCartRules as $orderCartRule) {
            $cartRule = $this->getCartRuleByIdAndOrigin($orderCartRule, $origin);
            if ($cartRule) {
                $cartRuleLang = $this->getCartRuleLangByCartRuleIdAndOrigin($cartRule, $origin);
                $orderData['order_cart_rules'][] = [
                    'code' => $cartRule->getCode(),
                    'name' => $cartRuleLang ? $cartRuleLang->getName() : $orderCartRule->getName(),
                    'value' => $orderCartRule->getValue(),
                    'description' => $cartRule ? $cartRule->getDescription() : $cartRule->getDescription(),
                ];
            }
        }
        return $orderData;
    }
}
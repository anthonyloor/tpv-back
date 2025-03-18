<?php

namespace App\Logic;

use App\Entity\PsAddress;
use App\Entity\PsCustomer;
use App\EntityFajasMaylu\PsCustomer as PsCustomerMaylu;

use Doctrine\Persistence\ManagerRegistry;
use App\EntityFajasMaylu\PsAddress as PsAddressMaylu;


class CustomerLogic
{
    private $entityManagerInterface;
    private $emFajasMaylu;


    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManagerInterface = $doctrine->getManager('default');
        $this->emFajasMaylu = $doctrine->getManager('fajas_maylu');
    }

    public function createCustomer($data): PsCustomer
    {
        $customer = new PsCustomer();
        $customer->setFirstname($data['firstname']);
        $customer->setLastname($data['lastname']);
        $customer->setCompany($data['company'] ?? '');
        $customer->setEmail($data['email']);
        $customer->setPasswd(password_hash($data['password'], PASSWORD_BCRYPT));
        $customer->setIdShop($data['id_shop']);
        $customer->setIdDefaultGroup($data['id_default_group']);
        $customer->setIdLang(1);
        $customer->setNewsletter(0);
        $customer->setActive(1);
        $customer->setDeleted(0);
        $customer->setDateAdd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $customer->setDateUpd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $customer->setLastPasswdGen(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $customer->setSecureKey(md5(uniqid()));
        $customer->setIdGender(0);
        $customer->setIdRisk(0);
        $customer->setIsGuest(0);
        $customer->setMaxPaymentDays(0);

        return $customer;
    }

    public function createAddres($data): PsAddress
    {
        $address = new PsAddress();
        $customer = $this->entityManagerInterface
            ->getRepository(PsCustomer::class)
            ->findOneBy(['id_customer' => $data['id_customer']]);
        $address->setCustomer($customer);
        $address->setIdCountry($data['id_country']);
        $address->setIdState($data['id_state']);
        $address->setAlias($data['alias']);
        $address->setCompany($data['company'] ?? '');
        $address->setLastname($data['lastname']);
        $address->setFirstname($data['firstname']);
        $address->setAddress1($data['address1']);
        $address->setAddress2($data['address2'] ?? '');
        $address->setPostcode($data['postcode']);
        $address->setCity($data['city']);
        $address->setOther($data['other'] ?? '');
        $address->setPhone($data['phone'] ?? '');
        $address->setPhoneMobile($data['phone_mobile'] ?? '');
        $address->setVatNumber($data['vat_number'] ?? '');
        $address->setDni($data['dni'] ?? '');
        $address->setDateAdd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $address->setDateUpd(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $address->setActive(1);
        $address->setDeleted(0);

        return $address;
    }

    public function generateJSONCustomer($allCustomers): array
    {
        // Convertir los objetos a arrays simples
        $customersArray = [];
        foreach ($allCustomers as $customer) {


            if ($customer->getOrigin() == 'mayret') {
                $address = $this->entityManagerInterface->getRepository(PsAddress::class)->findOneByCustomerId($customer->getIdCustomer()
                );
            } else {
                $address = $this->emFajasMaylu->getRepository(PsAddressMaylu::class)->findOneByCustomerId($customer->getIdCustomer()
                );
            }


            $phone = $address ? ($address->getPhone() ?: $address->getPhoneMobile()) : null;
            $mobilePhone = $address ? ($address->getPhoneMobile() ?: $address->getPhone()) : null;
            $finalPhone = $phone ? $phone : $mobilePhone;

            $customersArray[] = [
                'id_customer' => $customer->getIdCustomer(),
                'firstname' => $customer->getFirstname(),
                'lastname' => $customer->getLastname(),
                'email' => $customer->getEmail(),
                'phone' => $finalPhone,
                'date_add' => $customer->getDateAdd()->format('Y-m-d H:i:s'),
                'origin' => $customer->getOrigin(),
            ];
        }
        return $customersArray;
    }

    public function generateJSONAdresses($addresses): array
    {
        $addressesArray = [];
        foreach ($addresses as $address) {
            $addressesArray[] = [
                'id_address' => $address->getIdAddress(),
                'id_country' => $address->getIdCountry(),
                'id_state' => $address->getIdState(),
                'id_customer' => $address->getIdCustomer(),
                'alias' => $address->getAlias(),
                'company' => $address->getCompany(),
                'lastname' => $address->getLastname(),
                'firstname' => $address->getFirstname(),
                'address1' => $address->getAddress1(),
                'address2' => $address->getAddress2(),
                'postcode' => $address->getPostcode(),
                'city' => $address->getCity(),
                'other' => $address->getOther(),
                'phone' => $address->getPhone(),
                'phone_mobile' => $address->getPhoneMobile(),
                'vat_number' => $address->getVatNumber(),
                'dni' => $address->getDni(),
                'date_add' => $address->getDateAdd()->format('Y-m-d H:i:s'),
                'date_upd' => $address->getDateUpd()->format('Y-m-d H:i:s'),
                'active' => $address->isActive(),
                'deleted' => $address->isDeleted(),
                'origin' => $address->getOrigin(),
            ];
        }
        return $addressesArray;
    }
}

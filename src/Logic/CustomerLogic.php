<?php

namespace App\Logic;

use App\Entity\PsAddress;
use App\Entity\PsCustomer;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PsGroup;
use App\Entity\PsGroupLang;
use App\Entity\PsCustomerGroup;


class CustomerLogic
{
    private $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    public function createCustomer($data):PsCustomer
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
        $customer->setDateAdd(new \DateTime());
        $customer->setDateUpd(new \DateTime());
        $customer->setLastPasswdGen(new \DateTime());
        $customer->setSecureKey(md5(uniqid()));
        $customer->setIdGender(0);
        $customer->setIdRisk(0);
        $customer->setIsGuest(0);
        $customer->setMaxPaymentDays(0);

        return $customer;
    }

    public function createAddres($data):PsAddress
    {
        $address = new PsAddress();
        $address->setIdCustomer($data['id_customer']);
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
        $address->setDateAdd(new \DateTime());
        $address->setDateUpd(new \DateTime());
        $address->setActive(1);
        $address->setDeleted(0);

        return $address;
    }
}
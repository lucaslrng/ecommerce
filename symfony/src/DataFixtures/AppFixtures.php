<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Category;
use App\Entity\Feature;
use App\Entity\Inventory;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Promotion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
         // Création d'un user "normal"
        $user = new User();
        $user->setEmail("user@user.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
        $manager->persist($user);
        
        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("admin@admin.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $password = $this->userPasswordHasher->hashPassword($userAdmin, "password");
        $userAdmin->setPassword($password);
        $manager->persist($userAdmin);

        //Les catégories
        $category_ram = new Category();
        $category_ram->setName('RAM');
        $manager->persist($category_ram);

        $category_proc = new Category();
        $category_proc->setName('Processor');
        $manager->persist($category_proc);

        $category_cm = new Category();
        $category_cm->setName('Motherboard');
        $manager->persist($category_cm);

        $category_cg = new Category();
        $category_cg->setName('Graphicsboard');
        $manager->persist($category_cg);

        //Les caractéristiques
        $feature = new Feature();
        $feature->setName("DDR4");
        $manager->persist($feature);


        $feature2 = new Feature();
        $feature2->setName("DDR5");
        $manager->persist($feature2);

        $feature3 = new Feature();
        $feature3->setName("Socket AM4");
        $manager->persist($feature3);

        $feature4 = new Feature();
        $feature4->setName("Socket 1700");
        $manager->persist($feature4);

        $feature5 = new Feature();
        $feature5->setName("Socket 1200");
        $manager->persist($feature5);

        $feature6 = new Feature();
        $feature6->setName("PCI-Express");
        $manager->persist($feature6);

        //inventaire
        $inventory = new Inventory();
        $inventory->setAmount("10000");

        //Promotion
        $promotion = new Promotion();
        $promotion->setName("Summer Sales");
        $promotion->setAmount(20);
        $manager->persist($promotion);


        $promotion2 = new Promotion();
        $promotion2->setName("Destock one");
        $promotion2->setAmount("50");
        $manager->persist($promotion2);


        $promotion3 = new Promotion();
        $promotion3->setName("Destock two");
        $promotion3->setAmount("70");
        $manager->persist($promotion3);


        //RAM
        $product = new Product();
        $product->setCategory($category_ram);
        $product->setName('Corsair Vengeance LPX Noir - 8 Go 3000 MHz - CAS 16');
        $product->setDescription("La mémoire Vengeance LPX a été spécialement conçue pour l'overclocking haute performance. Le dissipateur thermique est composé d'aluminium pour une dissipation plus rapide de la chaleur, et la carte de circuit imprimé de huit couches gère la chaleur et offre une marge d'overclocking supérieure. Chaque circuit intégré est sélectionné individuellement pour assurer le potentiel de performance.");
        $product->setPrice(42);
        $product->setWeight(95);
        $product->addFeature($feature);
        $manager->persist($product);


        $product2 = new Product();
        $product2->setCategory($category_ram);
        $product2->setName('Kingston Fury Beast Black - 8 Go 5600 MHz - CAS 40');
        $product2->setDescription("La mémoire Kingston FURY Beast DDR5 intègre la toute dernière technologie de pointe aux plateformes de jeu de prochaine génération. Poussant la vitesse, la capacité et la fiabilité encore plus loin, la DDR5 arrive avec un arsenal de fonctionnalités améliorées, comme l’ECC sur puce (ODECC) pour une meilleure stabilité à des vitesses extrêmes, des doubles sous-canaux 32 bits pour une efficacité accrue, et un circuit intégré de gestion de l’énergie sur le module (PMIC) pour fournir de l’énergie là où elle est le plus nécessaire.");
        $product2->setPrice(100);
        $product2->setWeight(90);
        $product2->addFeature($feature2);
        $product2->addPromotion($promotion3);
        $manager->persist($product2);


        $product3 = new Product();
        $product3->setCategory($category_ram);
        $product3->setName('Textorm - 8 Go 2666 MHz - CAS 19');
        $product3->setDescription("Les RAM Textorm ont été conçues pour des PC cherchant le meilleur rapport qualité / performance et prix. Basées sur des puces des plus grands constructeurs (Micron, Samsung, ...) elles vont à l'essentiel en allant chercher la performance de ces puces, un design sobre et des dissipateurs parfaitement ajustés afin d'avoir le moins d'incompatibilités possible.");
        $product3->setPrice(50);
        $product3->setWeight(105);
        $product3->addFeature($feature);
        $product3->addPromotion($promotion2);
        $manager->persist($product3);

        $product4 = new Product();
        $product4->setCategory($category_ram);
        $product4->setName("G.Skill Trident Z5 RGB Argent - 32 Go 6000 MHz");
        $product4->setDescription("La mémoire DDR5 est conçue pour prendre en charge une capacité accrue, à partir de 16 Go et jusqu'à 128 Go par barrette, avec des fréquences pouvant atteindre jusqu'à 6000 MHz. Cela débride les capacités pour un flux de travail plus fluide sur de lourdes charges telles que la création de contenu, le montage vidéo, le rendu d'animation et le calcul scientifique.");
        $product4->setPrice(203);
        $product4->setWeight(105);
        $product4->addFeature($feature2);
        $manager->persist($product4);

        //CM
        $product5 = new Product();
        $product5->setCategory($category_cm);
        $product5->setName("ASRock Z690 PHANTOM GAMING-ITX/TB4");
        $product5->setDescription("La carte mère ASRock Z690 PHANTOM GAMING-ITX/TB4 est le socle idéal pour fonder votre configuration basée sur un processeur Intel Alder Lake-S. Sa conception intègre le chipset Intel Z690 et un socket LGA 1700.");
        $product5->setPrice(480);
        $product5->setWeight(700);
        $product5->addFeature($feature2);
        $product5->addFeature($feature4);
        $product5->addFeature($feature6);
        $manager->persist($product5);

        $product7 = new Product();
        $product7->setCategory($category_cm);
        $product7->setName("ASUS PRIME Z690-P");
        $product7->setDescription("Cette carte mère au format ATX offre des fonctionnalités de pointe pour faire décoller les performances de votre PC : 4 ports PCI-Express 16x (dont un renforcé), 4 ports M.2 compatibles NVMe, processeur audio Realtek S1220A, un contrôleur Realtek 2.5 Gb pour une connexion irréprochable.");
        $product7->setPrice(230);
        $product7->setWeight(720);
        $product7->addFeature($feature2);
        $product7->addFeature($feature4);
        $product7->addFeature($feature6);
        $product7->addPromotion($promotion);
        $manager->persist($product7);

        $product8 = new Product();
        $product8->setCategory($category_cm);
        $product8->setName("ASRock B550 EXTREME4");
        $product8->setDescription("La carte mère ASRock B550 EXTREME 4 est le socle idéal pour fonder votre configuration basée sur un processeur AMD Ryzen de 3ème génération. Sa conception intègre le chipset AMD B550 et un socket AM4, permettant d'exploiter pleinement les performances des nouveaux processeurs Ryzen.");
        $product8->setPrice(200);
        $product8->setWeight(734);
        $product8->addFeature($feature);
        $product8->addFeature($feature3);
        $product8->addFeature($feature6);
        $manager->persist($product8);

        $product9 = new Product();
        $product9->setCategory($category_cm);
        $product9->setName("GIGABYTE X570S AERO G");
        $product9->setDescription("La carte mère Gigabyte X570S AERO G est le socle idéal pour fonder votre configuration basée sur un processeur AMD Ryzen de deuxième ou troisième génération. Sa conception intègre le chipset AMD X570 et un socket AM4, permettant d'exploiter pleinement les performances des nouveaux processeurs Ryzen.");
        $product9->setPrice(389);
        $product9->setWeight(734);
        $product9->addFeature($feature);
        $product9->addFeature($feature3);
        $product9->addFeature($feature6);
        $product9->addPromotion($promotion3);
        $manager->persist($product9);

        $product13 = new Product();
        $product13->setCategory($category_cm);
        $product13->setName("Gigabyte B560 HD3");
        $product13->setDescription("La carte mère Gigabyte B560 HD3 est le socle idéal pour fonder votre configuration basée sur un processeur Intel Rocket Lake. Sa conception intègre le chipset Intel B560 et un socket LGA 1200.");
        $product13->setInventory($inventory);
        $product13->setPrice(125);
        $product13->setWeight(734);
        $product13->addFeature($feature);
        $product13->addFeature($feature5);
        $product13->addFeature($feature6);
        $manager->persist($product13);

        //Processor
        $product6 = new Product();
        $product6->setCategory($category_proc);
        $product6->setName("Intel Core i9-12900KS (3.4 GHz)");
        $product6->setDescription("Les processeurs Intel® Core™ i9 de 12ème génération (Alder Lake), prennent en charge les dernières innovations, à commencer par le support natif de la DDR5 à 4800 MHz, plus rapide et plus économe ainsi qu'une finesse de gravure de 10 nm.");
        $product6->setPrice(999);
        $product6->setWeight(890);
        $product6->addFeature($feature4);
        $manager->persist($product6);

        $product10 = new Product();
        $product10->setCategory($category_proc);
        $product10->setName("Intel Core i5-12600 (3.3 GHz)");
        $product10->setDescription("Les processeurs Intel® Core™ i5 de 12ème génération (Alder Lake-S), prennent en charge les dernières innovations, à commencer par le support natif de la DDR5 à 4800 MHz, plus rapide et plus économe ainsi qu'une finesse de gravure de 10 nm.");
        $product10->setPrice(788);
        $product10->setWeight(890);
        $product10->addFeature($feature4);
        $manager->persist($product10);

        $product11 = new Product();
        $product11->setCategory($category_proc);
        $product11->setName("AMD Ryzen 9 5950X (3.4 GHz)");
        $product11->setDescription("La gamme Ryzen inaugure une architecture entièrement nouvelle et revue en profondeur qui exprime toute la puissance et l'efficacité des derniers cœurs x86 Vermeer de performances élevées et hautement efficaces. De multiples avancées dans l'architecture combinées avec des technologies de plateformes et de traitement propulsent les utilisateurs vers de nouveaux horizons informatiques.");
        $product11->setPrice(749);
        $product11->setWeight(930);
        $product11->addFeature($feature3);
        $product11->addPromotion($promotion2);
        $manager->persist($product11);

        $product12 = new Product();
        $product12->setCategory($category_proc);
        $product12->setName("AMD Ryzen 5 3600 (3.6 GHz) - Version Bulk");
        $product12->setDescription("Les processeurs AMD Ryzen sont destinés aux créateurs de contenus numériques, aux pionniers de la réalité virtuelle, aux férus de jeux open world et aux passionnés de technologies. Ceux déterminés à imaginer, coder et conquérir sans limite dans l'ère de l'informatique immersive.");
        $product12->setPrice(160);
        $product12->setWeight(938);
        $product12->addFeature($feature3);
        $manager->persist($product12);

        $product14 = new Product();
        $product14->setCategory($category_proc);
        $product14->setName("Intel Celeron G5905 (3.5 GHz)");
        $product14->setDescription("La plate-forme du processeur Intel® Celeron® de nouvelle génération offre les performances nécessaires au traitement informatique de tous les jours avec les dernières capacités.");
        $product14->setPrice(46);
        $product14->setWeight(736);
        $product14->addFeature($feature5);
        $manager->persist($product14);


        //CG
        $product15 = new Product();
        $product15->setCategory($category_cg);
        $product15->setName("ASRock Radeon RX 6950 XT OC Formula");
        $product15->setDescription("La carte graphique Radeon RX 6950 XT est une carte graphique conçue pour les passionnés, à la recherche de la meilleure expérience de jeu possible. Porte-étendard de la nouvelle architecture RDNA2, elle apporte un gain de performance considérable par rapport à la génération précédente et propose le support natif du raytracing, une première pour les cartes graphiques AMD.");
        $product15->setPrice(1490);
        $product15->setWeight(900);
        $product15->addFeature($feature6);
        $product15->addPromotion($promotion);
        $manager->persist($product15);

        $product16 = new Product();
        $product16->setCategory($category_cg);
        $product16->setName("Gigabyte GeForce RTX 3090 GAMING OC");
        $product16->setDescription("La carte graphique GeForce RTX 3090 est l'arme ultime pour tout joueur souhaitant obtenir le meilleur de sa configuration de jeu. Fleuron de la nouvelle architecture Ampere, elle établit un nouveau standard de performances sur PC en maîtrisant avec aisance des technologies de pointe telles que le ray tracing pour des rendus toujours plus saisissants.");
        $product16->setPrice(1950);
        $product16->setWeight(1100);
        $product16->addFeature($feature6);
        $manager->persist($product16);

        $product17 = new Product();
        $product17->setCategory($category_cg);
        $product17->setName("Asus GeForce RTX 3060 TUF GAMING O12G V2 (LHR)");
        $product17->setDescription("Dotée de 12 Go de mémoire GDDR6 rapide, basée sur une interface PCI-Express 4.0, elle est conçue pour jouer en Full HD/QHD dans les meilleures conditions ! Elle ne fera qu'une bouchée des jeux actuels en réalité virtuelle et gèrera sans souci des configurations de jeu multi-écrans. Elle supporte également les fonctionnalités DirectX™ 12 Ultimate pour garantir une expérience rapide et fluide en toutes circonstances.");
        $product17->setPrice(1950);
        $product17->setWeight(1100);
        $product17->addFeature($feature6);
        $manager->persist($product17);

        //Inventaire
        $inventory = new Inventory();
        $inventory->setAmount(0);
        $inventory->setProduct($product17);
        $manager->persist($inventory);

        $inventory2 = new Inventory();
        $inventory2->setProduct($product7);
        $inventory->setAmount(1);
        $manager->persist($inventory2);


        //Cart
        $cart = new CartProduct();
        $cart->setProduct($product);
        $cart->setAmount(2);
        $cart->setUser($user);
        $manager->persist($cart);

        $cart2 = new CartProduct();
        $cart2->setProduct($product15);
        $cart2->setAmount(2);
        $cart2->setUser($user);
        $manager->persist($cart2);

        $cart3 = new CartProduct();
        $cart3->setProduct($product10);
        $cart3->setUser($user);
        $cart3->setAmount(6);
        $manager->persist($cart3);

        //Commande
        $order = new Order();
        $order->setUser($user);
        $order->addProduct($product);
        $order->addProduct($product10);
        $order->addProduct($product11);
        $order->setState("En cours de préparation");
        $manager->persist($order);

        //last : product17


        $manager->flush();

    }
}

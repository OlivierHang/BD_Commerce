<?php

namespace App\Controller\Admin;

use App\Entity\CommandeDetails;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommandeDetailsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CommandeDetails::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add('index', 'detail');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            IntegerField::new('commande.id', 'N° Commande'),
            TextField::new('bd'),
            IntegerField::new('quantity', 'Quantité'),
            MoneyField::new('getPrixDashboard', 'Prix unité')->setCurrency('EUR'),
            MoneyField::new('getTotalDashboard', 'Total')->setCurrency('EUR'),

        ];
    }
}

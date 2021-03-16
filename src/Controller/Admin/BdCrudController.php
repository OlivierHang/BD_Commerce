<?php

namespace App\Controller\Admin;

use App\Entity\Bd;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BdCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bd::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}

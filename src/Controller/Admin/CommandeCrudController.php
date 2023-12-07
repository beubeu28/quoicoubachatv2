<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
        
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->setLabel('Numéro de commande')
                ->setFormTypeOption('disabled','disabled'),
            TextField::new('statut')
                ->setFormTypeOption('attr', $this->isCreatePage($pageName) ? ['disabled' => 'disabled'] : []),
            NumberField::new('montant_Totale')
                ->setFormTypeOption('attr', $this->isCreatePage($pageName) ? ['disabled' => 'disabled'] : [])
                ->setLabel('Prix en €'),
            IdField::new('utilisateurid')
                ->setLabel('ID Utilsateur')
                ->setFormTypeOption('disabled','disabled'),
            DateTimeField::new('date')
                ->setFormTypeOption('attr', $this->isCreatePage($pageName) ? ['disabled' => 'disabled'] : []),

        ];
    }

    private function isCreatePage(string $pageName): bool
    {
        return 'new' === $pageName;
    }

    
}

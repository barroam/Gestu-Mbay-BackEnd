<?php

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        // Création des rôles
        $admin = Role::create(['name' => 'admin']);
        $agriculteur = Role::create(['name' => 'agriculteur']);
        $fournisseur = Role::create(['name' => 'fournisseur']);
        $roa = Role::create(['name' => 'ROA']);

        // Création des permissions
        $manageUsers = Permission::create(['name' => 'manage users']);
        $manageRessources = Permission::create(['name' => 'manage ressources']);
        $manageDemandes = Permission::create(['name' => 'manage demandes']);
        $manageProjets = Permission::create(['name' => 'manage projets']);
        $manageContrats = Permission::create(['name' => 'manage contrats']);

        // Attribution des permissions aux rôles
        $admin->givePermissionTo([$manageUsers, $manageRessources, $manageDemandes, $manageProjets, $manageContrats]);
        $agriculteur->givePermissionTo([$manageDemandes, $manageProjets]);
        $fournisseur->givePermissionTo([$manageRessources]);
        $roa->givePermissionTo([$manageDemandes, $manageProjets, $manageContrats]);
    }

    public function down()
    {
        // Suppression de tous les rôles et permissions
        Role::whereIn('name', ['admin', 'agriculteur', 'fournisseur', 'ROA'])->delete();
        Permission::whereIn('name', ['manage users', 'manage ressources', 'manage demandes', 'manage projets', 'manage contrats'])->delete();
    }
};

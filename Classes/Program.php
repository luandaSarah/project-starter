<?php

namespace Classes;

use Classes\Project;

require_once './Classes/Project.php';

class Program
{

    public function __construct(
        public $handle,
    ) {}
    
    public function init(): void
    {
        //affiche la question
        echo "Début du lancement d'un nouveau projet !\n";

        //Ouvre le clavier en mode lecture seule et stocke le flux dans $handle.

        //stocke l'entrée user 

        $directoryPath = getenv('HOMEDRIVE') . getenv('HOMEPATH') . '\\Documents';
        $name = $this->getProjectName($this->handle);
        $fullPathName = $directoryPath . '\\' . $name;
        $type = $this->getProjectType($this->handle);

        (new Project($name, $fullPathName, $type))
            ->create();
        exit;
    }



    private function getProjectName($handle): string
    {
        echo "Quel est le nom de votre projet ?\n";


        $projectName = trim(fgets($handle));
        if ($projectName) {
            if (preg_match("/^[a-zA-Z_-]/", $projectName)) {
                echo "'$projectName', C'est noté !\n";
            } else {
                echo "Le nom n'est pas valide.\n";
                $this->getProjectName($handle);
            }
        }
        return $projectName;
    }


    private function getProjectType($handle): string
    {
        echo "Quel type de projet voulez-vous réaliser ? vue ? php ? static ?\n";
        $projectType = trim(fgets($handle));
        if ($projectType) {
            if ($projectType !== "static" && $projectType !== "php" && $projectType !== "vue") {
                echo "Vous devez choisir entre static, php ou vue \n";
                $this->getProjectType($handle);
            } else {

                echo "Très bien lancement du projet $projectType !\n";
            }
        }
        return $projectType;
    }
}

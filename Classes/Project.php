<?php

namespace Classes;

use Classes\File;

require_once './Classes/File.php';

class Project
{

    public function __construct(
        public string $name,
        public string $directoryPath,
        public string $type,
    ) {}

    public function create(): void
    {
        // var_dump($this->name . ' ' . $this->directoryPath . ' ' . $this->type);
        // TODO: 
        ////créer Un dossier vide à l'emplacement choisie
        ////1) verifier si le dossier existe déjà
        ////2) creer un dossier a l'emplacement et au nom du projet
        if (file_exists($this->directoryPath) && is_dir($this->directoryPath)) {
            echo "Mince ce dossier existe déjà... '$this->directoryPath'";
            exit;
        } else {
            mkdir($this->directoryPath);
        }

        //3)

        if ($this->type === "static") {
            $this->staticProject();
        }

        $this->gitInit();
        echo "Parfait ! Votre projet est prêt à l'emploi !\n";
        shell_exec("code $this->directoryPath");
    }

    private function staticProject(): void
    {
        // TODO: 
        //Creer un dossier assets vide:
        $assetsDir = $this->directoryPath . '\\assets';
        //1) vefifier si le dossier existe déjà 
        if (file_exists($assetsDir) && is_dir($assetsDir)) {
            echo "une erreur c'est produite\n";
            exit;
        } else {
            mkdir($assetsDir);
        }
        echo "Souhaitez vous utiliser du Sass ? oui ou non\n";
        $handle = fopen("php://stdin", "r");
        $isSass = trim(fgets($handle));
        if ($isSass === "oui") {
            echo "Verification de la version de node...\n";
            $checkNode = shell_exec("node -v");
            if (!$checkNode) {
                echo "Impossible de continuer Node n'est pas installé ou une erreur c'est produite...\n";
                exit;
            }

            echo "Verification de la version de npm...\n";
            $checkNpm = shell_exec("npm -v");
            if (!$checkNpm) {
                echo "Impossible de continuer NPM n'est pas installé ou une erreur c'est produite...\n";
                exit;
            }

            echo "verification/installation de Sass... \n";
            $sassInstall = shell_exec("npm install -g sass");
            if (!$sassInstall) {
                echo "Impossible de continuer une erreur c'est produite...\n";
                exit;
            }

            if (file_exists($assetsDir . '\\scss') && is_dir($assetsDir . '\\scss')) {
                echo "une erreur c'est produite\n";
                exit;
            } else {
                echo "Création du dossier SCSS...\n";
                mkdir($assetsDir . '\\scss');
            }

            new File("$assetsDir\\scss", "style", ".scss")
                ->create();

            new File("$assetsDir\\scss", "variable", ".scss")
                ->create();

            echo "Fin de la configuration du Sass !\n";
        }

        //CSS
        if (file_exists($assetsDir . '\\css') && is_dir($assetsDir . '\\css')) {
            echo "une erreur c'est produite\n";
            exit;
        } else {
            echo "Création du dossier CSS...\n";
            mkdir($assetsDir . '\\css');
        }
        new File("$assetsDir\\css", "style", ".css")
            ->create();


        //TS JS
        echo "Souhaitez vous utiliser du Typescript ? oui ou non\n";

        $handle = fopen("php://stdin", "r");

        $isTypescript = trim(fgets($handle));

        if ($isTypescript === "oui") {
            if (file_exists($assetsDir . '\\ts') && is_dir($assetsDir . '\\ts')) {
                echo "une erreur c'est produite\n";
                exit;
            } else {
                echo "Création du dossier Typescript...\n";
                mkdir($assetsDir . '\\ts');
                new File("$assetsDir\\ts", "main", ".ts")
                    ->create();
            }
        } elseif ($isTypescript === "non") {
            if (file_exists($assetsDir . '\\js') && is_dir($assetsDir . '\\js')) {
                echo "une erreur c'est produite\n";
                exit;
            } else {
                echo "Création du dossier Javascript...\n";
                mkdir($assetsDir . '\\js');
                new File("$assetsDir\\js", "main", ".js")
                    ->create();
            }
        }

        //HTML
        $isTypescript === "oui" ? $htmlScript = "<script src=\"./assets/ts/main.ts\"></script>" : $htmlScript = "<script src=\"./assets/js/main.js\"></script>";
        $htmlContent = "<!DOCTYPE html>\n
<html lang=\"fr\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Document</title>
    <link rel=\"stylesheet\" href=\"./assets/css/style.css\">
    $htmlScript
</head>\n
<body>
    \n
</body>
</html>";

        echo "Création du fichier index.html...\n";
        new File("$this->directoryPath", "index", ".html")
            ->create()
            ->write($htmlContent);
    }


    private function gitInit()
    {

        $handle = fopen("php://stdin", "r");
        echo "Voulez vous creer un dépot Git ? oui ou non ?\n";
        $isGit = trim(fgets($handle));
        if ($isGit === "oui") {
            if (shell_exec("git -v")) {
                if (!file_exists($this->directoryPath . '\\.git')) {
                    if ($git = shell_exec("git init $this->directoryPath")) {
                        echo "$git\n";
                        $this->gitCommit();
                    } else {
                        echo "Une erreur s'est produite\n";
                        exit;
                    }
                } else {
                    echo "Un dépot est déjà existant\n";
                    exit;
                }
            } else {
                echo "Impossible git n'est pas installer sur votre machine\n";
                exit;
            }
        }
    }

    private function gitCommit()
    {
        shell_exec("cd  $this->directoryPath  &&  git add .");
        shell_exec("cd  $this->directoryPath && " . 'git commit -m "feat(INIT) initialisation du projet' . $this->type . '"');
    }
}

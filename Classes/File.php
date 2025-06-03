<?php

namespace Classes;

class File
{
    private array $acceptedFormat = [
        '.php',
        '.css',
        '.scss',
        '.html',
        '.htm',
        '.js',
        '.ts',
        '.json',
        '.xml',
        '.yml',
        '.yaml',
        '.env',
        '.md',
        '.txt',
        '.vue',
        '.jsx',
        '.tsx',
        '.sql',
    ];

    private string $fileName;

    public function __construct(
        public string $dir,
        public string $name,
        public string $format,
    ) {
        $this->fileName = "$this->dir\\$this->name$this->format";
    }

    public function create()
    {
        if (!in_array($this->format, $this->acceptedFormat)) {
            echo "Impossible de créer ce fichier, le format n'est pas correcte\n";
            exit;
        }
        if (file_exists($this->fileName)) {
            echo "une erreur c'est produite\n";
            exit;
        }
        fopen($this->fileName, "x");
        return $this;
    }

    public function write(string $content)
    {

        if (file_exists($this->fileName) && is_writable($this->fileName)) {
            file_put_contents($this->fileName, $content);
        } else {
            echo "Le fichier n'existe pas ou n'est pas accessible.\n";
            exit;
        }
        return $this;
    }
}

<?php

namespace Alura\Calisthenics\Domain\Student;

use Alura\Calisthenics\Domain\Email\Email;
use Alura\Calisthenics\Domain\Endereco\Endereco;
use Alura\Calisthenics\Domain\Video\Video;
use DateTimeInterface;
use Ds\Map;

class Student
{
    private Email $email;
    private DateTimeInterface $bd;
    private Map $watchedVideos;
    private string $fName;
    private string $lName;
    private Endereco $endereco;

    public function __construct(Email $email, DateTimeInterface $bd, string $fName, string $lName, Endereco $endereco)
    {
        $this->watchedVideos = new Map();
        $this->email = $email;
        $this->bd = $bd;
        $this->fName = $fName;
        $this->lName = $lName;
        $this->endereco = $endereco;
    }

    public function recuperaEndereco()
    {
        return $this->endereco->__toString();
    }

    public function getFullName(): string
    {
        return "{$this->fName} {$this->lName}";
    }
    
    public function getEmail(): string
    {
        return $this->email;
    }

    public function getBd(): DateTimeInterface
    {
        return $this->bd;
    }

    public function watch(Video $video, DateTimeInterface $date)
    {
        $this->watchedVideos->put($video, $date);
    }

    public function hasAccess(): bool
    {
        if ($this->watchedVideos->count() === 0) {
            return false;
        }

        $this->watchedVideos->sort(fn (DateTimeInterface $dateA, DateTimeInterface $dateB) => $dateA <=> $dateB);
        /** @var DateTimeInterface $firstDate */
        $firstDate = $this->watchedVideos->first()->value;
        $today = new \DateTimeImmutable();

        return $firstDate->diff($today)->days >= 90;
    }
}

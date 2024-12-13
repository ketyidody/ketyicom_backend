<?php

namespace App\Entity\EasyMedia;

use Adeliom\EasyMediaBundle\Entity\Folder as BaseFolder;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'easy_media__folder')]
class Folder extends BaseFolder
{
    #[ORM\Column(type: 'boolean', nullable: true)]
    protected bool $hidden = false;

    #[ORM\ManyToOne(targetEntity: Folder::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id')]
    protected ?BaseFolder $parent = null;

    #[ORM\OneToMany(targetEntity: Folder::class, mappedBy: 'parent')]
    protected Collection $children;

    public function __toString()
    {
        return $this->getName();
    }
}

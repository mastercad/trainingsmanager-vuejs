<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 */
#[ORM\Table(name: 'sessions')]
#[ORM\Entity]
class Session
{
    #[ORM\Column(name: 'session_id', type: 'string', length: 32, nullable: false, options: ['fixed' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private string $sessionId = '';

    #[ORM\Column(name: 'session_update', type: 'integer', nullable: true)]
    private int|null $sessionUpdate = null;

    #[ORM\Column(name: 'session_lifetime', type: 'integer', nullable: true)]
    private int|null $sessionLifetime = null;

    #[ORM\Column(name: 'session_data', type: 'text', length: 16777215, nullable: true)]
    private string|null $sessionData = null;

    #[ORM\Column(name: 'session_update_time', type: 'integer', nullable: false)]
    private DateTime $sessionUpdateTime;
}

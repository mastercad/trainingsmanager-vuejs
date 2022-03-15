<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table(name="session")
 * @ORM\Entity
 */
class Session
{
    /**
     * @var string
     *
     * @ORM\Column(name="session_id", type="string", length=32, nullable=false, options={"fixed"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sessionId = '';

    /**
     * @var int|null
     *
     * @ORM\Column(name="session_update", type="integer", nullable=true)
     */
    private $sessionUpdate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="session_lifetime", type="integer", nullable=true)
     */
    private $sessionLifetime;

    /**
     * @var string|null
     *
     * @ORM\Column(name="session_data", type="text", length=16777215, nullable=true)
     */
    private $sessionData;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="session_update_time", type="datetime", nullable=false, options={"default"="0000-00-00 00:00:00"})
     */
    private $sessionUpdateTime = '0000-00-00 00:00:00';


}

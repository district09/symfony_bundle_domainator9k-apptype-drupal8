<?php


namespace DigipolisGent\Domainator9k\AppTypes\DrupalEightBundle\Entity;

use DigipolisGent\Domainator9k\AppTypes\DrupalEightBundle\Form\Type\DrupalEightApplicationFormType;
use DigipolisGent\Domainator9k\CoreBundle\Entity\AbstractApplication;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DrupalEightApplication
 * @package DigipolisGent\Domainator9k\AppTypes\DrupalEightBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="drupal_eight_application")
 */
class DrupalEightApplication extends AbstractApplication
{


    const TYPE = "DRUPAL_EIGHT";

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="255")
     */
    protected $installProfile;

    /**
     * @return string
     */
    public static function getApplicationType(): string
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public static function getFormType(): string
    {
        return DrupalEightApplicationFormType::class;
    }

    /**
     * @return string
     */
    public function getInstallProfile(): ?string
    {
        return $this->installProfile;
    }

    /**
     * @param string $installProfile
     */
    public function setInstallProfile(string $installProfile)
    {
        $this->installProfile = $installProfile;
    }

}

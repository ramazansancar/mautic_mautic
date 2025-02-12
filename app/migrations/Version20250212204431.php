<?php

declare(strict_types=1);

namespace Mautic\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Mautic\CoreBundle\Doctrine\AbstractMauticMigration;

final class Version20250212204431 extends AbstractMauticMigration
{
    public function up(Schema $schema): void
    {
        $oldAndNewValues = [
            'Ad yaman'      => 'Adıyaman',
            'Ag r'          => 'Ağrı',
            'Aydin'         => 'Aydın',
            'Bal kesir'     => 'Balıkesir',
            'Bartin'        => 'Bartın',
            'Bingol'        => 'Bingöl',
            'Canakkale'     => 'Çanakkale',
            'Cankir'        => 'Çankırı',
            'Corum'         => 'Çorum',
            'Diyarbakir'    => 'Diyarbakır',
            'Duzce'         => 'Düzce',
            'Elazig'        => 'Elazığ',
            `Eskis'ehir`    => 'Eskişehir',
            `Gms'hane`      => 'Gümüşhane',
            'Igidir'        => 'Iğdır',
            'Icel'          => 'Mersin',
            'Istanbul'      => 'İstanbul',
            'Izmir'         => 'İzmir',
            'Kahramanmaras' => 'Kahramanmaraş',
            'Karabk'        => 'Karabük',
            'Kirikkale'     => 'Kırıkkale',
            'Kirklareli'    => 'Kırklareli',
            `Kirs'ehir`     => 'Kırşehir',
            'Ktahya'        => 'Kütahya',
            'Mugila'        => 'Muğla',
            'Mus'           => 'Muş',
            `Nevs'ehir`     => 'Nevşehir',
            'Nigide'        => 'Niğde',
            `S'anliurfa`    => 'Şanlıurfa',
            `S'rnak`        => 'Şırnak',
            `Us'ak`         => 'Uşak',
        ];

        foreach ($oldAndNewValues as $old => $new) {
            $this->addSql("UPDATE `{$this->prefix}leads` SET `state` = `{$new}` WHERE `state` = `{$old}`");
        }
    }
}
<?php

/**
 * Copyright © 2003-2024 The Galette Team
 *
 * This file is part of Galette (https://galette.eu).
 *
 * Galette is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Galette is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Galette. If not, see <http://www.gnu.org/licenses/>.
 */

namespace GaletteMaps\tests\units;

use Galette\GaletteTestCase;

/**
 * Color tests
 *
 * @author Johan Cwiklinski <johan@x-tnd.be>
 */
class Coordinates extends GaletteTestCase
{
    protected int $seed = 20240517214956;

    /**
     * Cleanup after each test method
     *
     * @return void
     */
    public function tearDown(): void
    {
        $delete = $this->zdb->delete(MAPS_PREFIX . \GaletteMaps\Coordinates::TABLE);
        $this->zdb->execute($delete);
        parent::tearDown();
    }

    public function testCoordinates(): void
    {
        $member = $this->getMemberOne();
        $coords = new \GaletteMaps\Coordinates();
        $this->assertSame([], $coords->getCoords($member->id));
        $this->assertSame([], $coords->listCoords());

        $this->logSuperAdmin();
        $this->assertSame([], $coords->getCoords($member->id));
        $this->assertSame([], $coords->listCoords());

        //set coordinates for member one
        $this->assertTrue($coords->setCoords($member->id, 50.362038,3.472998));
        $this->assertSame(
            [
                'id_adh' => $member->id,
                'latitude' => '50.362038',
                'longitude' => '3.472998'
            ],
            (array)$coords->getCoords($member->id)
        );
        $this->assertSame(
            [
                [
                    'id_adh' => $member->id,
                    'lat' => '50.362038',
                    'lng' => '3.472998',
                    'name' => 'DURAND René',
                    'nickname' => 'ubertrand'
                ]
            ],
            $coords->listCoords()
        );

        //update coordinates for member one
        $this->assertTrue($coords->setCoords($member->id, 51.362038,3.572998));

        //remove coordinates for member one
        $this->assertTrue($coords->removeCoords($member->id));
        $this->assertSame([], $coords->getCoords($member->id));
    }
}

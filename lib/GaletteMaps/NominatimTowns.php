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

declare(strict_types=1);

namespace GaletteMaps;

use Analog\Analog;
use Laminas\Db\Sql\Predicate\PredicateSet;
use Laminas\Db\Sql\Predicate\Expression;
use Galette\Core\Preferences;

/**
 * Towns GPS coordinates via nominatim
 *
 * @author Johan Cwiklinski <johan@x-tnd.be>
 */

class NominatimTowns
{
    private Preferences $preferences;

    /** @var array<string, string>  */
    private array $query_options = array(
        'format'            => 'xml',
        'addressdetails'    => '1'
    );
    private string $uri = 'http://nominatim.openstreetmap.org/search';

    /**
     * Constructor
     *
     * @param Preferences $preferences Preferences instance
     */
    public function __construct(Preferences $preferences)
    {
        $this->preferences = $preferences;
    }

    /**
     * Search a town by its name
     *
     * @param string  $town    Town name
     * @param ?string $country Country name (optional)
     *
     * @return array<int, array<string, string>>
     */
    public function search(string $town, string $country = null): array
    {
        if (!$town || trim($town) === '') {
            throw new \RuntimeException(
                "Town has not been specified!"
            );
        }

        $options = $this->query_options;
        $options['city'] = $town;
        if ($country !== null) {
            $options['country'] = $country;
        }

        $url_options = array();
        foreach ($options as $key => $value) {
            $url_options[] = $key . '=' . urlencode($value);
        }

        $url = $this->uri . '?' . implode('&', $url_options);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'GaletteMaps/' . $this->preferences->pref_nom);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new \RuntimeException(
                "Error on nominatim request:\n\tURI:" . $url .
                "\n\tOptions:\n" . print_r($options, true)
            );
        }

        //get request infos
        $infos = curl_getinfo($ch);
        if ($infos['http_code'] !== 200) {
            //At this point, core has been created, but is failing
            //to load in solr.
            throw new \RuntimeException(
                "Error on nominatim:\n\tURI: " . $url .
                "\n\Options: " . print_r($options, true)
            );
        }

        $xml = new \SimpleXMLElement($response);
        $towns = $xml->xpath('//place');

        $results = array();
        foreach ($towns as $town) {
            if ($town->city || $town->town || $town->village) {
                $unique = true;
                foreach ($results as $elt) {
                    if (
                        $elt['latitude'] == (string)$town['lat']
                        && $elt['longitude'] == (string)$town['lon']
                    ) {
                        $unique = false;
                        Analog::log(
                            'Town is already in list, ignore.',
                            Analog::INFO
                        );
                    }
                }

                if ($unique === true) {
                    $full_name = null;
                    if ($town->city) {
                        $full_name = (string)$town->city;
                    } elseif ($town->town) {
                        $full_name = (string)$town->town;
                    } elseif ($town->village) {
                        $full_name = (string)$town->village;
                    } else {
                        $full_name = (string)$town['display_name'];
                    }

                    $results[] = array(
                        'full_name' => $full_name,
                        'latitude'  => (string)$town['lat'],
                        'longitude' => (string)$town['lon']
                    );
                }
            } else {
                Analog::log(
                    'Nominatim result "' . $town['display_name'] .
                    '" is not a town',
                    Analog::INFO
                );
            }
        }

        return $results;
    }
}

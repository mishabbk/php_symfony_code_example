<?php

namespace App\Modele\Document;

use App\Modele\SearchBondTrait;
use App\Modele\SearchLotImageTrait;
use App\Modele\SearchLotTrait;
use App\Modele\SearchPersonTrait;
use App\Modele\SearchProjectTrait;
use App\Modele\SearchPropertyTrait;
use App\Modele\SearchSasTrait;
use App\Modele\SearchTrait;

class FilterSearchDocument
{
    use SearchTrait;
    use SearchSasTrait;
    use SearchProjectTrait;
    use SearchPersonTrait;
    use SearchBondTrait;
    use SearchPropertyTrait;
    use SearchLotTrait;
    use SearchLotImageTrait;
}

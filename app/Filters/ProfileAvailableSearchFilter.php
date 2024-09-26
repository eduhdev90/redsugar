<?php

namespace App\Filters;

use App\Filters\Atomic\ProfileAvailableGenderFilter;
use App\Filters\Atomic\Search\SearchAcademicBackgroundFilter;
use App\Filters\Atomic\Search\SearchAgeFilter;
use App\Filters\Atomic\Search\SearchBeardColorFilter;
use App\Filters\Atomic\Search\SearchBeardSizeFilter;
use App\Filters\Atomic\Search\SearchChildrenFilter;
use App\Filters\Atomic\Search\SearchDrinkFilter;
use App\Filters\Atomic\Search\SearchEyeColorFilter;
use App\Filters\Atomic\Search\SearchHairColorFilter;
use App\Filters\Atomic\Search\SearchHairTypeFilter;
use App\Filters\Atomic\Search\SearchHeightFilter;
use App\Filters\Atomic\Search\SearchLocationFilter;
use App\Filters\Atomic\Search\SearchMaritalStatusFilter;
use App\Filters\Atomic\Search\SearchMonthlyIncomeFilter;
use App\Filters\Atomic\Search\SearchPersonalPatrimonyFilter;
use App\Filters\Atomic\Search\SearchPhysicalTypeFilter;
use App\Filters\Atomic\Search\SearchSkinToneFilter;
use App\Filters\Atomic\Search\SearchSmokeFilter;
use App\Filters\Atomic\Search\SearchStyleLifeFilter;
use App\Filters\Atomic\Search\SearchTattooFilter;
use Illuminate\Database\Eloquent\Builder;

class ProfileAvailableSearchFilter extends ProfileAvailableDefaultFilter
{
    protected array $request_filters = [
        's_academic' => SearchAcademicBackgroundFilter::class,
        's_beardcolor' => SearchBeardColorFilter::class,
        's_beardsize' => SearchBeardSizeFilter::class,
        's_children' => SearchChildrenFilter::class,
        's_drink' => SearchDrinkFilter::class,
        's_eyecolor' => SearchEyeColorFilter::class,
        's_gender' => ProfileAvailableGenderFilter::class,
        's_haircolor' => SearchHairColorFilter::class,
        's_hairtype' => SearchHairTypeFilter::class,
        's_maritalstatus' => SearchMaritalStatusFilter::class,
        's_income' => SearchMonthlyIncomeFilter::class,
        's_patrimony' => SearchPersonalPatrimonyFilter::class,
        's_physical' => SearchPhysicalTypeFilter::class,
        's_skintone' => SearchSkinToneFilter::class,
        's_smoke' => SearchSmokeFilter::class,
        's_stylelife' => SearchStyleLifeFilter::class,
        's_tattoo' => SearchTattooFilter::class,
        's_height' => SearchHeightFilter::class,
        's_age' => SearchAgeFilter::class,
        's_location' => SearchLocationFilter::class,
    ];

    public function __construct()
    {
        $this->filters = array_merge($this->filters, $this->request_filters);
    }

    public function apply(Builder $query): Builder
    {
        $query = parent::apply($query);
        return $query;
    }

    public function receivedFilters(): array
    {
        $this->data = parent::receivedFilters();
        return array_merge(
            $this->data,
            array_filter(
                request()->only(array_keys($this->request_filters)),
                'strlen'
            )
        );
    }
}

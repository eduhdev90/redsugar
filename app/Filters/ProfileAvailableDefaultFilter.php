<?php

namespace App\Filters;

use App\Filters\Atomic\ProfileAvailableGenderFilter;
use App\Filters\Atomic\ProfileAvailableHighLightFilter;
use App\Filters\Atomic\ProfileAvailableIsFavoritedFilter;
use App\Filters\Atomic\ProfileAvailableMostViewedFilter;
use App\Filters\Atomic\ProfileAvailableProfileFilter;
use App\Models\Views\ProfileAvailableView;
use App\ValueObjects\Gender;
use App\ValueObjects\Interested;
use App\ValueObjects\Profile;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProfileAvailableDefaultFilter
{
    protected array $filters = [
        'profile' => ProfileAvailableProfileFilter::class,
        'gender' => ProfileAvailableGenderFilter::class,
        'highlight' => ProfileAvailableHighLightFilter::class,
        'most_viewed' => ProfileAvailableMostViewedFilter::class,
        'is_favorited' => ProfileAvailableIsFavoritedFilter::class
    ];

    protected ?ProfileAvailableView $profile;
    protected array $data = [];

    public function apply(Builder $query): Builder
    {
        foreach ($this->receivedFilters() as $name => $value) {
            $filterInstance = new $this->filters[$name];

            if (is_array($value)) {
                $query = $filterInstance($query, ...$value);
                continue;
            }

            $query = $filterInstance($query, $value);
        }

        return $query;
    }

    public function receivedFilters(): array
    {
        $this->setProfileScope();
        $this->setInterestedScope();
        $this->data['highlight'] = null;
        $this->data['most_viewed'] = null;
        $this->data['is_favorited'] = null;
        return $this->data;
    }

    private function getProfileLogged(): ProfileAvailableView
    {
        if (empty($this->profile)) {
            $this->profile = auth()->user()->profile_available;
            if (is_null($this->profile)) {
                throw new HttpException(400, "Perfil aguardando aprovação!");
            }
        }
        return $this->profile;
    }

    public function setProfileScope(): void
    {
        $this->data['profile'] = match ($this->getProfileLogged()->profile) {
            Profile::SUGAR_DADDY_MOMMY->value => Profile::SUGAR_BABY->value,
            Profile::SUGAR_BABY->value => Profile::SUGAR_DADDY_MOMMY->value
        };
    }

    public function setInterestedScope(): void
    {
        $gender = match ($this->getProfileLogged()->interested) {
            Interested::MAN->value => Gender::MALE->value,
            Interested::WOMAN->value => Gender::FEMALE->value,
            default => null
        };

        if ($gender) {
            $this->data['gender'] = $gender;
        }
    }
}

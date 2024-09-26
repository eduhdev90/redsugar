<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\ValueObjects\AcademicBackground;
use App\ValueObjects\BeardColor;
use App\ValueObjects\BeardSize;
use App\ValueObjects\Children;
use App\ValueObjects\Drink;
use App\ValueObjects\EyeColor;
use App\ValueObjects\Gender;
use App\ValueObjects\HairColor;
use App\ValueObjects\HairType;
use App\ValueObjects\Interested;
use App\ValueObjects\MaritalStatus;
use App\ValueObjects\MonthlyIncome;
use App\ValueObjects\PersonalPatrimony;
use App\ValueObjects\PhysicalType;
use App\ValueObjects\Profile;
use App\ValueObjects\SkinTone;
use App\ValueObjects\Smoke;
use App\ValueObjects\StyleLife;
use App\ValueObjects\Tattoo;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("Usuário", "Endpoint relacionado ao usuário")]
#[Subgroup("Cadastro", "Endpoint para cadastro de usuário")]
class ProfileFieldsController extends Controller
{
    /**
     * Lista de campos para cadastro
     */
    public function __invoke(): JsonResponse
    {
        $data = [];
        $data['gender'] = Gender::forSelect();
        $data['profile'] = Profile::forSelect();
        $data['interested'] = Interested::forSelect();
        $data['style_life'] = StyleLife::forSelect();
        $data['physical_type'] = PhysicalType::forSelect();
        $data['skin_tone'] = SkinTone::forSelect();
        $data['eye_color'] = EyeColor::forSelect();
        $data['drink'] = Drink::forSelect();
        $data['smoke'] = Smoke::forSelect();
        $data['hair_color'] = HairColor::forSelect();
        $data['hair_type'] = HairType::forSelect();
        $data['marital_status'] = MaritalStatus::forSelect();
        $data['beard_size'] = BeardSize::forSelect();
        $data['beard_color'] = BeardColor::forSelect();
        $data['children'] = Children::forSelect();
        $data['tattoo'] = Tattoo::forSelect();
        $data['academic_background'] = AcademicBackground::forSelect();
        $data['monthly_income'] = MonthlyIncome::forSelect();
        $data['personal_patrimony'] = PersonalPatrimony::forSelect();

        return response()->json($data);
    }
}

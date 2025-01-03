<?php

namespace App\Models;

use App\Traits\{HasAddress, HasPhone};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};
use Illuminate\Support\Facades\Storage;

/**
 * Class Company
 *
 * @property string $id
 * @property string|null $name
 * @property string|null $cnpj
 * @property string|null $opened_in
 * @property string $zip_code
 * @property string $street
 * @property string $number
 * @property string $neighborhood
 * @property string $city
 * @property string $state
 * @property string|null $complement
 * @property string|null $about
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $logo
 * @property string|null $favicon
 * @property string|null $x
 * @property string|null $instagram
 * @property string|null $facebook
 * @property string|null $linkedin
 * @property string|null $youtube
 * @property string|null $whatsapp
 * @property float|null $interest_rate_sale
 * @property float|null $interest_rate_installment
 * @property float|null $late_fee
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class Company extends BaseModel
{
    use HasFactory;
    use HasAddress;
    use HasPhone;

    protected $table = 'company';

    protected $fillable = [
        'tenant_id',
        'name',
        'cnpj',
        'opened_in',
        'about',
        'email',
        'logo',
        'favicon',
        'x',
        'instagram',
        'facebook',
        'linkedin',
        'youtube',
        'whatsapp',
        'interest_rate_sale',
        'interest_rate_installment',
        'late_fee',

        'font_family',
        'font_color',
        'promo_price_color',
        'body_bg_color',
        'card_color',
        'card_text_color',
        'nav_color',
        'footer_color',
        'footer_text_color',
        'link_color',
        'link_text_color',
        'btn_1_color',
        'btn_1_text_color',
        'btn_2_color',
        'btn_2_text_color',
        'btn_3_color',
        'btn_3_text_color',
        'select_color',
        'select_text_color',
        'check_color',
        'check_text_color',
        'bg_img',
        'bg_img_opacity',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    protected static function booted()
    {
        static::updating(function (Company $company) {
            $logoToDelete = $company->getOriginal('logo');

            if ($company->isDirty('logo') && $company->logo !== null) {
                Storage::delete("public/$logoToDelete");
            }

            $faviconToDelete = $company->getOriginal('favicon');

            if ($company->isDirty('favicon') && $company->favicon !== null) {
                Storage::delete("public/$faviconToDelete");
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'label', 'value', 'type', 'sort_order'];

    /**
     * Settings the admin can edit, and the config value each one overrides.
     * The config files keep the defaults; the database wins when filled.
     */
    public const DEFINITIONS = [
        'store' => [
            'shipping_fee' => ['Shipping fee (₹)', 'number', 'site.shipping.fee'],
            'free_shipping_from' => ['Free shipping on prepaid orders from (₹)', 'number', 'site.shipping.free_from'],
            'announcement' => ['Announcement line', 'text', 'site.announcement'],
        ],
        'contact' => [
            'contact_email' => ['General email', 'email', 'site.contact.email'],
            'contact_order_email' => ['Orders email', 'email', 'site.contact.order_email'],
            'contact_address' => ['Address', 'textarea', 'site.contact.address'],
            'contact_phone_1' => ['General enquiries phone', 'text', 'site.contact.phones.0.number'],
            'contact_phone_2' => ['Orders and schools phone', 'text', 'site.contact.phones.1.number'],
            'contact_phone_3' => ['Specimen copies phone', 'text', 'site.contact.phones.2.number'],
        ],
        'social' => [
            'social_instagram' => ['Instagram URL', 'url', 'site.social.Instagram'],
            'social_youtube' => ['YouTube URL', 'url', 'site.social.YouTube'],
            'social_facebook' => ['Facebook URL', 'url', 'site.social.Facebook'],
            'social_linkedin' => ['LinkedIn URL', 'url', 'site.social.LinkedIn'],
        ],
    ];

    /** Copies saved settings over the config defaults. Called once per request. */
    public static function applyToConfig(): void
    {
        try {
            $values = Cache::rememberForever('settings.all', fn () => Schema::hasTable('settings') ? static::pluck('value', 'key')->all() : []);
        } catch (\Throwable) {
            return; // database not ready (fresh install, CI)
        }

        foreach (self::DEFINITIONS as $fields) {
            foreach ($fields as $key => [$label, $type, $configKey]) {
                if (isset($values[$key]) && $values[$key] !== '') {
                    config([$configKey => $type === 'number' ? (int) $values[$key] : $values[$key]]);
                }
            }
        }
    }

    public static function flush(): void
    {
        Cache::forget('settings.all');
    }
}

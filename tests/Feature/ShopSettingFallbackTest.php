<?php

namespace Tests\Feature;

use App\Models\ShopSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ShopSettingFallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_fallback_shop_settings_from_config_when_no_database_record_exists(): void
    {
        Config::set('shop.company.name', 'Config Shop Name');
        Config::set('shop.company.email', 'config@example.com');
        Config::set('shop.company.phone', '1234567890');
        Config::set('shop.company.address_line1', 'Config Address 1');
        Config::set('shop.company.address_line2', 'Config Address 2');
        Config::set('shop.company.state', 'Config State');
        Config::set('shop.company.state_code', '99');
        Config::set('shop.company.logo_path', 'storage/config-logo.png');
        Config::set('shop.cgst_rate', 5.0);
        Config::set('shop.sgst_rate', 5.0);
        Config::set('shop.gst_rate', 10.0);

        $settings = shop();

        $this->assertInstanceOf(ShopSetting::class, $settings);
        $this->assertSame('Config Shop Name', $settings->name);
        $this->assertSame('config@example.com', $settings->email);
        $this->assertSame('1234567890', $settings->phone);
        $this->assertSame('Config Address 1', $settings->address_line1);
        $this->assertSame('Config Address 2', $settings->address_line2);
        $this->assertSame('Config State', $settings->state);
        $this->assertSame('99', $settings->state_code);
        $this->assertSame('config-logo.png', $settings->logo_path);
        $this->assertSame(5.0, $settings->cgst_rate);
        $this->assertSame(5.0, $settings->sgst_rate);
        $this->assertSame(10.0, $settings->gst_rate);
    }
}

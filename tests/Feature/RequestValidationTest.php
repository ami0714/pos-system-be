<?php

namespace Tests\Feature;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Requests\DashboardIndexRequest;
use App\Http\Requests\ProductBarcodeRequest;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\SaleCheckoutRequest;
use App\Http\Requests\SettingUpdateRequest;
use App\Http\Requests\StockAdjustRequest;
use App\Http\Requests\StockLogRequest;
use App\Http\Requests\StockStockInRequest;
use Tests\TestCase;

class RequestValidationTest extends TestCase
{
    public function test_all_expected_form_request_classes_exist(): void
    {
        $this->assertTrue(class_exists(CategoryStoreRequest::class));
        $this->assertTrue(class_exists(CategoryUpdateRequest::class));
        $this->assertTrue(class_exists(ProductStoreRequest::class));
        $this->assertTrue(class_exists(ProductCategoryRequest::class));
        $this->assertTrue(class_exists(ProductBarcodeRequest::class));
        $this->assertTrue(class_exists(SaleCheckoutRequest::class));
        $this->assertTrue(class_exists(StockLogRequest::class));
        $this->assertTrue(class_exists(StockStockInRequest::class));
        $this->assertTrue(class_exists(StockAdjustRequest::class));
        $this->assertTrue(class_exists(DashboardIndexRequest::class));
        $this->assertTrue(class_exists(SettingUpdateRequest::class));
    }
}

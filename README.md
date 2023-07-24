# 本地機啟動流程
1. 複製專案至本地資料夾
`git clone https://github.com/jamie328/exchange-rate-api.git`
2. 基本設置 & 安裝 php 套件
`composer install` 
`php artisan key:generate`
3. 本地機器啟動 laravel
`php artisan serve`
預設位址為 http://127.0.0.1:8000/

# 測試 API 功能
1. 應用 Postman 或是 網址列輸入 http://127.0.0.1:8000/api/currency/exchange?source=${source}&target={$target}&amount={$amount}
![Postman 畫面](https://hackmd.io/_uploads/rkRiYco5h.png)
![Browser 畫面](https://hackmd.io/_uploads/SJnBKci5n.png)

2. 如要進行測試，可應用 PHPStorm 內建 Plugins 或是下終端機指令
![Plugin 按鈕單元測試](https://hackmd.io/_uploads/SymL55jq2.png)
`./vendor/bin/phpunit tests/Unit/Currency/CurrencyServiceTest.php --testdox`
![終端機執行單元測試](https://hackmd.io/_uploads/BJeis9jqh.png)

# 專案架構說明
簡單拆分 Controller/UseCase/Service 架構，Controller 作為進入點，UseCase 為處理商業邏輯，UseCase 中的 Request 作為過濾資料與驗證輸入參數，再進入商業邏輯前，將錯誤的 Payload 提前擋下，UseCase 則是處理此次 API 商業邏輯，Response 則是整理 API 要回傳的格式，Service 則是抽出共同的商業邏輯，後續可以於不同情境中複用。
實作詳情可以參照 `jamie328/exchange-rate-api/app` 的內容。 
測試詳情可以參照 `jamie328/exchange-rate-api/tests/Unit/Currency` 的內容。 